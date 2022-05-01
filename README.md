# Logs importer & count analyzer

Created on top of [Symfony-Docker](https://github.com/dunglas/symfony-docker) package.

## Setup

The below directions takes as example the installation of the application to the local domain `logs-processor.localhost` and make it accessible over HTTP on port 8000.
Feel free to adapt the variables if you want to.

*Prerequisites:* Docker is installed on your system, and also [Docker Compose](https://docs.docker.com/compose/install/).

1. Checkout the code from this repository:<br>
`git clone https://github.com/albarbu/logs-processor.git`
2. Add the `logs-processor.localhost` domain to the `/etc/hosts` file, as alias for `127.0.0.1`.
3. Create the `.env.local` configuration file in the root directory, with a content like below:<br>
      `SERVER_NAME=http://logs-processor.localhost`<br>
      `HTTP_PORT=8000`<br>
      `POSTGRES_USER=legal1`<br>
      `POSTGRES_PASSWORD=legal1`<br>
      `POSTGRES_DB=legal_one`<br>
      `POSTGRES_VERSION`=13
4. Run the below command from CLI after moving to the project root directory:<br>
`POSTGRES_USER=legal1 POSTGRES_PASSWORD=legal1 POSTGRES_DATABASE=legal_one SYMFONY_VERSION=5.4.* HTTP_PORT=8000 SERVER_NAME="http://logs-processor.localhost" docker-compose build --pull --no-cache`
<br>to get the necessary images and build the containers.
5. Run<br>
   `HTTP_PORT=8000 SERVER_NAME="http://logs-processor.localhost" docker-compose up -d`<br>
to raise the containers up.
6. Enter the main application container, e.g<br>
`docker exec -it logs-processor_php_1 /bin/sh`<br> and run<br>`composer install`<br>to import required packages.
7. In the same main container, run the migrations (if needed):<br>
`bin/console doctrine:migrations:migrate`<br>
8. Open `http://processor.localhost:8000/docs` in your web browser of choice and check the documentation for the API endpoint implemented
9. Run `docker-compose stop` when you want to stop the containers.

## Make commands

To facilitate the containers related operations, a `Makefile` has been added in the root directory of the project.<br>
It contains several commands that permit building the containers, raising them up or stopping them, and also accessing both (app and db) containers.
To see the supported commands, type `make` or `make help` on the CLI.<br>
For example instead of setup step 4 command, you can simply issue a `make build` command. Ensure the local domain is the right one first, and also adjust the container names in the `Makefile` if needed.  

## Functionalities

1. [Logs import](docs/import.md)
2. [Logs count API endpoint](docs/api.md)
