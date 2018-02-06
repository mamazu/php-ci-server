#PHP CI
This project is a try to run a local continous integration server that is based on php and the framework Symfony.

## Installation
Before you can install the framework you need to have:
* A webserver that can run Symfony (3.4)
* PHP 7.0 or higher
* [composer](http://getcomposer.org) has to be installed

## Setting up the server
1. Create a VHost on your server that points to the web root of the project (the folder `public`) with the correct rights. You find a guide on how to do this [here](https://symfony.com/doc/current/setup/web_server_configuration.html).
2. Run `composer install` to install all dependencies.
3. Configure your database in the `.env` file in the `DATABASE_URL=mysql://user:password@host/database_name` line
4. Create the database with `bin/console doctrine:database:install`
5. Update the database `bin/console doctrine:schema:update -f`

Now you should see a page looking like this:
<img src='images/index.png' />