#!/usr/bin/env bash
# Optional maintenance script. Set ADMIN_API_KEY securely in your shell before use.
# Do not commit real API keys to this file or to GitHub.
set -euo pipefail
: "${ADMIN_API_KEY:?Set ADMIN_API_KEY in your environment first}"
BASE_URL="${VISIONPATH_BASE_URL:-https://your-domain.example}"
curl --fail --silent --show-error \
  --get --data-urlencode "key=${ADMIN_API_KEY}" \
  "${BASE_URL}/api/update_careers.php"
