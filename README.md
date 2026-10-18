# dancingspoon-wp-theme

The Dancing Spoon WordPress theme (`dancingspoon`). Deployed by GitHub Actions: every
merge to `main` builds `dancingspoon-<version>.zip`, cuts a GitHub Release when the
`Version:` header in `style.css` is new, and installs + activates the theme on the dev
site; `deploy-prod` (Run workflow, release tag) deploys a release to dancingspoon.net
after the `prod` environment's reviewers approve.

- `Version:` in `style.css` is the only version knob — bump it to release.
- Christmas look (snow, Santa hat on the mark, greeting bar, evergreen footer):
  Appearance → Customize → Dancing Spoon seasonal. On by default; the first switch turns all of it off.
  The recipes in the "Festive recipes" category (or the one picked there) turn the top card into a slider.
- How it works, the network path and the first-time setup:
  https://wordpress-plugin-delivery-1c5177.pages.ops.utr.utrzone.local/latest/

## Brand assets

`assets/brand/` holds the logo for social profiles (not shipped in the theme zip): the mark as
self-contained SVG (fonts embedded) in plum and cream, square avatars 1024/512 px on cream and on plum,
transparent PNGs of the mark in both colours, and 512 px GIFs of the spoon spinning (plum on cream,
cream on plum). Source of truth is `assets/images/logo.svg`.
