# Reforge CMS
A lightweight CMS roughly reminiscent of Wordpress APIs. Reforge offers intelligent caching through APCu, redis, memcache, wincache, xcache, or even filesystem to load database results and pages lightning fast. Page responses often clock in at around 50ms. This all happens behind the scene and manages those caches automatically, so you're never working with out of date data. When you alter data the relevant caches update to reflect that.

## It supports:
- An MVC framework capable of dynamically maintaining database schema without any overhead.
- Cascading and recursive custom fields placed on any number of post or page types. 
- Granular and custom permissions through an admin UI. 
- Themes and plugins. 
- Custom post types. 
- Forms with SMTP. 
- Partial and reusable content blocks. 
- A media interface that supports everything including SVG and Webp and automatically generates different defined sizes.
- Advanced SEO features including schemas and hinting.
- Built in SCSS automatic SCSS compilation.