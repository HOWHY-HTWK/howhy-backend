# HOWHY Backend

This project is part of the HOWHY project. It is the backend for the HOWHY project. The frontend can be
found [here](https://github.com/HOWHY-HTWK/howhy-frontend/tree/development).

## Project Description

HOWHY is a web application that displays educational videos and lets the user answer interactive questions. It is
intended to add playful elements to learning and thus increase th students' motivation to do so. Through a ranking
system, users can collect points and win prizes for certain promotions.

The educational videos are stored on a third party
platform and is not maintained by the developers of this project.

## Used Technologies

The HOWHY backend is built with the Laravel framework. The database consists of a MariaDB database and can be managed
via MySQL. 

The backend is built as a REST API and provides endpoints for the frontend to interact with.

## Installation

### Prerequisites

- Make sure you have `Docker` installed on your machine.
- Make sure you have `MySQL` installed on your machine.
- Make sure you have `composer` installed on your machine.

### Initial Setup

- Set up the environment file `.env` with the necessary information.
    - Run `cp .env.example .env` to copy the example environment file.
    - In the `.env` file set your MySQL root password.
    - In the `.env` file set a password for the admin user.
- Run `composer install` to install all dependencies.

There are two ways to run this project. Either manually via Docker or with the Sail CLI.

### Sail

- Add the laravel sail alias `alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'`.
- Add the line to your export file (e.g. ~/.zshrc or ~/.bashrc) to make sail globally accessible.
- run `sail up` to start the server.
- Run `sail artisan key:generate` to generate an access key.
- Run `sail artisan migrate` to create all necessary database tables.
- run `sail artisan db:seed` to create an initial user with admin rights.

### Manual Setup

- Start the database with `docker compose up`.
- Run the following commands inside the docker container:
  - Run `php artisan key:generate` to generate an access key.
  - Run `php artisan migrate` to create all necessary database tables.
  - Run `php artisan db:seed` to create an initial user with admin rights.

## Usage

When starting the [HOWHY frontend](https://github.com/HOWHY-HTWK/howhy-frontend/tree/development) you can log in to the UI with the user `admin@admin.net` and your previously set admin password.

## Deployment with Docker

- Go through the initial setup (see above).
- change the uid in the `docker-compose-prod.yml` file to the uid of the current user in the host system
- Run `docker compose -f docker-compose-prod.yml up` to start the server in production mode.
- Run `docker exec howhy-backend-app-1 composer install` to install all dependencies.
- Run `docker exec howhy-backend-app-1 php artisan key:generate` to generate an access key.
- Run `docker exec howhy-backend-app-1 php artisan migrate` to create all necessary database tables.
- run `docker exec howhy-backend-app-1 artisan db:seed` to create an initial user with admin rights.

### Server Configuration

To run both th HOHWY Frontend and HOWHY Backend on a production server an additional reverse-proxy is needed. The
Imprint and Privacy
Statement must also be provided separately. The configuration must look something like this:

```
server {
    server_name <DOMAIN>;

    access_log off;
    error_log /var/log/error.log;

    listen 443 ssl; 

    error_page 403 /error_403.html;

    location = /error_403.html {
        root /usr/share/nginx/html/;
        internal;
    }

    location /info/impressum/ {
        return 301 <DOMAIN>/impressum.html;
    }
    location /info/datenschutz/ {
        return 301 <DOMAIN>/info/datenschutz.html;
    }

    location /backend/ {

        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;

        proxy_pass <DOMAIN>:8000/;
        proxy_read_timeout 90;
    }
    location / {

        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;

        proxy_pass <DOMAIN>:4173;
        proxy_read_timeout 90;
    }

}

server {
    server_name howhy.htwk-leipzig.de;
    access_log off;
    error_log /var/log/error.log;
    listen 80;

    #  return 301 https://$host$request_uri;
    return 301 https://$host/;

}

```

## Trademark

"HOWHY" is a registered Trademark in Germany. Please beware of that if you are make use of this project.
