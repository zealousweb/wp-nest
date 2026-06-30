---
trigger: manual
---

# SEO — SITE_NAME

## WP foundations

`after_setup_theme` must register: `title-tag`, `post-thumbnails`, `html5`. Never hardcode `<title>`. `wp_head()` before `</head>` · `wp_footer()` before `</body>` — required for plugin meta/canonical/OG/schema. Never remove either.

## Semantic HTML

Heading hierarchy + landmarks: `a11y.mdc` · `<article>` for self-contained content.

## Images & links

Images: `wp_get_attachment_image()` · descriptive `alt` · hero: `loading="eager"` + `fetchpriority="high"` · OG image min 1200×630.

Internal links: `get_permalink()`, `home_url()`, `get_term_link()` — never hardcode. External `target="_blank"` → `rel="noopener noreferrer"` · sponsored/UGC → add `rel="nofollow"`.

## SEO plugin (Yoast / RankMath / AIOSEO)

Plugin owns: title, meta, canonical, og:_, twitter:_, JSON-LD. Theme must not duplicate any of these. Check for active plugin via `defined('WPSEO_VERSION')`, `defined('RANK_MATH_VERSION')`, or `class_exists('AIOSEO\Plugin\AIOSEO')` before any fallback output.

## JSON-LD

Output via `wp_head` hook · `wp_json_encode()` all user data · never inline in templates. Common types: `WebSite`+`Organization` · `Article` · `FAQPage` · `BreadcrumbList` · `Product` · `Event`.

## CPTs & taxonomies

CPT: `public=true`, `has_archive`, `rewrite` with `with_front=false`, `show_in_rest=true`. Never `public=false` for content that should rank. Taxonomy: same rewrite + `show_in_rest=true`.

## Crawlability

- Robots: `wp_robots` filter for edge cases (`is_search()` → noindex) · never raw `<meta name="robots">` in templates
- Pagination: `wp_link_pages()` singular · `paginate_links()` archives · never `query_posts()`
- Redirects: `wp_safe_redirect($url, 301)` + `exit` · no chains · bulk via plugin not `functions.php`
- 404: real HTTP 404 · never redirect all 404s to home · `wp_head()`/`wp_footer()` in `404.php`
- Sitemap: don't disable `/wp-sitemap.xml` without alternative

## Never

Hardcode `<title>`, meta, canonical, og:_, twitter:_ in theme · duplicate `<h1>` · fake headings with `<div>`/`<span>` · `query_posts()` · unescaped JSON-LD · redirect chains.
