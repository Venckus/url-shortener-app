# Project

## Tech stack
PHP 8.2, laravel 10, docker

## Setup
* Copy `.env.example` to `.env`;
* build docker containers `docker-compose up --build`
* migrate database on first run 

## Endpoints
* URL Shortening: method POST, path `/api/url`
* URL Redirect: method GET, path `/api/url/:short_url`
* URL Update: method PATCH, path `/api/url/:short_url`
* URL Delete: method DELETE, path `/api/url/:short_url`

## Data structure
It is supposed, that each url has its `owner`. This way url should have one to one relationship with user.
URL table fields:
- id
- uuid
- user_id
- long_url
- short_url
- expires_at
- timestamps
- deleted_at (soft delete)

# What is not implemented
* analytics could be added as feature: it could be on additional field to count redirects. Or new db analytics table with count of redirects, 

# TODO
* migrations of url table
* routes
* validations
* controller
* services
* resources

## Done