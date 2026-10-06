# AGENTS.md — Bagisto Varnish

Guidance for AI agents (and humans) working inside the `bagisto/bagisto-varnish` package.
The [README](./README.md) is the user-facing install and configuration guide; this file is
how the package is wired into Bagisto and what to watch for when changing it.

## Overview

The package puts Varnish in front of Bagisto: storefront responses are tagged so Varnish can
cache and ban them, and the parts of the page that differ per customer are fetched separately
so a cached page stays correct for everyone.

- **Namespace:** `Webkul\Varnish` → `src/`
- **Branch:** a single `master`, tracking the newest Bagisto line — currently **2.5** (Laravel 13,
  PHP 8.4). There is no long-lived 2.4 branch; that line is held by the **`v2.1.0`** tag, so a 2.4
  fix means branching from the tag. The README carries the
  [version matrix](./README.md#version-compatibility) — update it there, not here, whenever a
  release targets a new Bagisto line.
- **Registration:** `VarnishServiceProvider` is added to the application's
  `bootstrap/providers.php` **last**. Composer auto-discovery is deliberately not declared.

## Why the provider must be registered last

`boot()` calls `$router->aliasMiddleware('cache.response', VarnishCacheMiddleware::class)` —
`Http\Middleware\VarnishCache`, imported under that alias — replacing the alias **Bagisto's own
`ShopServiceProvider` already registers** for `Webkul\Shop\Http\Middleware\CacheResponse`. Core's
storefront routes carry that alias, so this swap is what redirects them from Bagisto's built-in
page cache to Varnish tagging.

It is a deliberate takeover, not an accident, and it is decided by boot order: registered before
the Shop package, Shop overwrites it again and nothing is tagged. Verify it after any change:

```bash
php artisan tinker --execute='echo app("router")->getMiddleware()["cache.response"];'
# expect Webkul\Varnish\Http\Middleware\VarnishCache
```

## The ESI holes — the heart of the package

A cached page cannot carry customer-specific markup, so the package cuts those blocks out and
fetches them per request. Every fragment is served by `EsiController::loadView()` at
`/esi?tag=<tag>` (route `varnish.esi.load`), and `src/Config/varnish.php` maps each tag to a view
under `esi.views`. There are **two** mechanisms, and which one a hole uses is not a free choice:

**1. `<x-varnish::dynamic-view view="…">` — for the four dropdowns.**
`src/Resources/views/shop/components/dynamic-view/index.blade.php` registers a Vue component that
`fetch`es the fragment and injects it with `v-html`. Used in the two overridden headers for
`customer-desktop-dropdown`, `customer-mobile-md-dropdown`, `customer-mobile-sm-dropdown` and
`customer-account-profile-drawer`. The HTML comment above each hole states the reason:
**Varnish's own ESI cannot resolve a Blade component**, so the package does the fetch itself.

Two consequences:

- **It fetches on the first `mousemove` or `touchstart`, not on mount.** Until the visitor moves
  the pointer the dropdown is genuinely empty. A `curl` of the page, and a Playwright test that
  clicks without moving first, both see nothing — that is the design, not a bug.
- **`v-html` content is not compiled by Vue.** A fragment must be plain HTML and Blade; a Vue
  component or directive inside one silently does nothing. The current fragments observe this.

**2. A real `<esi:include>` — for `customer-status`.**
`src/Resources/views/shop/view-render-events/customer-status.blade.php` is a hidden
`<span id="isCustomerFlag">` holding a genuine `<esi:include src="/esi?tag=customer-status" />`,
which Varnish resolves because it is plain markup. The fragment is one line —
`{{ auth()->guard('customer')->check() }}` — so the span reads `1` or empty.

It reaches the page through `Event::listen('bagisto.shop.layout.body.before', …)` →
`$viewRenderEventManager->addTemplate(...)`, so **no core view is edited to place it**. This is
what the third override depends on: `components/products/card.blade.php` differs from core in one
line, reading `document.getElementById('isCustomerFlag')` instead of calling
`auth()->guard('customer')->check()` while the page is being cached. Remove the listener and every
product card decides wishlist state from whoever warmed the cache.

**The three overridden views** come from `publishables/views/shop`, published into
`resource_path('themes/default/views')`:

| Override | Why |
|---|---|
| `components/layouts/header/desktop/bottom.blade.php` | one dropdown hole |
| `components/layouts/header/mobile/index.blade.php` | three holes |
| `components/products/card.blade.php` | the one-line `isCustomerFlag` read |

`/flashes` (`varnish.session.flashes`) is the companion to all of this: a cached page cannot carry
a session flash message, so the storefront reads them from there as JSON instead.

### Keeping the overrides current — read before upgrading Bagisto

**An override only replaces a customer block; everything else in those three files is core's
markup, and it goes stale silently.** A stale override does not error — it quietly reverts core's
newer header to the version the package was forked from.

When Bagisto changes those views, **re-derive the override from core's current file** rather than
merging the old one forward. A three-way merge looks like it works and leaves structurally broken
Blade, because the region being replaced moves:

- In Bagisto 2.4 the `@guest('customer')` / `@auth('customer')` pair sat **inside one**
  `<x-slot:content>`.
- In 2.5 each branch wraps **its own content slot** (`<x-slot:content>` for guests,
  `<x-slot:content class="p-0!">` for authenticated customers).

The package supplies **one** content slot holding the dynamic view; the fragment branches on
guest/auth internally, so it serves both.

**The fragments go stale the same way**, and are easier to forget: each one is a copy of the
customer block core used to render inline, so when core restyles its dropdown the fragment keeps
the old markup. Diff each fragment against the matching block in core's current
`header/desktop/bottom.blade.php` and `header/mobile/index.blade.php` — compare the routes,
translation keys, `view_render_event` hooks and config checks rather than the Tailwind class
order, which differs harmlessly.

**`php artisan view:cache` is not a syntax check** — it reports success on Blade whose compiled
PHP has a parse error, and the error only fires when the page renders. After touching an override,
compile it and lint the output, then load the page and watch the browser console: unbalanced
slots surface as a **Vue compiler error**, not a PHP one.

## Purging

`Providers/EventServiceProvider` bans the matching tags on product, category, CMS page, review,
URL-rewrite, order, refund, channel, configuration and theme-section events — create/update on the
`after` hook, delete on the `before` one, because the row has to still exist to build its tags.
`FlushVarnishCache` is the console entry point.

Purges go to the **Varnish Host URL**, not the backend URL — they are different settings, and
pointing them at Bagisto means no ban ever reaches Varnish.

## Admin configuration

`src/Config/system.php` is merged into `core` and nests under the top-level `cache_management`
group. Bagisto ships that group from 2.4 onwards — on 2.5 it comes from `Webkul\Admin`'s own
`system.php` — so the file **declares it only when it is absent**. Do not remove that guard, or the
section is declared twice.

The guard reads `config('core')` at merge time, which is the **second** reason the provider is
registered last: run before `AdminServiceProvider`, it sees no `cache_management`, declares its
own, and Admin's later merge appends a duplicate. Check after a change:

```bash
php artisan tinker --execute='echo collect(config("core"))->pluck("key")->filter(fn($k)=>$k==="cache_management")->count();'
# expect 1
```

## Conventions

- **Translation keys are kebab-case, route names are snake_case.** The same feature is
  `shop::app.eu-withdrawal.guest-dropdown.link` as a key and `shop.eu_withdrawal.guest.lookup` as
  a route. Never rename one by searching for the other's spelling.
- **All 22 Bagisto locales** exist under `src/Resources/lang/`. `en` is the canonical structure;
  every other locale carries the identical key set. `php artisan bagisto:translations:check` does
  **not** cover this package — it scans only `base_path('packages/Webkul')`, so a clone symlinked
  in through a path repository is never read. Compare each locale against `en` yourself.
- **Code style:** `vendor/bin/pint`, run from the application root.
- After changing providers, config or routes: `php artisan optimize:clear`, and
  `php artisan responsecache:clear` before trusting what a page renders.

## Verifying a change

```bash
php artisan vendor:publish --provider="Webkul\Varnish\Providers\VarnishServiceProvider" --force
php artisan optimize:clear && php artisan responsecache:clear

# every ESI fragment must answer 200
for t in customer-desktop-dropdown customer-mobile-md-dropdown customer-mobile-sm-dropdown \
         customer-account-profile-drawer customer-status; do
    curl -s -o /dev/null -w "$t %{http_code}\n" "$APP_URL/esi?tag=$t"
done
```

Then load the storefront and confirm the browser console is clean — the overrides are where
breakage shows up first.
