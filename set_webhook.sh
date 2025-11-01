#!/bin/bash
# Ganti YOUR_DOMAIN_HERE dengan domain yang sesuai (https)
BASE_URL="https://YOUR_DOMAIN_HERE"
BOT_TOKEN="8320580407:AAG0kZRP3MmJyt4vAoF7gPAKmWHTvjYqGs8"
echo "Setting webhook to $BASE_URL/bot.php"
curl -s "https://api.telegram.org/bot${BOT_TOKEN}/setWebhook?url=${BASE_URL}/bot.php"
