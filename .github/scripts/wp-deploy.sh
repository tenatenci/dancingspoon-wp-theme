#!/usr/bin/env bash
# Install a plugin or theme zip into the running WordPress pod and activate it.
# Runs on the GitHub runner with KUBECONFIG set; needs the wp-plugin-deploy role
# (pods get/list, pods/exec create, deployments get) in the WordPress namespace.
#
# env: WP_KIND (plugin|theme) WP_SLUG WP_VERSION WP_ZIP KUBE_NAMESPACE WP_RELEASE
#      SITE_URL (smoke test) SITE_RESOLVE (optional curl --resolve for internal hosts)
set -euo pipefail

: "${WP_KIND:?}" "${WP_SLUG:?}" "${WP_VERSION:?}" "${WP_ZIP:?}" "${KUBE_NAMESPACE:?}" "${WP_RELEASE:?}" "${SITE_URL:?}"
K="kubectl -n $KUBE_NAMESPACE"
WP_PATH=/var/www/html
# WP-CLI runs as www-data so the files it writes stay editable by WordPress itself.
WP="runuser -u www-data -- env HOME=/tmp WP_CLI_CACHE_DIR=/tmp/wp-cli-cache php /tmp/wp-cli.phar --path=$WP_PATH"

pod=$($K get pod -l "app.kubernetes.io/instance=$WP_RELEASE,app.kubernetes.io/component=wordpress" \
      -o jsonpath='{range .items[?(@.status.phase=="Running")]}{.metadata.name}{"\n"}{end}' | head -1)
[ -n "$pod" ] || { echo "no running WordPress pod for release $WP_RELEASE in $KUBE_NAMESPACE"; exit 1; }
echo "pod: $pod"

$K cp wp-cli.phar "$pod:/tmp/wp-cli.phar" -c app
$K cp "$WP_ZIP" "$pod:/tmp/$WP_SLUG.zip" -c app
# kubectl cp keeps the local file mode; make both readable for www-data whatever the runner's umask was.
$K exec "$pod" -c app -- chmod 0644 /tmp/wp-cli.phar "/tmp/$WP_SLUG.zip"

$K exec "$pod" -c app -- sh -c "$WP core is-installed" || { echo "WordPress is not installed on this site"; exit 1; }

before=$($K exec "$pod" -c app -- sh -c "$WP $WP_KIND get $WP_SLUG --field=version" 2>/dev/null || echo "none")
echo "$WP_KIND $WP_SLUG: $before -> $WP_VERSION"
# the theme to fall back to if the new one breaks the site (a plugin is simply deactivated)
prev_theme=""
if [ "$WP_KIND" = theme ]; then
  prev_theme=$($K exec "$pod" -c app -- sh -c "$WP theme list --status=active --field=name" 2>/dev/null | head -1)
  echo "active theme before: ${prev_theme:-none}"
fi

$K exec "$pod" -c app -- sh -c "$WP $WP_KIND install /tmp/$WP_SLUG.zip --force --activate && rm -f /tmp/$WP_SLUG.zip"

after=$($K exec "$pod" -c app -- sh -c "$WP $WP_KIND get $WP_SLUG --field=version")
[ "$after" = "$WP_VERSION" ] || { echo "installed version $after != $WP_VERSION"; exit 1; }

# Smoke: the site must still answer 200 with the package active. A fatal takes it down,
# so a failed smoke deactivates the plugin or re-activates the theme that was live before.
resolve=(); [ -n "${SITE_RESOLVE:-}" ] && resolve=(--resolve "$SITE_RESOLVE")
if ! curl -fsS -k -o /dev/null --max-time 20 --retry 3 --retry-delay 3 "${resolve[@]}" "$SITE_URL/?deploy=$WP_VERSION"; then
  echo "smoke test failed on $SITE_URL - rolling back"
  if [ "$WP_KIND" = plugin ]; then
    $K exec "$pod" -c app -- sh -c "$WP plugin deactivate $WP_SLUG" || true
  elif [ -n "$prev_theme" ] && [ "$prev_theme" != "$WP_SLUG" ]; then
    $K exec "$pod" -c app -- sh -c "$WP theme activate $prev_theme" || true
  fi
  exit 1
fi
echo "deployed $WP_KIND $WP_SLUG $WP_VERSION, site answers 200"
