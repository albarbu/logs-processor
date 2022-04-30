# Logs importer & count analyzer

Created on top of [Symfony-Docker](https://github.com/dunglas/symfony-docker) package.

## Setup

The below directions takes as example the installation on the local domain `logs-processor.localhost` over HTTP (on port 8000).

Prerequisites: Docker is installed on your system.

0. Checkout the code from this repository:<br>
`git clone https://github.com/albarbu/logs-processor.git`
1. If not already installed earlier, [install Docker Compose](https://docs.docker.com/compose/install/)
2. Add the `logs-processor.localhost` domain to the /etc/hosts file, as alias for 127.0.0.1.
3. Run the below command from CLI after moving to the project directory:<br>
`POSTGRES_USER=legal1 POSTGRES_PASSWORD=legal1 POSTGRES_DATABASE=legal_one SYMFONY_VERSION=5.4.* HTTP_PORT=8000 SERVER_NAME="http://logs-processor.localhost" docker-compose build --pull --no-cache`
<br>to get the necessary images and build the containers.
4. Create the .env.local configuration file in the root directory, with a content like below:<br>
`SERVER_NAME=http://logs-processor.localhost` <br>
`HTTP_PORT=8000`<br>
`POSTGRES_USER=legal1`<br>
`POSTGRES_PASSWORD=legal1`<br>
`POSTGRES_DB=legal_one`<br>
`POSTGRES_VERSION`=13
5. Run<br>
   `HTTP_PORT=8000 SERVER_NAME="http://logs-processor.localhost" docker-compose up -d`<br>
to raise the containers up.
6. Enter the main application container, e.g<br>
`docker exec -it logs-processor_php_1 /bin/sh`<br> and run<br>`composer install`<br>to import required packages.
7. In the same main container, run the migrations if needed:<br>
`bin/console doctrine:migrations:migrate`<br>
8. Open `http://processor.localhost:8000/docs` in your web browser of choice and check the documentation for the API endpoint implemented
9. Run `docker-compose stop` when you want to stop the containers.

## Functionalities

1. [Logs import](docs/import.md)
2. [Logs count API endpoint](docs/api.md)
