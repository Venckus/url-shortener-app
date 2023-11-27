# Project

## Tech stack
PHP 8.2, Laravel 10, docker

## Setup
* Copy `.env.example` to `.env`;
* build docker containers `docker-compose up --build`
* migrate database on first run 

## Endpoints
* URL Shortening: method POST, path `/api/url`
* URL Redirect: method GET, path `/api/url/:short_code`
* URL Update: method PATCH, path `/api/url/:short_code`
* URL Delete: method DELETE, path `/api/url/:short_code`

## Data structure
It is supposed, that each url has its `owner`. This way url should have one to one relationship with user.
URL table fields:
- id (uuid)
- user_id
- title
- long_url
- short_code
- expires_at (nullable)
- timestamps
- deleted_at (soft delete)

# What is not implemented
* users table could use uuid instead of id for security reasons.
* analytics could be added as feature: it could be on additional field to count redirects. Or new db analytics table with count of redirects, ...
* Code structure is used as "Laravel default' by placing classes into default Laravel folders.

# TODO
* routes
* validations
* controller
* services
* resources

* update endpoint with tests, routes, validation, service

## Done
* store endpoint with tests, routes, validation, service
* migrations of url table