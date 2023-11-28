# URL shortening app

## Introduction
This project is a URL shortener service. It allows users to create short URLs from long ones, which can be useful for sharing links. The project is built using Laravel.

## Prerequisites
- Docker, Docker compose
- PHP 8.2
- Laravel 10
- PostGreSQL 13

## Installation
1. Clone the repository
2. Copy `.env.example` to `.env`;
3. build docker containers `docker-compose up --build`
4. Run migrations with `php artisan migrate`

## Usage
To create a short URL, send a POST request to `/urls` with the following parameters:
- `title`: The title of the URL
- `long_url`: The original, long URL

## API Documentation
### POST `/api/urls`
Creates a new short URL.
Request parameters:
- `user_id`: string
- `title`: string
- `long_url`: string
- `short_code`: string ([a-z] or `-`), max length 50
- `expires_at` => string date after now

### PATCH `/api/urls/:url_id`
Updates a short URL by using `urls.id` (uuid).
Request parameters:
- `title`: string (optional)
- `long_url`: string (optional)
- `short_code`: string ([a-z] or `-`), max length 50 (optional)
- `expires_at` => string date after now (optional)

### DELETE `/api/urls/:url_id`
Deletes a short URL by using `urls.id` (uuid).

### GET /{shortCode}
Redirects to the original URL associated with the given short code.

## Database Schema
The `urls` table has the following fields:
- `id` (uuid)
- `user_id` (foreign)
- `title`
- `long_url`
- `short_code` (index)
- `expires_at` (nullable)
- `timestamps`
- `deleted_at` (soft delete)

## Testing
Run tests with `php artisan test`.

## Deployment
Deployment instructions will vary depending on your hosting provider. Generally, you will need to:
1. Clone the repository on your server
2. Install dependencies with `composer install`
3. Set up your environment variables in `.env`
4. Run migrations with `php artisan migrate`

## Future Improvements
- Implement user authentication and validate short URL ownership in the update and delete endpoints.
- Add analytics feature to count redirects.
- Improve the short URL code generator to include numbers and special characters.