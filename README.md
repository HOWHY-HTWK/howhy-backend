
# HOWHY Backend

## Instructions to run this project

There are two ways to run this project. Either locally or with Docker

### locally

- Run `composer install`
- run `cp .env.example .env.`
- set the database password in the .env file and in the docker compose file
- start the database with `docker compose up`
- Run `php artisan key:generate`
- Run `php artisan migrate`
- set the admin password in the .env file
- Run `php artisan db:seed` to generate the admin user
- Run `php artisan serve`

- If you run the frontend (https://github.com/HOWHY-HTWK/howhy-frontend/tree/development) you can log in there with admin@admin.net and your admin password

### Docker

- run `cp .env.example .env.`
- set the database password in the .env file
- set the admin password in the .env file

- add the laravel sail alias `alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'`
- add the line to  ~/.zshrc or ~/.bashrc
- `sail up`to run the project
