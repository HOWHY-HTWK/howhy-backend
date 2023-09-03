
# HOWHY Backend

The Howhy Backend is a Laravel Backend with MariaDB Database.

## Introduction

Howhy is a React Webapp with a Laravel Backend, that can display educational Videos with interactive questions. The Progress of the Users can be stored and the users can unlock prizes. The Videos a stored on a third party Platform.

## Instructions to run this project

There are two ways to run this project. Either locally or with Docker in combination with the CLI "Sail"

### Docker

- run `cp .env.example .env.`
- set the database password and the admin password in the .env file
- add the laravel sail alias `alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'`
- add the line to  ~/.zshrc or ~/.bashrc
- `sail up` to run the project

The first time you run the project:

- Run `sail artisan migrate` to create all nessecary database tables
- run `sail artisan db:seed` to make the first user with admin rights
- If you run the [frontend](https://github.com/HOWHY-HTWK/howhy-frontend/tree/development) you can log in there with '<admin@admin.net>' and your previously created admin password

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

- If you run the [frontend](https://github.com/HOWHY-HTWK/howhy-frontend/tree/development) you can log in there with '<admin@admin.net>' and your admin password

### Deploy with Docker

- run `cp .env.example .env.`
- set the database password, the admin password and other properties in the .env file
- change the uid in the docker-compose-prod.yml file to the uid of the current user in the host system
- `docker compose -f docker-compose-prod.yml up` to run the project
- Run `docker exec howhy-backend-app-1 composer install` to install all dependencies
- Run `docker exec howhy-backend-app-1 php artisan key:generate`
- Run `docker exec howhy-backend-app-1 php artisan migrate` to create all nessecary database tables
- run `docker exec howhy-backend-app-1 artisan db:seed` to make the first user with admin rights

### Server Configuration

To run both Frontend and Backend on a production Server an additional reverse-proxy is needed. The Imprint and Privacy Statement must also be provided seperatly. The Configuration must look something like this:

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
