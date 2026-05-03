# 95th Bomb Group Heritage — Red Feather Club Website

A static flat-file website for the 95th Bomb Group Heritage Association.
Built with vanilla HTML, CSS and JavaScript — no build step required.
Deployable to Amazon S3, Cloudflare Pages, Netlify, GitHub Pages, or any static host.

---

## Project Structure

```
rfc-www/
├── index.html              # Homepage
├── css/
│   └── style.css           # All styles — design system, components, layout
├── js/
│   └── main.js             # Navigation, tabs, lightbox, scroll animations
├── images/                 # ← ADD YOUR PHOTOS HERE
│   ├── gallery/            # Gallery photos
│   ├── history/            # Historical photographs
│   ├── museum/             # Museum interior/exterior
│   └── events/             # Event photographs
└── pages/
    ├── history.html        # Our Past — history & timeline
    ├── museum.html         # The Museum
    ├── events.html         # Forthcoming Events
    ├── gallery.html        # Photo Gallery
    ├── contact.html        # Contact & Find Us
    └── support.html        # Support Us
```

---

## Adding Photos

### To the Homepage Hero
Edit `index.html` and find the `.hero__photos` section. Replace each placeholder div:
```html
<!-- Before -->
<div class="hero__photo"><div class="hero__photo-placeholder">Photo</div></div>

<!-- After -->
<div class="hero__photo"><img src="images/hero-1.jpg" alt="The Red Feather Club"></div>
```
Recommended: 3 landscape photos, at least 1200px wide.

### To the Homepage Gallery Mosaic
In `index.html`, find `.gallery-mosaic`. Replace each `.gallery-cell__placeholder` div:
```html
<!-- Before -->
<div class="gallery-cell" data-lightbox="">
  <div class="gallery-cell__placeholder">📷</div>
</div>

<!-- After -->
<div class="gallery-cell" data-lightbox="../images/gallery/photo-full.jpg">
  <img src="images/gallery/photo-thumb.jpg" alt="Description of photo">
</div>
```

### To the Gallery Page
In `pages/gallery.html`, replace `.photo-placeholder` divs with `<img>` tags:
```html
<!-- Before -->
<div class="photo-item" data-lightbox="">
  <div class="photo-placeholder">...</div>
  <span class="photo-caption">Caption</span>
</div>

<!-- After -->
<div class="photo-item" data-lightbox="../images/gallery/photo-full.jpg">
  <img src="../images/gallery/photo.jpg" alt="Description">
  <span class="photo-caption">Your caption here</span>
</div>
```

### Image Tips
- Use **WebP** format for best performance (convert with Squoosh.app — free)
- Gallery thumbnails: max 800px wide
- Full-size (lightbox) images: max 1600px wide
- Keep file sizes under 300KB per image for fast loading on mobile

---

## Customisation

### Updating Events
Edit `pages/events.html` and `index.html`. Each event follows this pattern:
```html
<div class="event-row">
  <div class="event-badge">
    <span class="event-badge__day">26</span>
    <span class="event-badge__month">Jul</span>
  </div>
  <div class="event-details">
    <span class="event-details__tag tag--open">Open Day</span>
    <div class="event-details__name">Museum Open Day</div>
    <div class="event-details__time">10am – 4pm · Free Entry</div>
  </div>
</div>
```
Tag classes: `tag--open` (green), `tag--evening` (red), `tag--special` (gold)

### Updating Social Feed Posts
The Facebook and Instagram cards in `index.html` are static HTML.
To update them, edit the `.feed-card` blocks in the "Latest News" section.

For a **live feed** without a server:
- Use the **"Live Feed"** tab — paste in the official Facebook Page Plugin embed code and/or a LightWidget/SnapWidget Instagram embed
- Sign up free at [lightwidget.com](https://lightwidget.com) for a hosted Instagram feed widget

### Colours & Fonts
All colours are CSS variables in `css/style.css`:
```css
The palette is now rooted in authentic 1943 USAAF colours:

--olive-drab (#4b5320) — Olive Drab No. 41, the exact colour used on B-17s, jeeps, and equipment. This is now the dominant structural colour — nav, hero, footers, section bands, and CTAs all carry this tone.
--od-dark (#2c3320) — The deep shadow version, used for the navbar, page heroes, and footer backgrounds.
--od-light (#d4d9b8) — A pale, desaturated OD tint for card hover states and the social feed panel.
--insignia-yel (#b8960c) — USAAF Identification Yellow, the period-correct accent used on training markings and national insignia borders. Replaces the warm gold.
--insignia-red (#8b1a1a) — National Insignia Red, the darker period-correct red used on the star-and-bar roundel.
--khaki / --khaki-pale — Khaki No. 26, the uniform colour — warm but now unmistakably in the OD family.
--sand (#c8bc94) — Used for secondary text on dark grounds, echoing the desert sand tones of the era.
```

### Logo / Emblem
Replace the placeholder emblem in the nav:
```html
<!-- Find this in the nav: -->
<div class="nav__emblem-placeholder">95<br>BG</div>

<!-- Replace with: -->
<img src="images/logo.png" alt="95th Bomb Group Heritage Association logo">
```

---

## Contact Form (S3 / Static Hosting)

The contact form uses [Formspree](https://formspree.io) — free for up to 50 submissions/month.

1. Sign up at [formspree.io](https://formspree.io)
2. Create a new form and copy your form ID
3. In `pages/contact.html`, update the form action:
```html
<form action="https://formspree.io/f/YOUR_FORM_ID" method="POST">
```

Alternatives: [Netlify Forms](https://www.netlify.com/products/forms/) (if hosting on Netlify), [Web3Forms](https://web3forms.com) (free).

---

## Deployment

### Amazon S3
1. Create an S3 bucket with **static website hosting** enabled
2. Set the bucket policy to allow public read access
3. Upload all files maintaining the folder structure
4. Set `index.html` as the index document
5. (Optional) Add CloudFront CDN in front for HTTPS and caching
6. (Optional) Set up Route 53 for a custom domain

```bash
# Example using AWS CLI:
aws s3 sync . s3://your-bucket-name --delete --exclude ".git/*" --exclude "README.md"
```

### Cloudflare Pages (Recommended — free HTTPS, global CDN)
1. Push this folder to a GitHub/GitLab repository
2. Go to [pages.cloudflare.com](https://pages.cloudflare.com)
3. Connect your repo — no build command needed, output directory is `/`
4. Done — automatic deploys on every push

### Netlify (Also free, easy)
1. Drag and drop the `rfc-www` folder onto [app.netlify.com](https://app.netlify.com)
2. Done — you'll get a live HTTPS URL instantly
3. Connect to GitHub for automatic deploys

### GitHub Pages
1. Push to a GitHub repo
2. Go to Settings → Pages → Source: main branch / root
3. Your site will be live at `https://yourusername.github.io/repo-name`

---

## Google Maps Embed

In `pages/contact.html`, replace the placeholder iframe with a real embed:
1. Go to [maps.google.com](https://maps.google.com)
2. Search for the address: *Horham Road, Denham, Eye, IP21 5DG*
3. Click **Share** → **Embed a map** → Copy the iframe code
4. Replace the existing `<iframe>` in contact.html

---

## Facebook Page Plugin

In `index.html`, in the "Live Feed" tab panel, replace the placeholder with:
```html
<div class="fb-page"
  data-href="https://www.facebook.com/people/95th-Bomb-Group-Heritage/61554777124268/"
  data-tabs="timeline"
  data-width="500"
  data-height="600"
  data-small-header="true"
  data-adapt-container-width="true"
  data-hide-cover="false"
  data-show-facepile="false">
</div>
```
And add this before `</body>`:
```html
<div id="fb-root"></div>
<script async defer crossorigin="anonymous"
  src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v18.0">
</script>
```

---

## Browser Support
Chrome, Firefox, Safari, Edge (all modern versions). No polyfills required.

## Performance Notes
- All CSS and JS is loaded from local files — no external dependencies at runtime
- Google Fonts are loaded via a single `@import` — can be replaced with self-hosted fonts for offline use
- Add `loading="lazy"` to all `<img>` tags for better mobile performance

---

*Built for the 95th Bomb Group Heritage Association · Registered Charity No. 1119769*
