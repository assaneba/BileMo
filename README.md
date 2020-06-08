# BileMo

Web service exposing the API of BileMo which is a company offering a selection of high-end mobile phones.

## Installation

### Prerequisites 

Install GitHub (<https://gist.github.com/derhuerst/1b15ff4652a867391f03>) .\
Install Composer (<https://getcomposer.org/>) .\

Symfony 4.4 requires PHP 7.1.3 or higher to run.\
Prefer MySQL 5.6 or higher.\

### Download

[![Repo Size](https://img.shields.io/github/repo-size/assaneba/BileMo.svg?label=Repo+Size)](https://github.com/assaneba/BileMo/tree/master) \
After Git installation execute the following command line to download project into your chosen directory:
```
git clone https://github.com/assaneba/BileMo.git

```

Install dependencies by running the following command:
```
composer install
```

### Database

Change database connection in .env file.\
```
DATABASE_URL=mysql://root:@127.0.0.1:3306/bilemo?serverVersion=5.7
```

Create database:
```
php bin/console doctrine:database:create
```

Build the database structure using the following command:
```
php bin/console doctrine:migrations:migrate
```

Load the initial data
```
php bin/console doctrine:fixtures:load
```
### Run the application

Launch the Apache/Php runtime environment by using :
```
php bin/console server:run
```

### Default user credendiatls

Default password for the user is ```passer12345```\
Default username ```twebburn2@nydailynews.com```

## Creator

Assane Thione Ba

[![WebSite Status](https://img.shields.io/website-up-down-green-red/https/philippebeck.net.svg?label=https://assaneba.com)](https://assaneba.com)
[![GitHub Followers](https://img.shields.io/github/followers/assaneba.svg?label=GitHub+:+assaneba+|+Followers)](https://github.com/assaneba)
[![Twitter Follow](https://badgen.net/twitter/follow/assanetba)](https://twitter.com/assanetba)
