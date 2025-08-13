# Changelog

This changelog consists of the bug & security fixes and new features included in the releases listed below.

## **v2.0.0 (11th Aug, 2025) ** -

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

## **v1.0.0 (27th of April, 2022)** - *Release*

- First release.
