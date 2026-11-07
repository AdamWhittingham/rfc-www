# 95th Bomb Group Heritage — Red Feather Club Website

The website for the 95th Bomb Group Heritage Association, built with
[Hugo](https://gohugo.io). Hugo turns the templates, content and data files in
this repo into a plain static site in `public/`, which can be hosted on Amazon
S3, Cloudflare Pages, Netlify, GitHub Pages or any static host.

The homepage's "Latest News" tabs pull the most recent posts from the official
Facebook Page and Instagram account at build time (see
[Facebook & Instagram](#facebook--instagram)).

---

## Development

You need Hugo 0.146 or newer (`brew install hugo`). The WordPress theme needs
PHP to lint, which is managed with [asdf](https://asdf-vm.com) and pinned in
`.tool-versions`.

```bash
make dev      # build, serve and open the site in your browser (live reload)
make serve    # the same, without opening a browser
make build    # build the site into public/
make init     # one-time: install the pinned PHP via asdf (compiles from source)
make lint     # php -l over every file in the theme
make zip      # rebuild wordpress-theme/red-feather-club.zip
make help     # list all targets
```

`make init` runs `make deps` first, which installs the Homebrew libraries the
PHP build needs (already-installed formulae are skipped). To pin a different
PHP line, delete the `php` entry from `.tool-versions` and run
`make init PHP_SERIES=8.4`.

---

## Project Structure

```
rfc-www/
├── hugo.toml               # Site config: menu, social accounts, contact details
├── content/                # One file per page (title, description, which layout)
│   ├── _index.md           # Homepage
│   └── pages/              # history, museum, events, gallery, contact, support
├── layouts/
│   ├── baseof.html         # Page shell: <head>, nav, footer, lightbox, script
│   ├── home.html           # Homepage
│   ├── history.html …      # One layout per page, holding that page's markup
│   └── _partials/          # Nav, footer, event rows, social feed fetchers/cards
├── data/
│   ├── events.yaml         # ← EDIT EVENTS HERE
│   ├── gallery.yaml        # ← ADD GALLERY PHOTOS HERE
│   └── social.yaml         # Fallback posts used when the social APIs aren't available
├── css/
│   ├── style.css           # Design system, shared components, layout
│   └── pages/              # Styles used by a single page
├── js/
│   └── main.js             # Navigation, tabs, gallery filter, lightbox, scroll animations
└── images/                 # ← ADD YOUR PHOTOS HERE (gallery/, history/, museum/, events/)
```

`images/` is published as it is. Stylesheets and `main.js` are minified and
published with a content hash in the file name (such as
`style.min.b83f62….css`), so visitors' browsers always fetch the latest version
after a change. Reference them only through `layouts/baseof.html` and the
`styles` front matter, never by a hard-coded path. The built site keeps the old URLs (`/index.html`, `/pages/events.html` and so on), so existing links still work.

---

## Editing Content

### Events
Edit `data/events.yaml`. Each event looks like this:
```yaml
- date: 2026-07-26
  group: open            # which list on the Events page (open | evening)
  tag: open              # label colour: open (green) | evening (red) | special (gold)
  label: Open Day        # optional; defaults from the tag
  name: Museum Open Day
  time: 10am – 4pm · Free Entry
  desc: Optional longer description shown on the Events page.
```
The Events page lists every event. The homepage shows the next five that are
still in the future **at the time the site was built**, so rebuild the site
regularly (see [Keeping the feeds fresh](#keeping-the-feeds-fresh)).

### Gallery
Put the image in `images/gallery/` and add an entry to `data/gallery.yaml`:
```yaml
- src: /images/gallery/exterior.jpg
  full: /images/gallery/exterior-large.jpg   # optional, for the lightbox
  alt: The Red Feather Club from the road
  caption: The Red Feather Club — Horham
  category: site                             # site | museum | events | historical
```
The first eight photos also fill the homepage mosaic. Entries without `src`
show a placeholder.

### Page text
Each page's markup lives in its layout (`layouts/history.html`,
`layouts/museum.html` and so on), and its title and meta description in the
matching file under `content/pages/`. The nav is the `[[menus.main]]` list in
`hugo.toml`; the footer is `layouts/_partials/footer.html`. Both are shared by
every page.

### Image Tips
- Use **WebP** format for best performance (convert with Squoosh.app — free)
- Gallery thumbnails: max 800px wide
- Full-size (lightbox) images: max 1600px wide
- Keep file sizes under 300KB per image for fast loading on mobile

---

## Facebook & Instagram

The "Latest News" tabs on the homepage show the three latest posts from the
Facebook Page and the Instagram account. They're fetched from Meta's Graph API
**when the site is built**, so visitors' browsers never talk to Meta and the
images are served from this site.

Facebook links and buttons across the site go to the
[95thBG group](https://www.facebook.com/groups/95thBG/) (`facebook.url` in
`hugo.toml`), but the feed is read from the association's Facebook **Page**
(`facebook.pageID`), because Meta removed API access to Groups in 2024.

The build looks for a Page access token in the `RFC_META_TOKEN` environment
variable. Without it (or if the API call fails, in which case the build prints a
warning) the tabs fall back to the posts in `data/social.yaml`.

### Creating the Meta token

The build needs a **Page access token** for the 95th Bomb Group Heritage
Facebook Page. Created as below, it doesn't expire, so this is a one-off job
(see [When the token stops working](#when-the-token-stops-working)). It takes
about 20 minutes.

**Before you start**, you need:
- A personal Facebook account with **full control** of the Page (Page
  settings → Page setup → Page access).
- The Instagram account switched to a **Business** or **Creator** account and
  **linked to the Facebook Page**. In the Instagram app go to Settings →
  Account type and tools, then link the Page from the Page's settings under
  Linked accounts. The Instagram tab can't fetch posts without this link.

**1. Register as a Meta developer.** Go to
[developers.facebook.com](https://developers.facebook.com), log in with the
Facebook account that manages the Page, and choose **Get Started**. Meta may ask
you to verify a phone number.

**2. Create an app.** Choose **My Apps → Create App** and call it something like
"Red Feather Club website". When asked what the app is for, choose **Other**,
then the **Business** app type. Meta renames these options from time to time;
pick whichever lets you manage a Page and its Instagram account. Leave the app
in **Development** mode. It never needs to go live or through App Review,
because the only account using it is an admin of both the app and the Page.

**3. Note the app's ID and secret.** Under **App settings → Basic**, copy the
**App ID** and the **App Secret** (choose Show). Treat the secret like a
password.

**4. Generate a short-lived user token.** Open the
[Graph API Explorer](https://developers.facebook.com/tools/explorer/):
1. Under **Meta App**, select your new app.
2. Under **User or Page**, choose **User Token**.
3. Under **Permissions**, add `pages_show_list`, `pages_read_engagement`,
   `instagram_basic` and `business_management`.
4. Choose **Generate Access Token**. In the pop-up, select the 95th Bomb Group
   Heritage Page and its Instagram account, then approve.
5. Copy the token from the **Access Token** box. It lasts about an hour.

These permissions only allow reading: the token can't post to the Page or
Instagram.

**5. Exchange it for a long-lived user token.** Run this in a terminal,
replacing the three values in capitals. It returns an `access_token` that lasts
about 60 days.
```bash
curl -G https://graph.facebook.com/v23.0/oauth/access_token \
  -d grant_type=fb_exchange_token \
  -d client_id=APP_ID \
  -d client_secret=APP_SECRET \
  -d fb_exchange_token=SHORT_LIVED_TOKEN
```
(The [Access Token Debugger](https://developers.facebook.com/tools/debug/accesstoken/)
does the same thing: paste the token, choose **Debug**, then **Extend Access
Token**.)

**6. Get the Page token.** Ask for the Pages you manage, using the long-lived
token from step 5:
```bash
curl -G https://graph.facebook.com/v23.0/me/accounts \
  -H "Authorization: Bearer LONG_LIVED_USER_TOKEN"
```
Find the entry whose `id` is `61554777124268` (the 95th Bomb Group Heritage
Page). Its `access_token` is the **Page token**. Because it was made from a
long-lived user token, it doesn't expire.

**7. Check it.** Paste the Page token into the
[Access Token Debugger](https://developers.facebook.com/tools/debug/accesstoken/).
It should show **Type: Page**, **Expires: Never**, and the four permissions
from step 4. Then check it can read both accounts:
```bash
export RFC_META_TOKEN=PAGE_TOKEN
curl -G "https://graph.facebook.com/v23.0/61554777124268/posts?fields=message&limit=1" \
  -H "Authorization: Bearer $RFC_META_TOKEN"
curl -G "https://graph.facebook.com/v23.0/61554777124268?fields=instagram_business_account" \
  -H "Authorization: Bearer $RFC_META_TOKEN"
```
The first should return the latest post. The second should return an
`instagram_business_account` with an `id`. If it doesn't, the Instagram account
isn't linked to the Page (see "Before you start").

**8. Store it.** Add the Page token as a secret environment variable called
`RFC_META_TOKEN` wherever the site is built (Cloudflare Pages or Netlify
environment variables, or a CI secret). Never commit it to the repository, and
don't send it by email or chat. The short- and long-lived user tokens from
steps 4 and 5 aren't needed any more and can be discarded.

### When the token stops working

When the token is missing or rejected, the build still succeeds. It prints a
warning starting `social: Graph API request failed` and shows the posts from
`data/social.yaml` instead. The Page token stops working if the person who
created it:
- changes their Facebook password,
- loses full control of the Page,
- removes the app under Facebook Settings → Business integrations, or
- deletes the app.

To fix it, repeat steps 4 to 8 (the app from steps 2 and 3 can be reused). If
the association uses a Meta Business portfolio, a **system user** token created
in Business Settings doesn't depend on any one person's account, so it is a
sturdier option for the long term.

To try it locally:
```bash
RFC_META_TOKEN=... make build
```

Facebook links across the site go to the group (`facebook.url`). The feed reads
from the Page (`facebook.pageID`) because Meta no longer offers API access to
Groups.

The Page ID, the Graph API version and how many posts to show are under
`[params.social]` in `hugo.toml`. The Instagram account ID is looked up from
the Page automatically; set `instagram.userID` to skip that lookup. Graph API
versions are retired roughly two years after release, so bump `graphURL` when
Meta announces the end of the version in use.

### Keeping the feeds fresh
Because posts are fetched at build time, the feeds only change when the site is
rebuilt. Schedule a rebuild, for example once an hour or once a day:
- **Cloudflare Pages / Netlify:** create a deploy hook and call it from a
  scheduled job (a Cloudflare Worker cron trigger, or any cron service).
- **GitHub Actions:** a workflow with `on: schedule` that runs `hugo --minify`
  and deploys `public/`.

---

## Customisation

### Colours & Fonts
All colours are CSS variables in `css/style.css`:
```css
The palette is now rooted in authentic 1943 USAAF colours:

--olive-drab (#42491c) — Olive Drab No. 41, the exact colour used on B-17s, jeeps, and equipment. This is now the dominant structural colour — nav, hero, footers, section bands, and CTAs all carry this tone.
--od-dark (#272d1c) — The deep shadow version, used for the navbar, page heroes, and footer backgrounds.
--od-light (#d4d9b8) — A pale, desaturated OD tint for card hover states and the social feed panel.
--insignia-yel (#d4ac0e) — USAAF Identification Yellow, the period-correct accent used on training markings and national insignia borders. Used on dark backgrounds.
--gold-deep (#6b5707) — A deep version of the same yellow for gold text on light backgrounds, where the bright yellow is too faint to read.
--insignia-red (#8b1a1a) — National Insignia Red, the darker period-correct red used on the star-and-bar roundel.
--khaki (#635b41) / --khaki-pale — Khaki No. 26, the uniform colour — warm but now unmistakably in the OD family. --khaki is body text on light backgrounds.
--khaki-light (#ab9f7e) — A lighter khaki for muted text on dark backgrounds (nav, hero, footer).
--sand (#c8bc94) — Used for secondary text on dark grounds, echoing the desert sand tones of the era.
```

### Logo / Emblem
Replace the placeholder emblem in `layouts/_partials/nav.html`:
```html
<!-- Find this in the nav: -->
<div class="nav__emblem-placeholder">95<br>BG</div>

<!-- Replace with: -->
<img src="{{ "images/logo.png" | relURL }}" alt="95th Bomb Group Heritage Association logo">
```

---

## Contact

The Contact page links to the enquiries address set as `email` in `hugo.toml`.
To use a form service such as [Formspree](https://formspree.io),
[Netlify Forms](https://www.netlify.com/products/forms/) or
[Web3Forms](https://web3forms.com) instead, edit the form in
`layouts/contact.html`.

The map is a Google Maps embed in the same file: on
[maps.google.com](https://maps.google.com) search for the address, choose
**Share → Embed a map**, and replace the `<iframe>`.

---

## Deployment

Every host needs the same two settings: build command `hugo --minify` and
output directory `public`. Set `HUGO_VERSION` to a version of 0.146 or newer
where the host asks for one, and add `RFC_META_TOKEN` as a secret environment
variable for the social feeds.

### Cloudflare Pages (Recommended — free HTTPS, global CDN)
1. Push this repository to GitHub/GitLab
2. In [pages.cloudflare.com](https://pages.cloudflare.com) connect the repo and choose the **Hugo** preset
3. Add `HUGO_VERSION` and `RFC_META_TOKEN` under Settings → Environment variables

### Netlify
1. Connect the repository in [app.netlify.com](https://app.netlify.com)
2. Build command `hugo --minify`, publish directory `public`
3. Add `HUGO_VERSION` and `RFC_META_TOKEN` under Site configuration → Environment variables

### Amazon S3
Build locally or in CI, then upload `public/`:
```bash
RFC_META_TOKEN=... hugo --minify
aws s3 sync public/ s3://your-bucket-name --delete
```
Enable static website hosting with `index.html` as the index document, and
optionally put CloudFront in front for HTTPS.

### GitHub Pages (current)
The site is published to `https://adamwhittingham.github.io/rfc-www/` by
`.github/workflows/deploy.yml` on every push to `master` or `hugo`. Both
branches deploy to the same URL, so **whichever branch was pushed last is
live**. A branch with a `hugo.toml` is built with Hugo, which picks up the
`/rfc-www/` sub-path automatically; other branches publish the flat HTML files
as they are. You can also start a deploy by hand from the Actions tab
(**Deploy to GitHub Pages → Run workflow**, choosing the branch).

One-time setup in the repository settings:
1. **Settings → Pages → Build and deployment → Source:** choose
   **GitHub Actions** (instead of "Deploy from a branch").
2. **Settings → Environments → github-pages → Deployment branches and tags:**
   add `hugo` alongside `master`. Without this, deploys from `hugo` are
   rejected.
3. **Settings → Secrets and variables → Actions → New repository secret:**
   name `RFC_META_TOKEN`, value the Page token from
   [Creating the Meta token](#creating-the-meta-token).

The feeds only refresh when the site is rebuilt. To rebuild on a schedule, add
a `schedule:` trigger to the workflow once the Hugo site is on `master`
(GitHub only runs scheduled workflows from the default branch).

---

## WordPress Theme

The same design system is packaged as an installable WordPress theme in
`wordpress-theme/`.

```
wordpress-theme/
├── red-feather-club/        # the theme source
└── red-feather-club.zip     # ready to upload via Appearance → Themes → Add New
```

Install: **Appearance → Themes → Add New → Upload Theme**, choose
`red-feather-club.zip`, Install Now, Activate. Then
**Appearance → Customize → Red Feather Club** for the hero, announcement strip,
footer details and social links, and **Settings → Permalinks → Save Changes** so
the `/events/` and `/gallery/` archives resolve.

What it maps onto WordPress:

| Flat-file site | WordPress |
| --- | --- |
| `css/style.css` + `css/pages/*.css` | theme `style.css` (one consolidated stylesheet) + `theme.json` palette |
| `js/main.js` | `assets/js/main.js` (plus core Gallery/Image blocks wired to the lightbox) |
| Hero, merch strip, footer text | Customizer → Red Feather Club |
| `layouts/_partials/nav.html` / `footer.html` | `header.php` / `footer.php` and four menu locations |
| `data/events.yaml` | **Events** post type (date, time line, colour tag) |
| `data/gallery.yaml` | **Gallery Photos** post type + categories, filterable grid |
| Intro cards, CTA band, timeline, stat block… | 11 block patterns under the "Red Feather Club" category |

Rebuild the zip after editing the theme:

```bash
cd wordpress-theme && rm -f red-feather-club.zip && zip -rq red-feather-club.zip red-feather-club -x '*.DS_Store'
```

The flat-file site remains the source of truth for the palette: the theme's
`style.css` carries `css/style.css` verbatim (minus the Google Fonts `@import`,
which the theme enqueues properly), with the page-level component styles and the
WordPress-specific rules appended after it.

---

## Browser Support
Chrome, Firefox, Safari, Edge (all modern versions). No polyfills required.

## Performance Notes
- All CSS and JS is loaded from local files — no external dependencies at runtime
- Google Fonts are loaded via a single `@import` — can be replaced with self-hosted fonts for offline use
- Gallery and social images are lazy-loaded; social post images are resized to 800×600 WebP at build time

---

*Built for the 95th Bomb Group Heritage Association · Registered Charity No. 1119769*
