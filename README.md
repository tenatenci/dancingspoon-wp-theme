# dancingspoon-wp-theme

The Dancing Spoon WordPress theme (`dancingspoon`). Deployed by GitHub Actions: every
merge to `main` builds `dancingspoon-<version>.zip`, cuts a GitHub Release when the
`Version:` header in `style.css` is new, and installs + activates the theme on the dev
site; `deploy-prod` (Run workflow, release tag) deploys a release to dancingspoon.net
after the `prod` environment's reviewers approve.

- `Version:` in `style.css` is the only version knob — bump it to release.
- How it works, the network path and the first-time setup:
  https://wordpress-plugin-delivery-1c5177.pages.ops.utr.utrzone.local/latest/
