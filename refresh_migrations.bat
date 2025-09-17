@echo off
for %%f in (database\migrations\2025_09_12_2*.php) do (
    echo Refreshing %%f ...
    php artisan migrate:refresh --path=database/migrations/%%~nxf
)
