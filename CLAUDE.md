# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

The website for the 95th Bomb Group Heritage Association (Red Feather Club), generated with Hugo (0.146+, using the flat `layouts/` structure: `baseof.html`, `home.html`, `_partials/`). Vanilla CSS and JavaScript, no package manager, no JS framework. `hugo` outputs a static site to `public/` for S3, Cloudflare Pages, Netlify, or GitHub Pages.

## Development

Use the `Makefile`:

- `make serve` — `hugo server` with live reload on port 1313.
- `make build` — `hugo --minify` into `public/` (git-ignored). Set `RFC_META_TOKEN` to fetch real social posts.
- `make init` — one-time toolchain setup: installs the PHP pinned in `.tool-versions` via asdf (compiles from source, several minutes) after `make deps` installs the Homebrew build prerequisites. asdf is assumed present.
- `make lint` — `php -l` over every file in the WordPress theme. This is the only automated check in the repo; run it after touching any theme PHP.
- `make zip` — rebuild `wordpress-theme/red-feather-club.zip` from the theme source.

There are no automated tests for the site; check that `hugo` builds without errors or warnings, then load the affected page(s) in a browser.

## Architecture

- `hugo.toml` — site config: `uglyURLs` keeps the old `/index.html` and `/pages/*.html` URLs; `[[menus.main]]` drives the nav (active state via `IsMenuCurrent`, `params.cta` for the "Support Us" button); `[params]` holds contact details and social account URLs/IDs (`social.facebook.url` is the 95thBG group for links; `social.facebook.pageID` is the Page the feed reads, since the Graph API can't read Groups); module mounts make the root `css/` and `js/` Hugo assets and `images/` static files.
- `content/` — `_index.md` (home) and `pages/*.md`. These carry front matter only (`title`, `description`, `layout`, `styles`); page markup lives in the layout named by `layout`. Hugo disallows `.html` content files by default, which is why markup isn't in `content/`.
- `layouts/baseof.html` — the shared shell (head, nav, footer, lightbox, `main.js`). Per-page stylesheets are listed in front matter `styles` and linked here. All CSS and `js/main.js` go through `resources.Get | minify | fingerprint` (hashed file names plus SRI `integrity`), so never link them by literal path. `home.html`, `events.html`, `gallery.html`, `history.html`, `museum.html`, `contact.html`, `support.html` each define `main`. Use `relref`/`relURL` for internal links, never hard-coded paths.
- `layouts/_partials/` — `nav.html`, `footer.html` (single copies for every page), `icon.html` (shared inline SVGs), `event-row.html` / `event-item.html` / `event-label.html`, and `social/` (see below).
- `data/` — recurring content as YAML: `events.yaml` (Events page grouped by `groups`; homepage shows the next five dated on/after the build date), `gallery.yaml` (Gallery page and first eight in the homepage mosaic), `social.yaml` (fallback feed posts).
- `css/style.css` — the design system and shared components. Colour palette is defined as CSS custom properties at the top (`--olive-drab`, `--od-dark`, `--od-light`, `--insignia-yel`, `--insignia-red`, `--khaki`, `--sand`, etc.), deliberately matched to authentic 1943 USAAF colours — preserve this palette's intent when touching styles. Text colours come in pairs by background so text stays at 4.5:1 contrast or better: `--khaki` (light grounds) / `--khaki-light` (dark grounds) for muted text, and `--gold` (dark grounds) / `--gold-deep` (light grounds) for gold labels. Small text has a floor of 12px for Barlow Condensed labels and 0.85rem for body copy. `css/pages/*.css` hold styles used by one page only.
- `js/main.js` — single IIFE handling all interactivity site-wide: mobile nav burger toggle, the social feed tab switcher (`.social-tab` / `.social-tab-panel`, keyed by `data-tab`/`data-panel`), the gallery filter (`.filter-btn` / `.photo-item[data-category]`), a lightbox (`[data-lightbox]` elements, `#lightbox`/`#lightbox-img`), an IntersectionObserver-driven fade-up animation for `.observe-fade` elements, and smooth-scroll for in-page anchors. New interactive behaviour should be added here rather than as inline/per-page scripts.
- `images/` — asset directory, organised into `gallery/`, `history/`, `museum/`, `events/` subfolders per the site sections that consume them.

### Social feeds

`layouts/_partials/social/` fetches Facebook Page posts and Instagram media from the Graph API at build time with `resources.GetRemote`:

- `graph.html` — one Graph API GET (`params.social.graphURL` + path). The token comes from `RFC_META_TOKEN` (allowed by `security.funcs.getenv`) and is sent as an `Authorization: Bearer` header so it never appears in URLs or logs. Failures are wrapped in `try` and become a `warnf`, never a build failure.
- `facebook.html` / `instagram.html` — return a list of `{date, text, image, link}` cards, or `data/social.yaml` when the token is missing or the call fails. Instagram's user ID is looked up from the Page's `instagram_business_account` unless `params.social.instagram.userID` is set.
- `image.html` — downloads each post image, crops to 800×600 WebP and publishes it under `images/social/`, because Meta's CDN URLs expire.
- `card.html` — the `.feed-card` markup.

Remote responses are cached for an hour (`[caches.getresource]`); use `hugo --ignoreCache` to force a refetch. Feeds only update when the site is rebuilt, so production needs a scheduled rebuild.

## WordPress Theme (`wordpress-theme/`)

`wordpress-theme/red-feather-club/` is a classic WordPress theme carrying the
same design system, plus `red-feather-club.zip` (the uploadable package —
regenerate it after any theme edit, see README). Notes for working on it:

- The theme's `style.css` is `css/style.css` verbatim (minus the font `@import`)
  followed by (a) the component styles that live in `css/pages/*.css` in the
  Hugo site and (b) a WordPress section (editor content typography,
  alignments, comments, widgets, admin-bar offset). Palette changes must be made
  in the root `css/style.css`, then re-mirrored here and in `theme.json`.
- `assets/css/editor-style.css` is generated from the theme's `style.css` —
  tokens, section furniture and components only, no site chrome.
- Recurring content is data, not markup: `rfc_event` (Events, with
  `_rfc_event_date` / `_rfc_event_time` / `_rfc_event_tag` meta) and `rfc_photo`
  (Gallery Photos + `rfc_photo_category`). Markup for both lives in
  `inc/template-tags.php` (`rfc_event_row()`, `rfc_event_item()`,
  `rfc_photo_item()`), not in templates.
- Components are exposed to editors as block patterns in `patterns/*.php`, each
  an HTML block using the theme's classes.
- `make lint` syntax-checks the theme, but nothing in this repo can *run* WordPress; behavioural changes need checking on a real install.

## Content Patterns

These recurring HTML patterns are used across pages — follow them when adding content rather than inventing new markup shapes:

- **Lightbox images**: a container with `data-lightbox="path/to/full-image.jpg"`, containing an `<img>` (thumbnail) — the JS reads the `data-lightbox` attribute (falling back to the nested `<img>` src). Gallery entries in `data/gallery.yaml` render this for you.
- **Event rows**: rendered from `data/events.yaml` by `event-row.html` (Events page: `.event-row` > `.event-badge` + `.event-details` with a `.event-details__tag` using `tag--open` / `tag--evening` / `tag--special`) and `event-item.html` (homepage list). Add events to the data file, not as markup.
- **Social feed cards**: `.feed-card` blocks rendered by `_partials/social/card.html` inside `.social-tab-panel` sections in `layouts/home.html`.
- **Contact**: `layouts/contact.html` links to `params.email` with `mailto:`; there is no server-side code in this repo.
