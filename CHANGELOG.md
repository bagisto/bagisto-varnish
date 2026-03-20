# Changelog

This changelog consists of the bug & security fixes and new features included in the releases listed below.

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
