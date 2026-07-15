@echo off
cd /d "c:\Users\catac\OneDrive\Desktop\backend\inventory"
echo === Requiring Laravel Breeze package ===
call composer require laravel/breeze --dev
echo === Running Breeze Blade installer ===
call php artisan breeze:install blade --dark --pest --no-interaction
echo === Installing npm packages ===
call npm install
echo === Compiling assets ===
call npm run build
echo === Breeze installation completed! ===
