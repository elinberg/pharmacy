# Laravel Pharmacy Finder application

Application that gets your current Geolocation and finds the 5 nearest Pharmacies via REST Api

Home Page:
http://localhost:8000

API Example:
http://localhost:8000/api/pharmacy/38.9115038/-94.7894597/5

## Requirements
php 5.6.18
Composer
Sqlite

## Install Instructions:
git clone https://github.com/elinberg/pharmacy.git
cd pharmacy
composer update
php artisan migrate:refresh --seed
php artisan serve

# Files of interest
##Controller
app/Http/Controllers/PharmacyController.php
## Model
app/Pharmacy.php
## Sqlite file
database/database.sqlite
## Migration
database/migrations/2017_05_07_035201_create_pharmacies.php
## Seed
database/seeds/PharmaciesTableSeeder.php
pharmacy/public/csv/pharmacies.csv

