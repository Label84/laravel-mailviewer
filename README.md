# Laravel MailViewer

[![Latest Stable Version](https://poser.pugx.org/label84/laravel-mailviewer/v/stable?style=flat-square)](https://packagist.org/packages/label84/laravel-mailviewer)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Quality Score](https://img.shields.io/scrutinizer/g/label84/laravel-mailviewer.svg?style=flat-square)](https://scrutinizer-ci.com/g/label84/laravel-mailviewer)
[![Total Downloads](https://img.shields.io/packagist/dt/label84/laravel-mailviewer.svg?style=flat-square)](https://packagist.org/packages/label84/laravel-mailviewer)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/label84/laravel-mailviewer/run-tests?label=Tests&style=flat-square)

Laravel MailViewer enables you to view and filter through mail sent by your Laravel application. It saves all sent mail to the database automatically. It also provides an overview of the number of emails sent grouped by Notification.

![MailViewer screenshot](./docs/screenshot_default.png?raw=true "MailViewer Screenshot")

- [Requirements](#requirements)
- [Limitations](#limitations)
- [Installation](#installation)
- [Usage](#usage)
  - [View mails](#view-mails)
  - [Analytics](#analytics)
- [Configuration](#configuration)
  - [Commands](#commands)
  - [Exclude records](#exclude-records)
- [Notes](#Notes)
- [Tests](#tests)
- [Security](#security)
- [License](#license)

## Requirements

- Laravel 7.x or 8.x
- PHP >= 7.4 or 8.0

## Laravel support

| Version       | Release       |
|:--------------|:-------------:|
| 7.x to 8.x    | 1.0           |

## Limitations

This package is only able to track mails send via [SwiftMailer library](https://swiftmailer.symfony.com). By default Laravel uses this library when sending mails via [Mailables](https://laravel.com/docs/8.x/mail) and [Notifications](https://laravel.com/docs/8.x/notifications).

## Installation

### 1. Require package

Add the package to your application:

```sh
composer require label84/laravel-mailviewer
```

You can also do this manually by updating your composer.json file.

### 2. Install package

Add the config and migration to your application:

```sh
php artisan mailviewer:install
```

#### 2.1 Install package manually (alternative)

You can also install the package manually by executing the following commands:

```sh
php artisan vendor:publish Label84\MailViewer\MailViewerServiceProvider --config
php artisan vendor:publish Label84\MailViewer\MailViewerServiceProvider --migrations
```

#### 2.2 Publish the views (optional)

To change the default views, you can publish them to your application with the following command:

```sh
php artisan vendor:publish Label84\MailViewer\MailViewerServiceProvider --views
```

### 3. Run migrations

Run the migration command:

```sh
php artisan migrate
```

## Usage

### View mails

To preview the mails sent by your application visit: `/admin/mailviewer`

You can change the route in the config file.

To view the content of the mail you can click on the UUID (blue link).

#### Filter through the items

You can filter the listed mails in the overview with query parameters (the ?foo=bar ones).

| Parameter     | Value                                    | Example           |
|:--------------|:-----------------------------------------|:------------------|
| to=           | email                                    | info@example.com  |
| cc=           | email                                    | info@example.com  |
| bcc=          | email                                    | info@example.com  |
| notification= | class basename of Notification           | VerifyEmail       |
| from=         | [Carbon](https://carbon.nesbot.com/docs) | 2 hours ago       |
| till=         | [Carbon](https://carbon.nesbot.com/docs) | yesterday         |
| around=       | [Carbon](https://carbon.nesbot.com/docs) | 2020-12-24 06:00  |

The 'notification' parameter only requires the class basename (ie. \Illuminate\Auth\Notifications\VerifyEmail = VerifyEmail).

The around parameter show all mails sent around the given time. By default is will show all mails sent 10 minutes before and 10 minutes after the given time. You can customize the number of minutes by adding an additional &d=X parameter where X is the number of minutes.

#### Filter example #1

View all VerifyEmail notification mails to info@example.com sent yesterday:

`/admin/mailviewer?to=info@example.com&notification=VerifyEmail&from=yesterday&till=yesterday`

#### Filter example #2

View all mails sent in the last 2 hours:

`/admin/mailviewer?from=2 hours ago`

#### Filter example #3

View all mails sent this morning around ~08:00:

`/admin/mailviewer?around=2020-12-31 08:00&d=60`

### Analytics

![MailViewer Analytics screenshot](./docs/screenshot_analytics.png?raw=true "MailViewer Analytics Screenshot")

To preview the analytics page visit: `/admin/mailviewer/analytics`

You can change the route in the config file.

## Configuration

Publish the config file and views file to be able to customize the MailViewer package.

### Commands

#### Clean up database records

The package has has a built-in command to clean up older database records. Add this command to your Kernel and run it daily/weekly.

```sh
php artisan mailviewer:cleanup
```

By default the command will remove all records older than 30 days.

##### Delete records that are older than x days

```sh
php artisan mailviewer:cleanup --days=60
```

### Exclude records

#### Exclude mail by notification of recipient

In the config file you can add an array of notification classes and/or array of email addresses that should be excluded.

## Notes

- Bootstrap 5 is used for the default interface
- Middleware [web] + [auth] are applied by default

## Tests

```sh
./vendor/bin/phpunit
```

## Security

By default the package only applies the [web] and [auth] middleware to the MailViewer routes. When used in production make sure you apply extra middleware or other security measure to prevent unwanted usage.

## License

[MIT](https://opensource.org/licenses/MIT)
