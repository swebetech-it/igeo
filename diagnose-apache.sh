#!/bin/bash

echo "=========================================="
echo "       APACHE DIAGNOSTIC TOOL"
echo "=========================================="

echo "[1] Listing Enabled Sites:"
ls -l /etc/apache2/sites-enabled/
echo ""

echo "[2] Checking Virtual Host Configuration:"
sudo apache2ctl -S
echo ""

echo "[3] Checking for 'brightstar' references in Apache config:"
grep -r "brightstar" /etc/apache2/ 2>/dev/null || echo "No 'brightstar' found in /etc/apache2/"
echo ""

echo "[4] Checking igeo.conf content (first 20 lines):"
if [ -f "/etc/apache2/sites-enabled/igeo.conf" ]; then
    head -n 20 /etc/apache2/sites-enabled/igeo.conf
else
    echo "igeo.conf is NOT enabled."
fi
echo ""

echo "[5] Checking 000-default.conf content (if enabled):"
if [ -f "/etc/apache2/sites-enabled/000-default.conf" ]; then
    head -n 20 /etc/apache2/sites-enabled/000-default.conf
else
    echo "000-default.conf is NOT enabled."
fi
