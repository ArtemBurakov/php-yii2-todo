<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">php-yii2-todo</h1>
</p>

The advanced php-yii2-todo project template includes three layers: frontend, backend, and console, each of which is a separate Yii application. It is designed as the back-end logic for the [todo application](https://github.com/ArtemBurakov/todo) as well as the front-end part. It uses MySQL database and Firebase services to send notifications to registered users` devices.

## Features

- :calling: Sending messages using Firebase services to users' devices.
- :closed_lock_with_key: Secure user authorization using own created method with the use of access token in request header`s.
- :rocket: Powerful developer and debugging features provided by [yii php framework](https://www.yiiframework.com/).
- :wrench: Easy to modify and configure custom server endpoints.

## Requirements

1. Installed Linux, Apache, MySQL, PHP (LAMP) stack on your local machine or remote server.

#### LAMP stack installiation

If you already have a LAMP stack installed, you can skip this step. Otherwise, complete the LAMP stack installation on your target machine or follow this [tutorial](https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-on-ubuntu-20-04).

## Directory structure

```
common
    config/              contains shared configurations
    mail/                contains view files for e-mails
    models/              contains model classes used in both backend and frontend
    tests/               contains tests for common classes
console
    config/              contains console configurations
    controllers/         contains console controllers (commands)
    migrations/          contains database migrations
    models/              contains console-specific model classes
    runtime/             contains files generated during runtime
backend
    assets/              contains application assets such as JavaScript and CSS
    components/          contains application components
    config/              contains backend configurations
    controllers/         contains Web controller classes
    models/              contains backend-specific model classes
    modules              contains application api versions
    runtime/             contains files generated during runtime
    tests/               contains tests for backend application
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
frontend
    assets/              contains application assets such as JavaScript and CSS
    components/          contains application components
    config/              contains frontend configurations
    controllers/         contains Web controller classes
    models/              contains frontend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for frontend application
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
vendor/                  contains dependent 3rd-party packages
environments/            contains environment-based overrides
```

## Getting started

### Installation

* Clone the project to `/var/www` folder

```bash
  cd /var/www && git clone https://github.com/ArtemBurakov/php-yii2-todo.git
```

* Navigate to the project folder

```bash
  cd php-yii2-todo
```

* Then follow the [instructions](https://www.yiiframework.com/doc/guide/2.0/en/start-installation) how to install yii

## Configuration

### Create Database

* Execute `create-db.sql` file located in the project root folder

Replace `YOUR_DB_USER_NAME` with your MySQL user name. After running this command, you will be prompted for the MySQL user password.

```bash
  mysql -u YOUR_DB_USER_NAME -p < create-db.sql
```

Now a new database for this project has been created. You can check it by using [PhpMyAdmin](https://www.phpmyadmin.net/).

### Configure Database

* Open `main-local.php` file located at `php-yii2-todo/common/config` folder and replace file content with your MySQL user data:

```bash
  'username' => 'YOUR_DB_USER_NAME',
  'password' => 'YOUR_DB_USER_PASSWORD',
```

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.