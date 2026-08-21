# Changelog

This changelog consists of the bug & security fixes and new features included in the releases listed below.

## **v2.0.1** - *Unreleased*

### 🐞 Bug Fixes

* Purge requests are sent to the **Varnish Host URL** (`varnish_url`) instead of the **Backend Host URL**, which pointed them at Bagisto itself so no ban ever reached Varnish. Installs that only filled the backend field keep working, as it is still read as a fallback.

* **Purge Everything** now bans every cached object. It banned only objects carrying `X-Bagisto-Tags`, leaving static files, ESI fragments and any route without the `cache.response` middleware in the cache.

* A purge now clears the page in **every channel, locale and currency** it was cached under. Tags were matched against the purging request's own context, which for an admin save is the admin's locale and currency, not the shopper's.

* Purge patterns are anchored on the tag separators, so purging `/shirts` no longer also drops `/shirts-for-men`.

* Appearance changes purge again on Bagisto v2.4. The package listened for the `theme_customization.*` events, which v2.4 replaced with `section.*`, so publishing a section never reached the storefront. Footer and service sections drop the whole cache, other sections drop the home page, and a theme activation drops everything.

* Publishing a theme's sections together now sends one purge instead of one per section.

* The exported VCL compiles. `.host`, `.port` and the grace period were written unquoted, and a scheme or port in the backend URL is now stripped from `.host`.

* **Purge by URLs** accepts the comma separated list its own field documents, rather than treating the whole textarea as a single URL.

* A purge can no longer hold an admin save open when Varnish is unreachable; requests time out after 5 seconds.

* Cached pages are sent as `s-maxage=<ttl>, max-age=0, must-revalidate` rather than handing the same lifetime to `max-age`. Varnish still holds the page for the full lifetime, but a browser no longer keeps a private copy that no purge can reach, which left a visitor who had already loaded a page seeing the old one for up to a week after it was republished.

## **v2.0.0 (20th of March, 2025)** - *Release*

* Compatibility with Bagisto v2.4.

* Moved configuration panel under **Cache Management → Varnish** section.

* Renamed configuration key from `fpc` to `varnish` for consistency.

* Auto-registers `cache_management` parent section for Bagisto v2.4.0 where it is not available in core.

* Fixed route pattern for `shop.product_or_category.index` to match Bagisto v2.4 signature.

* Synced published shop views with Bagisto v2.4 (localStorage category caching, srcset attributes, wishlist variable).

* Fixed all translation keys across blade views and controllers.

## **v1.1.0 (30th of September, 2025)** - *Release*

* Compatibility with the Bagisto v2.3.7.

* Improved coding standards and code quality.

* Fixed the default configuration issues.

## **v1.0.0 (17th of September, 2025)** - *Release*

### ✨ New Features

* Full integration of Varnish Cache with Bagisto for full-page caching.
* Support for **ESI (Edge Side Includes)** to render dynamic blocks at the caching layer.
* AJAX-based dynamic view loading component to improve Core Web Vitals (LCP/FCP).
* Admin panel tools for purging cache by URL or entirely, and exporting VCL configuration.
* Automatic cache purging on product, category, page, order, review, refund, and theme updates.
* Configuration options for Varnish host, backend host, grace period, and access lists.
* Updated Varnish 6.x VCL templates optimized for Bagisto backend.

### 🐛 Fixes & Improvements

* Improved cache purging accuracy and stability.
* Enhanced handling of dynamic views and cache headers for smoother user experience.
