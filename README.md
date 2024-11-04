# Welcome to Inscope Management App

This is a Laravel 11 APIs demonstration app designed to help users manage companies and projects effectively. The application provides a robust API for handling company and project data, allowing users to perform various operations based on their roles and permissions.


# Installation guide

 1. git clone https://github.com/nikelioum/inscope.git
 2. composer install
 3. npm install
 4. npm run build
 5. php artisan migrate --seed


After this, you need to download the JSON file named `PostmanImport.json` from the root of the project. Then, import it into your local Postman installation.

# Users 

**Admin User**
Email: admin@example.com
Password: password

**Simple User**
Email: johndoe@example.com
Password: password

*To test more simple users check the credentials in: 

>  inscope\database\seeders\UserSeeder.php


-----------------

 - For API authentication, I use Sanctum. 
 - For permission checks, I use a simple helper to verify if a user is an admin. All other permission
   logic is handled directly within the controllers.


-----------------

**Pest Testing**

Check if user can get token | Run this in cli `./vendor/bin/pest tests/Feature/AuthToken.php`  