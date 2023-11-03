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
    chmod 777 storage/json/tgn/*.json
fi

echo "Telegram Git Notifier config files are ready!"
