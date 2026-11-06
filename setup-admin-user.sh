#!/bin/bash

# Swebetech Admin User Setup Script
# Creates a non-root admin user and hardens SSH

USER_NAME="swebetech-admin"

echo "👤 Creating non-root admin user: $USER_NAME..."

# Check if user already exists
if id "$USER_NAME" &>/dev/null; then
    echo "⚠️  User $USER_NAME already exists."
else
    # Add user
    sudo adduser --gecos "" "$USER_NAME"
    # Add to sudo group
    sudo usermod -aG sudo "$USER_NAME"
    echo "✅ User $USER_NAME created and added to sudo group."
fi

echo "=========================================="
echo "⚠️  CRITICAL STEP REQUIRED:"
echo "1. Please finish setting the password for '$USER_NAME' if prompted."
echo "2. Open a NEW terminal and try to SSH as '$USER_NAME'."
echo "3. Verify you can use 'sudo' with that user."
echo "DO NOT PROCEED to Step 2 until you have verified SSH access!"
echo "=========================================="

read -p "Have you verified SSH access for '$USER_NAME'? (y/n) " verified

if [ "$verified" != "y" ]; then
    echo "❌ SSH verification failed. Aborting SSH hardening."
    exit 1
fi

# 2. HARDEN SSH
echo "🔒 Step 2: Hardening SSH (Disabling Root Login)..."
sudo sed -i 's/PermitRootLogin yes/PermitRootLogin no/' /etc/ssh/sshd_config

echo "🔄 Restarting SSH service..."
sudo systemctl restart sshd

echo "✅ SSH Hardened. Root SSH login is now disabled."
echo "=========================================="
echo "🎉 Admin user setup completed!"
