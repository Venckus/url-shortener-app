# Project

## Tech stack
PHP 8.2, Laravel 10, docker

## Setup
* Copy `.env.example` to `.env`;
* build docker containers `docker-compose up --build`
* migrate database on first run 

## Endpoints
* URL Shortening: method POST, path `/api/url`
* URL Update: method PATCH, path `/api/url/:url_id`
* URL Delete: method DELETE, path `/api/url/:url_id`
* URL Redirect: method GET, path `/:short_code`

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
* Short URL code generator is simple without numbers and special characters only including `-`. Thus the validation for short URL code in *Request is setup accordingly.
* User authentication is not implemented. Therefore, there is no validation for short URL ownership in the update and delete endpoints. Users could be identified by using tokens and the actual user model should be retrieved in *Request or Middleware and then used to validate if short URL belongs to user.
* analytics could be added as feature: it could be on additional field to count redirects. Or new db analytics table with count of redirects, ...
* Code structure is used as "Laravel default' by placing classes into default Laravel folders.

# TODO

## Done
* redirect endpoint with tests, naming improved
* destroy endpoint with tests
* update endpoint with tests, validation, service
* store endpoint with tests, routes, validation, service
* migrations of url table