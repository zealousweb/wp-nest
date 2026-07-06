---
trigger: manual
---

# SEO — SITE_NAME

## WordPress

- Enable `title-tag`, `post-thumbnails`, and `html5`.
- Never hardcode `<title>`.
- Always include `wp_head()` and `wp_footer()`.

## Content

- Use semantic HTML and proper heading hierarchy.
- Use `wp_get_attachment_image()` with descriptive `alt`.
- Hero images → `loading="eager"` + `fetchpriority="high"`.
- Use `get_permalink()`, `home_url()`, and `get_term_link()` for links.
- External links with `target="_blank"` → `rel="noopener noreferrer"`.

## SEO Plugins

- Let Yoast, Rank Math, or AIOSEO manage SEO tags.
- Never duplicate title, meta, canonical, Open Graph, Twitter, or JSON-LD.

## JSON-LD

- Output via `wp_head`.
- Encode with `wp_json_encode()`.
- Never inline in templates.

## Crawlability

- Use `wp_robots` for robots rules.
- Use `paginate_links()` / `wp_link_pages()`.
- Use `wp_safe_redirect()` for redirects.
- Keep real 404 pages.
- Don't disable the sitemap without an alternative.

## Never

- Hardcoded SEO tags
- Duplicate `<h1>`
- Fake headings
- `query_posts()`
- Unescaped JSON-LD
- Redirect chains
