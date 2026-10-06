@AGENTS.md

## Claude Code

Load the Bagisto skills with the Skill tool before writing code rather than after —
`bagisto-coding-standards` for any PHP or Blade, and `bagisto-extension-setup` before wiring this
clone into an application. Where a Bagisto skill and the conventions above disagree, the ones above
win: they are this package's own, and a skill describes core.

Two habits this package in particular rewards:

- **Read core's current view before touching an override or a fragment.** The files under
  `publishables/` and `src/Resources/views/shop/components/layouts/header/` are copies of core
  markup; a merge that looks clean can still revert core's newer header or break a slot.
- **Check the page in a browser, not only the compiler.** Unbalanced Blade slots here surface as a
  Vue console error on a rendered page, and `php artisan view:cache` reports success anyway.
