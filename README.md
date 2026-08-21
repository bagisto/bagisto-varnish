# Varnish Integration for Bagisto

This package puts **Varnish Cache** in front of a Bagisto storefront, so a cached page is served
without touching PHP at all, while personalised parts of the page still come from the application
through **ESI (Edge Side Includes)** or **AJAX dynamic views**.

---

## 📌 Features

* ⚡ **Full-page caching** via Varnish
* 🔄 **Automatic cache purging** when a product, category, page, section or configuration changes
* 🔍 **ESI support** for dynamic blocks
* 🖱 **AJAX-based dynamic view loading** for improved Core Web Vitals
* 🛠 **Artisan command** for cache management
* 🖥 **Admin tools** for purging cache and exporting a ready-made VCL
* 🎨 **Theme-ready Blade components**
* 🛡 **Middleware** that tags responses and sets cache headers

---

## 🔄 How it fits together

Varnish sits **between** your public web server and Bagisto. Three processes, three ports:

```
                                                     ┌─────────────────────────────┐
  browser ──▶ nginx :443 ──▶ Varnish :6081 ──▶ nginx :8080 ──▶ php-fpm │ Bagisto  │
              (TLS)          (the cache)        (the backend)          └──────────┘
                                  ▲
                                  │  PURGE requests from Bagisto go here
                                  └──────────────────── "Varnish Host URL"
```

**The single most common mistake is confusing the two host settings.** They describe *different
hops*, and getting them the wrong way round fails silently — pages cache but never update:

| Setting | Describes | Used for |
|---|---|---|
| **Varnish Host URL** | Varnish itself (`:6081`) | Where Bagisto **sends PURGE requests**. Wrong value ⇒ nothing is ever purged. |
| **Backend Host URL / Port** | Bagisto's own web server (`:8080`) | What is written into the **exported VCL** as Varnish's backend. Wrong value ⇒ Varnish cannot reach your site. |

Ports above are examples — use whatever your server actually listens on. Confirm with:

```bash
pgrep -a varnishd                 # the -a flag shows Varnish's listen port
grep -n listen /etc/nginx/sites-enabled/your-site
```

---

## ✅ Requirements

* Bagisto **2.4.x**
* PHP **8.3+** (matching Bagisto 2.4's own `>=8.3 <8.5`)
* Varnish **6.0 or newer** (tested on 6.x and 7.x)

---

## 📦 Installation

### 1. Install via Composer

```bash
composer require bagisto/bagisto-varnish
```

### 2. Register the service provider

Bagisto 2.4 keeps providers in **`bootstrap/providers.php`**, which returns a *flat array* — not the
`'providers' => [...]` block used by older Laravel versions:

```php
<?php

use Webkul\Varnish\Providers\VarnishServiceProvider;
// ...other Webkul providers

return [
    // ...other Webkul providers

    VarnishServiceProvider::class,   // ← must be LAST
];
```

> **Order matters, and auto-discovery is deliberately disabled.** The provider re-registers the
> `cache.response` middleware alias so storefront routes are tagged for Varnish instead of being
> stored by Bagisto's built-in page cache. Registered before the Shop package, the Shop package
> would simply overwrite it again and nothing would be tagged.

### 3. Publish the config

```bash
php artisan vendor:publish --provider="Webkul\Varnish\Providers\VarnishServiceProvider"
php artisan optimize:clear
```

---

## ⚙️ Configuration

**Do this before exporting the VCL** — the VCL file is generated *from* these values.

Go to **Admin → Configuration → Cache Management → Varnish → Configuration**.

1. Set **Cache Application** to **Varnish**.

   > The remaining fields stay **hidden** until you do — they depend on this one. If you cannot see
   > the settings below, this is why.

2. Fill in the rest:

| Field | Example | Notes |
|---|---|---|
| **Access List** | `localhost,127.0.0.1,::1` | IPs allowed to issue PURGE. Must include the host Bagisto runs on. |
| **Varnish Host URL** | `127.0.0.1:6081` | Where purges are sent. See the table above. |
| **Backend Host URL** | `127.0.0.1` | Host only — a scheme or port here is ignored, the port has its own field. |
| **Backend Host Port** | `8080` | The port your **backend** web server listens on, not Varnish's. |
| **Grace Period** | `3d` | How long stale content may be served while the backend is slow or down. |

3. Save.

### Keeping the settings

These live in `core_config`, so anything that rebuilds the database (`bagisto:install`, which runs
`db:wipe` + `migrate:fresh`) removes them. On a demo or CI environment that reinstalls regularly,
seed the values instead of entering them by hand.

---

## 🖥 Install the VCL

1. **Admin → Configuration → Cache Management → Varnish → Configuration → Export VCL.**
   The downloaded file already contains the backend host, port, access list and grace period you
   configured above.

2. Put it in place and reload:

   ```bash
   sudo cp default.vcl /etc/varnish/default.vcl
   sudo varnishd -C -f /etc/varnish/default.vcl > /dev/null && echo "VCL compiles"
   sudo systemctl reload varnish
   ```

   The `-C` step compiles without applying — always run it first, since a bad VCL will stop Varnish
   from starting.

3. **Purge everything once**, so nothing cached under an older configuration is left behind.

A ready-made copy also ships at `vcls/6.0.vcl` for reference, but prefer the exported file: that one
carries your own settings.

---

## 🔬 Verify it is working

```bash
# 1. Is the response cacheable and tagged?
curl -sI https://your-store.test/ | grep -i 'x-bagisto\|cache-control'
#   X-Bagisto-Tags: bagisto-index,bagisto-index-default-en-USD
#   Cache-Control: public, s-maxage=604800, max-age=0, must-revalidate

# 2. Is Varnish serving it? Ask twice — the second should be a HIT.
curl -sI https://your-store.test/ | grep -i x-bagisto-cache-debug
#   X-Bagisto-Cache-Debug: MISS   →   HIT

# 3. Does purging reach Varnish? Change a product, then re-request:
#   the page should go back to MISS.
```

`X-Bagisto-Cache-Debug` reports `HIT`, `MISS` or `UNCACHEABLE`. If you never see `HIT`, Varnish is
not in the request path. If you see `HIT` but it never returns to `MISS` after an edit, your
**Varnish Host URL** is wrong.

---

## 🛡 Automatic cache purging

Purging happens on its own when:

* A **product** is created, updated or deleted — its own page, its category pages and the home page.
* A **category** is created, updated or deleted — its page in every locale, and the home page.
* A **CMS page**, **review**, **order**, **refund** or **URL rewrite** changes.
* A **section** is published, created or deleted under **Appearance**. Footer and services sections
  are drawn by the sitewide layout, so they drop the whole cache; any other section drops the home
  page. Editing, reordering and toggling a section are held as drafts and purge nothing until you
  press **Publish**.
* A **channel** is updated, which includes activating a different theme.
* Any **configuration** is saved.

A purge clears the page in every channel, locale and currency it was cached under, so it does not
matter which locale the admin happened to be in when saving.

To purge from your own code:

```php
use Webkul\Varnish\Facades\VarnishCache;

VarnishCache::forget('/some-path');       // one page, every variant
VarnishCache::forget(['/a', '/b']);       // several
VarnishCache::flush();                    // everything
```

Or from the command line:

```bash
php artisan varnish:flush            # requires varnishadm on the same host
```

> **Upgrading from an older version:** a cached page carries the tags it will later be purged by, so
> pages already sitting in Varnish still hold the old tag format. Run **Purge Everything** once
> after deploying.

---

## 🛠 Manual cache management

**Admin → Configuration → Cache Management → Varnish → Purge Cache**

* **Purge by URLs** — one or more full URLs or paths, separated by commas or newlines.
* **Purge Everything** — drops every cached object, including static assets and ESI fragments that
  Bagisto never tagged.

---

## 🎨 Theme integration

Personalised fragments must be pulled out of the cached page. Declare each one in
`config/varnish.php`:

```php
return [
    'esi' => [
        'views' => [
            'customer-desktop-dropdown' => 'varnish::shop.components.layouts.header.desktop.customer-dropdown',
        ],
    ],
];
```

* **Key** → the identifier used in the ESI or AJAX call
* **Path** → the Blade view to render

Then include it one of two ways:

### ESI include

```blade
<esi:include src="/esi?tag=customer-desktop-dropdown" />
```

Assembled by Varnish itself, so it is present in the first paint. A slow backend will hold up the
whole page.

### AJAX dynamic view — recommended

```blade
<x-varnish::dynamic-view view="customer-desktop-dropdown" />
```

Fetched after the page renders. Better LCP/FCP, and the right choice for dropdowns, modals and menus
that are not needed immediately.

---

## 🗂 Cache-Control headers

Routes that **should** be cached carry:

```
Cache-Control: public, s-maxage=604800, max-age=0, must-revalidate
```

The lifetime goes to `s-maxage`, which only shared caches such as Varnish honour, while browsers are
asked to revalidate. This matters: a purge reaches Varnish alone, so giving the same lifetime to
`max-age` would leave every visitor who had already loaded the page holding a private copy for a week
that nothing could invalidate.

Routes that must **not** be cached should send:

```
Cache-Control: no-cache, no-store, must-revalidate
```

Attach the middleware to any route you want cached and tagged:

```php
Route::get('/', [HomeController::class, 'index'])
    ->name('shop.home.index')
    ->middleware('cache.response');
```

The lifetime is a middleware argument in minutes — `cache.response:1440` for a day. It defaults to
7 days.

---

## 🧯 Troubleshooting

| Symptom | Cause | Fix |
|---|---|---|
| Changes never appear on the storefront | Purges are not reaching Varnish | Check **Varnish Host URL** points at Varnish's port, not the backend's or nginx's. A purge sent to nginx returns `301` and is silently lost. |
| `X-Bagisto-Cache-Debug` never appears | Varnish is not in the request path | Check your web server proxies to Varnish. |
| Always `MISS`, never `HIT` | Response is not cacheable | Look for `Cache-Control: private`/`no-store`, or a `Set-Cookie` on the response. |
| Purge reports success, page still stale | The cached object predates an upgrade | Run **Purge Everything** once. |
| Page updates for a new visitor but not for you | Your browser cached it | Hard-reload. Confirm the response sends `max-age=0`. |
| Exported VCL will not compile | Backend host contains a scheme or port | Put the host alone in **Backend Host URL**; the port has its own field. |
| `405 Method not allowed` on purge | The purging host is not in the ACL | Add it to **Access List** and re-export the VCL. |
| Settings vanished after a reinstall | `bagisto:install` wipes the database | Seed the `core_config` values instead of entering them by hand. |

---

## 🚀 Best practices

* Use **ESI** for small, critical personalised blocks (login status, cart count).
* Use **AJAX dynamic views** for everything else, to protect Core Web Vitals.
* Never cache `/customer` or `/checkout` — the shipped VCL already bypasses both.
* Compile a VCL with `varnishd -C` before reloading it.
* Test on staging first, and watch **LCP**, **FCP** and **TTFB** after enabling Varnish.
