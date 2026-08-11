#!/bin/bash
# composer run dev の起動時に自動実行され、Macの現在のLAN IPアドレスを検出して
# .env の APP_URL と vite.config.js の hmr.host を書き換えます。
# Wi-Fiの再接続などでIPアドレスが変わっても、手動修正が不要になります。

set -euo pipefail
cd "$(dirname "$0")/.."

IP="$(ipconfig getifaddr en0 2>/dev/null || true)"
if [ -z "$IP" ]; then
    IP="$(ipconfig getifaddr en1 2>/dev/null || true)"
fi

if [ -z "$IP" ]; then
    echo "[sync-lan-url] LAN IPアドレスを検出できませんでした。.env / vite.config.js は変更しません。"
    exit 0
fi

PORT="${APP_SERVE_PORT:-8000}"

if [ -f .env ]; then
    if grep -q '^APP_URL=' .env; then
        sed -i '' -E "s#^APP_URL=.*#APP_URL=http://${IP}:${PORT}#" .env
    else
        echo "APP_URL=http://${IP}:${PORT}" >> .env
    fi
fi

if [ -f vite.config.js ]; then
    sed -i '' -E "s#host: '[^']*', // MacのIPアドレス#host: '${IP}', // MacのIPアドレス#" vite.config.js
fi

echo "[sync-lan-url] APP_URL を http://${IP}:${PORT} に同期しました。"
