# Sport Center Project

## Overview
The Sport Center project is a web application built using the Laravel framework. It provides a platform for managing sports facilities, bookings, and user interactions.

## Directory Structure
- **app/**: Contains the core application code, including models, controllers, and services.
- **bootstrap/**: Contains files for bootstrapping the application, including the application instance and configuration loading.
- **config/**: Contains configuration files for various services and components of the application.
- **database/**: Contains database migrations, seeders, and factories.
- **public/**: Contains the front-facing files of the application, including the index.php file, assets, and other publicly accessible files.
- **resources/**: Contains views, raw assets (like LESS, SASS, or JavaScript), and language files.
- **routes/**: Contains route definitions for the application, typically defining the application's endpoints.
- **storage/**: Contains compiled views, file uploads, logs, and other files generated by the application.
- **tests/**: Contains the test files for the application, including unit and feature tests.

## Environment Configuration
Make sure to set up your environment variables in the `.env` file. This file contains environment-specific variables and configurations for the application.

## Installation
1. Clone the repository:
   ```
   git clone <repository-url>
   ```
2. Navigate to the project directory:
   ```
   cd Sport-center-main
   ```
3. Install dependencies using Composer:
   ```
   composer install
   ```
4. Install JavaScript dependencies using npm:
   ```
   npm install
   ```
5. Set up your `.env` file by copying the example:
   ```
   cp .env.example .env
   ```
6. Generate the application key:
   ```
   php artisan key:generate
   ```

## Usage
To start the development server, run:
```
php artisan serve
```
Visit `http://127.0.0.1:8000` in your browser to access the application.

## Testing
To run the tests, use the following command:
```
php artisan test
```

## License
This project is licensed under the MIT License. See the LICENSE file for more details.