@echo off
echo 🚀 Starting AquaPure Web Applications...
start cmd /k "php artisan serve"
start cmd /k "npm run dev"
echo 🔗 Both servers are running! Open http://127.0.0.1:8000 in your browser.
