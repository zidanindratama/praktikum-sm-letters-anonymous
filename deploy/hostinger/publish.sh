#!/usr/bin/env bash

set -euo pipefail

: "${APP_PATH:?APP_PATH is required}"
: "${PUBLIC_PATH:?PUBLIC_PATH is required}"

template_path="$APP_PATH/deploy/hostinger/public-index.php"

if [[ ! -f "$template_path" ]]; then
  echo "Template index.php tidak ditemukan di $template_path" >&2
  exit 1
fi

mkdir -p "$PUBLIC_PATH" "$PUBLIC_PATH/uploads"

# Copy the public web assets to Hostinger's web root.
cp -a "$APP_PATH/public/." "$PUBLIC_PATH/"

# Rebuild the front controller so it points to the app outside public_html.
sed "s|__CI_APP_PATH__|$APP_PATH|g" "$template_path" > "$PUBLIC_PATH/index.php"
