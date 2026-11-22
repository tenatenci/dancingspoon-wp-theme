# dancingspoon-wp-theme

The Dancing Spoon WordPress theme (`dancingspoon`). Deployed by GitHub Actions: every
merge to `main` builds `dancingspoon-<version>.zip`, cuts a GitHub Release when the
`Version:` header in `style.css` is new, and installs + activates the theme on the dev
site; `deploy-prod` (Run workflow, release tag) deploys a release to dancingspoon.net
after the `prod` environment's reviewers approve.

- `Version:` in `style.css` is the only version knob — bump it to release.
- Christmas look (snow, Santa hat on the mark, greeting bar, evergreen footer):
  Appearance → Customize → Dancing Spoon seasonal. Off by default; the first checkbox turns all of it on.
  The recipes in the "Festive recipes" category (or the one picked there) turn the top card into a slider.
  Long titles are set smaller in the slider so every slide has the same height ("Shrink long titles", on by default).
- Top card (front page): Appearance → Customize → Dancing Spoon front page. Which recipe it shows, the title size
  (100 / 90 / 80 / 70 %) and the text alignment (title centred — the default —, everything centred, left).
- Social icons (header, footer, contact page, follow block): Appearance → Customize → Dancing Spoon social links.
  Instagram, TikTok, Pinterest and YouTube, each with a Show checkbox and its link; all on with the default links
  out of the box, an empty link falls back to the default.
- How it works, the network path and the first-time setup:
  https://wordpress-plugin-delivery-1c5177.pages.ops.utr.utrzone.local/latest/

## Brand assets

`assets/brand/` holds the logo for social profiles (not shipped in the theme zip): the mark as
self-contained SVG (fonts embedded) in plum and cream, square avatars 1024/512 px on cream and on plum,
transparent PNGs of the mark in both colours, and 512 px GIFs of the spoon spinning (plum on cream,
cream on plum). Source of truth is `assets/images/logo.svg`.
