#!/bin/bash

json_files=(
    "github-events.json"
    "gitlab-events.json"
    "tgn-settings.json"
)

for file in "${json_files[@]}"; do
    if [ ! -f "storage/json/tgn/$file" ]; then
        cp "./config/jsons/$file" "storage/json/tgn/$file"
    fi
done

if [[ "$(uname -s -r)" == *"Linux"* && "$(cat /etc/os-release)" == *"Ubuntu"* ]]; then
    chmod 777 storage/json/tgn/*.json
fi
