# Weather mini project in Drupal

## Description
This is a Drupal-based project that retrieves weather data from online services, set up to run on an Apache24 server with a Docker-based database server. Drupal version is 11.0.7

## Prerequisites
- **Apache Server** (configured to serve your Drupal application)
- **Docker** (for running the database server in a container)
- **PHP**
- **Composer** (for installing Drupal and managing Drupal dependencies)

###Docker container
docker run -d --name mysql -p 3306:3306 -e MYSQL_ROOT_PASSWORD=root mysql:latest

