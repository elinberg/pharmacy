# Laravel Pharmacy Finder application

Application that gets your current Geolocation and finds the 5 nearest Pharmacies via REST Api. The default database is set to sqlite however
the migrations have also been tested with mysql 5.6

Home Page:
http://localhost:8000

API Example:
http://localhost:8000/api/pharmacy/38.9115038/-94.7894597/5

## Requirements
php 5.6.18

Composer

Sqlite or Mysql

## Install Instructions:
git clone https://github.com/elinberg/pharmacy.git

cd pharmacy

composer update

php artisan migrate:refresh --seed

php artisan serve

## Files of interest
## Controller
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

## Changelog
refactored PharmacyController and moved code into Pharmacy Model getNearestPharmacy
created distance function in MySQL, to calculate distance from origin lat/long to the
point values create on insert
utilized mysql 5.7 Geo-Spacial features
added spacial index on point column
updated seed process to create `point` column on insert
simplified the response from Google and sorted associative array by the meter element
added comment headers to methods
sqlite is no longer an option

#todo
A stored procedure might be faster then the function
There is something called the sphinx server that claims to make these calculations lightning fast