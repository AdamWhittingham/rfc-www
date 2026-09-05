# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

A static flat-file website for the 95th Bomb Group Heritage Association (Red Feather Club). Built with vanilla HTML, CSS, and JavaScript — no build step, no package manager, no framework. Deployable as-is to S3, Cloudflare Pages, Netlify, or GitHub Pages.

## Development

There is no build/lint/test tooling in this repo — it's plain files served directly. To preview locally, serve the directory root with any static file server (e.g. `python3 -m http.server`) and open `index.html` or `pages/*.html`. There are no automated tests; verify changes by loading the affected page(s) in a browser.

## Architecture

- `index.html` — homepage; also hosts the hero photos, homepage gallery mosaic, event list preview, and the "Latest News" social feed tabs.
- `pages/*.html` — one file per section (`history`, `museum`, `events`, `gallery`, `contact`, `support`). Each page duplicates the nav/footer markup found in `index.html` rather than including a shared partial — there is no templating layer, so structural nav/footer changes must be applied by hand across every HTML file.
- `css/style.css` — single stylesheet for the whole site (design tokens, layout, and components together). Colour palette is defined as CSS custom properties at the top (`--olive-drab`, `--od-dark`, `--od-light`, `--insignia-yel`, `--insignia-red`, `--khaki`, `--sand`, etc.), deliberately matched to authentic 1943 USAAF colours — preserve this palette's intent when touching styles.
- `js/main.js` — single IIFE handling all interactivity site-wide: mobile nav burger toggle, active-nav-link highlighting (by comparing `location.pathname` to link `href`), the social feed tab switcher (`.social-tab` / `.social-tab-panel`, keyed by `data-tab`/`data-panel`), a lightbox (`[data-lightbox]` elements, `#lightbox`/`#lightbox-img`), an IntersectionObserver-driven fade-up animation for `.observe-fade` elements, and smooth-scroll for in-page anchors. All pages load this same script; new interactive behavior should be added here rather than as inline/per-page scripts.
- `images/` — asset directory, organized into `gallery/`, `history/`, `museum/`, `events/` subfolders per the site sections that consume them.

## Content Patterns

These recurring HTML patterns are used across pages — follow them when adding content rather than inventing new markup shapes:

- **Lightbox images**: wrap in a container with `data-lightbox="path/to/full-image.jpg"`, containing an `<img>` (thumbnail) — the JS reads the `data-lightbox` attribute (falling back to the nested `<img>` src).
- **Event rows** (used in `index.html` and `pages/events.html`): `.event-row` > `.event-badge` (day/month) + `.event-details` (with a `.event-details__tag` using `tag--open` / `tag--evening` / `tag--special` modifiers for colour-coding).
- **Social feed cards**: static `.feed-card` blocks inside `.social-tab-panel` sections in `index.html`; there is no live integration by default (a Facebook Page Plugin / LightWidget embed can be substituted per the README).
- **Contact form**: posts to Formspree (`pages/contact.html`); no server-side code in this repo.

Because there's no shared header/footer include, any change to the nav or footer needs to be replicated across `index.html` and all files in `pages/`.
