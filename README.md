# Logs importer & count analyzer

Created on top of [Symfony-Docker](https://github.com/dunglas/symfony-docker) package.

## Setup

0. Presumption: Docker is installed on your system.
1. If not already installed earlier, [install Docker Compose](https://docs.docker.com/compose/install/)
2. Run<br>
`POSTGRES_USER=legal1 POSTGRES_PASSWORD=legal1 POSTGRES_DATABASE=legal_one SYMFONY_VERSION=5.4.* HTTP_PORT=8000 SERVER_NAME="http://legal-one-logs.localhost" docker-compose build --pull --no-cache`
<br>to build the necessary images
3. Create the .env.local configuration file, with a content like below:<br>
SERVER_NAME=http://legal-one-logs.localhost<br>
HTTP_PORT=8000<br>
POSTGRES_USER=legal1<br>
POSTGRES_PASSWORD=legal1<br>
POSTGRES_DB=legal_one<br>
4. Run<br>`docker-compose up -d`<br>to raise the containers up.
5. Add the `legal-one-logs.localhost` domain to the /etc/hosts file, as alias for 127.0.0.1.
6. Enter the main application container (e.g. `docker exec -it logs-processor_php_1 /bin/sh`) and run `composer install` to import all packages.
7. In the same main container, run the migrations: `bin/console doctrine:migrations:migrate`.
8. Open `http://legal-one-logs.localhost:8000/docs` in your web browser of choice and check the documentation for the API endpoint implemented
9. Run `docker-compose stop` when you want to stop the containers.

## Functionalities

1. [Logs import](docs/import.md)
2. [Logs count API endpoint](docs/api.md)
