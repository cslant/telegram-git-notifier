#!/bin/bash

echo "Setting up config files for Telegram Git Notifier..."

mkdir -p storage/json/tgn

json_files=(
    "github-events.json"
    "gitlab-events.json"
    "tgn-settings.json"
)

for file in "${json_files[@]}"; do
    if [ ! -f "storage/json/tgn/$file" ]; then
        cp "$(dirname "$0")/config/jsons/$file" "storage/json/tgn/$file"
        echo "Created storage/json/tgn/$file"
    fi
done

if [[ "$(uname -s -r)" == *"Linux"* && "$(cat /etc/os-release)" == *"Ubuntu"* ]]; then
    # shellcheck disable=SC2196
    OWNER=$(ps aux | egrep '(apache|httpd|nginx)' | grep -v root | head -n1 | awk '{print $1}')
    if [ -z "$OWNER" ]; then
        OWNER=$(whoami)
    fi
    sudo chown -R "$OWNER":"$OWNER" storage/json/tgn
fi

echo "Telegram Git Notifier config files are ready!"
