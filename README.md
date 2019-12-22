# Euro Currency Rates (ECR)
## Introduction

This is a small Laravel 6.x app that displays the latest euro currency rates pulled from the 
[website](https://www.bank.lv/statistika/dati-statistika/valutu-kursi/aktualie) of the central bank of Latvia.

It processes the [RSS feed](https://www.bank.lv/vk/ecb_rss.xml) to pull the latest euro currency rates. 
The rates of the day are added to the feed after 17:00 EET (UTC/GMT+2) every day, except on weekends and the following holidays:
New Year's Day (January 1), Good Friday, Easter Monday, Labour Day (May 1), and Christmas (December 25–26).

The output of the app is in Latvian.

[Demo](https://ecr.anatolijstarasovs.lv)

## Description

- This is a PHP [Composer](https://getcomposer.org/) project based on [Laravel 6.x](https://laravel.com/docs/6.x).
- There is one view `app.blade.php` that displays euro currency rates.
- There is one custom controller `RateController.php` with two methods:
<br>`getRates.php` used to fetch currency rates from the RSS feed and save them in the database.
<br>`showRates.php` used to select the latest available rates from the database to display them using Laravel's pagination.
- The routes to the controller are registered in the `routes/web.php` file.
- The app is using the default [Bootstrap](https://getbootstrap.com) styling. The following commands had to be run to enable Bootstrap frontend scaffolding:
```
composer require laravel/ui --dev
php artisan ui bootstrap
```
- Authentication related files, as well as most of the sample files that comes with a fresh Laravel installation have been left intact.
### Database
- The app can use either [MySQL](https://dev.mysql.com/downloads/mysql/) or [MariaDB](https://mariadb.org/) as a database.
- The database schema consists of two tables:
<br> `Currencies` stores currency names in Latvian mapped to their respective country codes.
<br> `Rates` stores the currency rates pulled from the RSS feed of the central bank.
The following commands were used to generate Eloquent models and respective migration files:
```
php artisan make:model Currency --migration
php artisan make:model Rate --migration
```
- There are two database schema migration files:
<br>`2019_12_19_212947_create_currencies_table.php`
<br>`2019_12_19_213846_create_rates_table.php`
- There is one custom seeder `CurrencyTableSeeder.php` used to populate the `Currencies`.
<br>The following command must be run after adding custom seeder classes:
```
composer dump-autoload
```
## Installation
Describes the installation process on Debian 10 with Apache as a web server. It assumes that you have a virtual server with `ssh` access available.
### Prerequisites
#### Apache
[How to install the Apache Web Server on Debian 10](https://www.digitalocean.com/community/tutorials/how-to-install-the-apache-web-server-on-debian-10)
<br><br>Make sure that the `DocumentRoot` of your virtual host includes the `public` folder because the main `index.php` file of the project resides in the `public` directory of the project.
For example:
```
/var/www/ecr/public
```
Also make sure that `AllowOverride All` is set for the project's public folder. Otherwise, non-root routes won't resolve.
```
<Directory /var/www/ecr/public/>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
```
Use [Let's Encrypt](https://letsencrypt.org/) and the [certbot](https://certbot.eff.org/) to add free TLS/SSL support.
```
sudo apt-get install certbot python-certbot-apache
sudo certbot --apache
```
#### PHP
Note that the server must meet the following [requirements](https://laravel.com/docs/6.x) for Laravel:
- PHP >= 7.2.0
- BCMath PHP Extension
- Ctype PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
#### Composer
The [official installation instructions](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos).
```
sudo apt update
sudo apt install curl php-cli php-mbstring git unzip
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
HASH="$(wget -q -O - https://composer.github.io/installer.sig)"
php -r "if (hash_file('SHA384', 'composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
```
#### Git and NodeJS
```
sudo apt update
sudo apt install git
sudo apt install nodejs npm
```
#### Database
Install and configure either MariaDB or MySQL.
[How to install MariaB on Debian 10](https://www.digitalocean.com/community/tutorials/how-to-install-mariadb-on-debian-10).
```
sudo apt install mariadb-server
sudo mysql_secure_installation

# Add a new password protected admin user to be used to connect to the database from the app:
mysql
GRANT ALL ON *.* TO 'admin'@'localhost' IDENTIFIED BY 'PASSWORD' WITH GRANT OPTION;
FLUSH PRIVILEGES;

# Useful commands:
sudo systemctl status mariadb
sudo systemctl start mariadb
sudo mysqladmin version
```
Once installed and configured, create a new database:
```
CREATE DATABASE ecr CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```
### Setup
#### Clone Repository
Clone this repository within the folder created to hold the project:
```
git clone https://github.com/anatolijstarasovs/ecr.git .
```
Directories within the `storage` and the `bootstrap/cache` directories should be writable by Apache or Laravel will not run. 
You can change the ownership of these folders to Apache:
```
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```
#### Setup Environment
There is a file called `.env.example` with configuration values. Copy the file as `.env` and fill in your database configuration parameters:
```
cp .env.example .env
```
Edit the file `nano .env` and make sure to set the following configuration parameters:
```
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecr
DB_USERNAME=admin
DB_PASSWORD=PASSWORD
```
#### Install JavaScript Dependencies
```
npm install
```
#### Install PHP Dependencies
```
composer install --optimize-autoloader --no-dev
```
#### Generate Application Key
```
php artisan key:generate
```
This command generates a random key which is automatically added to the `APP_KEY` variable in the `.env` configuration file.
#### Setup Database
Migrate database schema and seed the currency values with a single command:
```
php artisan migrate --seed
```
## Maintenance
- Run `php artisan down` to put the app into maintenance mode and `php artisan up` to bring it back up. 
- Run `git pull` to get the latest changes from the repository.
- Run `composer install` to check for any changes in the `composer.lock` file.
## Task Scheduling
Within `App\Console\Kernel.php` there is a schedule with a call to pull the new currency rates at 17:30 EET every day:
```
$schedule->call('App\Http\Controllers\RateController@getRates')
    ->timezone('Europe/Riga')
    ->dailyAt('17:30');
```
`php artisan schedule:run` command can be used in the localhost to run the schedule. 
<br> You can also add the following cron job on your server:
```
* * * * * php /var/www/ecr/artisan schedule:run >> /dev/null 2>&1
```
## Tasks
- [ ] Cleanup. Remove default Laravel files that aren't required for this app.
- [ ] Sorting
- [ ] Flags
- [ ] Date selection using a date picker
- [ ] Tests
## License

Licensed under the [MIT license](https://opensource.org/licenses/MIT).
