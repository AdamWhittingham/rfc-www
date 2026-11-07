=== Red Feather Club ===

Contributors: 95th Bomb Group Heritage Association
Requires at least: 6.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Tags: one-column, two-columns, custom-colors, custom-logo, custom-menu, featured-images, block-patterns, editor-style, full-width-template, translation-ready

The 95th Bomb Group Heritage Association house style, packaged as a WordPress theme.

== Description ==

Red Feather Club is a classic WordPress theme carrying the design system of the
95th Bomb Group Heritage Association site at Horham, Suffolk: a 1943 USAAF
palette (Olive Drab No.41, Identification Yellow, National Insignia Red, Khaki
No.26), Playfair Display / Libre Baskerville / Barlow Condensed typography, a
full-bleed hero, and the event, gallery, timeline and news components the
association uses.

Features:

* Full-height homepage hero with kicker, statistics and call-to-action buttons,
  all editable in the Customizer.
* Events custom post type with date, time and colour-coded tag, rendered as the
  site's .event-row and .event-item components.
* Gallery Photos custom post type with categories, a filterable masonry grid and
  a lightbox that also picks up core Gallery and Image blocks.
* Eleven block patterns (intro cards, visit cards, CTA band, statistics,
  timeline, feature list, support cards, pull quote, gallery mosaic, sidebar
  card, section header) under the "Red Feather Club" category.
* theme.json palette, font families and sizes so the block editor matches the
  front end, plus a matching editor stylesheet.
* Four menu locations (primary plus three footer columns) and two widget areas.
* Starter content: the full page structure and menus on a fresh install.

== Installation ==

1. In wp-admin, go to Appearance → Themes → Add New → Upload Theme.
2. Choose red-feather-club.zip and click Install Now, then Activate.
3. Go to Appearance → Customize → Red Feather Club to set the hero image,
   headline, statistics, announcement strip, footer details and social links.
4. Appearance → Menus: assign a menu to "Primary Navigation". To style an item
   as the red button (as "Support Us" is on the association's site), open Screen
   Options, tick "CSS Classes", and add the class nav__cta to that item.
5. Settings → Permalinks → Save Changes, so the /events/ and /gallery/ archives
   resolve.
6. If you use a page (rather than the archive) for events or photographs, set
   its template under Page → Settings → Template to "Events Programme" or
   "Photo Gallery".

== Frequently Asked Questions ==

= Where do the events on the homepage come from? =

From the Events post type. Each event has a date, a "time / entry line" and a
tag (Open Day, Evening Event or Special Event) in the Event Details box. The
homepage shows the next five; the Events archive and the "Events Programme"
page template show the full programme grouped by year.

= How do I build a page out of the site's components? =

Add a block pattern: in the editor, open the inserter, choose Patterns, then the
"Red Feather Club" category. Each pattern is plain HTML using the theme's
classes, so you can edit the copy directly.

= Can I change the colours? =

Yes. The palette is defined once as CSS custom properties at the top of
style.css and mirrored in theme.json. Change both to keep the editor in step.

== Changelog ==

= 1.0.0 =
* Initial release — port of the flat-file rfc-www design system.

== Copyright ==

Red Feather Club WordPress Theme, (C) 2026 95th Bomb Group Heritage Association.
Red Feather Club is distributed under the terms of the GNU GPL v2 or later.

Fonts loaded from Google Fonts (Playfair Display, Libre Baskerville, Barlow
Condensed) are licensed under the SIL Open Font License, 1.1.
