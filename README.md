# Laravel MailViewer

[![Latest Stable Version](https://poser.pugx.org/label84/laravel-mailviewer/v/stable?style=flat-square)](https://packagist.org/packages/label84/laravel-mailviewer)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Total Downloads](https://img.shields.io/packagist/dt/label84/laravel-mailviewer.svg?style=flat-square)](https://packagist.org/packages/label84/laravel-mailviewer)
![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/label84/laravel-mailviewer/run-tests.yml?branch=master&style=flat-square)

Mailviewer enables you to view and filter mail that is sent by your Laravel application in the browser. The Analytics page gives you insight in the amount of mails sent sent by your application grouped by Notification.

![MailViewer screenshot](./docs/screenshot-default.png?raw=true "MailViewer Screenshot")

- [Laravel Support](#laravel-support)
- [Installation](#installation)
- [Usage](#usage)
  - [Filters](#filters)
  - [Analytics](#analytics)
  - [Examples](#examples)
  - [Exclusion list](#exclusion-list)
- [Tests](#tests)
- [License](#license)

## Laravel Support

| Version | Release |
|---------|---------|
| 12.x    | ^3.0    |
| 11.x    | ^3.0    |

## Limitations

This package tracks mails sent via Laravel's `Mailables` and `Notifications`.

## Installation

### 1. Require package

```sh
composer require label84/laravel-mailviewer
```

### 2. Publish the config file and migration

```sh
php artisan vendor:publish --provider="Label84\MailViewer\MailViewerServiceProvider" --tag="config"
php artisan vendor:publish --provider="Label84\MailViewer\MailViewerServiceProvider" --tag="migrations"
```

#### 2.1 Publish the views (optional)

To change the default views, you can publish them to your application.

```sh
php artisan vendor:publish --provider="Label84\MailViewer\MailViewerServiceProvider" --tag="views"
```

### 3. Run migrations

Run the migration command.

```sh
php artisan migrate
```

## Usage

To preview the mails sent by your application visit: `/admin/mailviewer`. You can change this url in the config file.

You can add `MAILVIEWER_ENABLED=true` to your `.env` file.

### Filters

You can filter the mails in the overview with query parameters - example `/admin/mailviewer?notification=WelcomeMail`.

| Parameter     | Value                                    | Example           |
|:--------------|:-----------------------------------------|:------------------|
| to=           | email                                    | [info@example.com](info@example.com)  |
| cc=           | email                                    | [info@example.com](info@example.com)  |
| bcc=          | email                                    | [info@example.com](info@example.com)  |
| notification= | class basename of Notification           | VerifyEmail       |
| from=         | [Carbon](https://carbon.nesbot.com/docs) | 2 hours ago       |
| till=         | [Carbon](https://carbon.nesbot.com/docs) | yesterday         |
| around=       | [Carbon](https://carbon.nesbot.com/docs) | 2020-12-24 06:00  |

The 'notification' parameter only requires the class basename (ie. \Illuminate\Auth\Notifications\VerifyEmail = VerifyEmail).

The around parameter show all mails sent around the given time. By default is will show all mails sent 10 minutes before and 10 minutes after the given time. You can customize the number of minutes by adding an additional &d=X parameter where X is the number of minutes.

### Analytics

![MailViewer Analytics screenshot](./docs/screenshot-analytics.png?raw=true "MailViewer Analytics Screenshot")

To preview the analytics page visit: `/admin/mailviewer/analytics`. You can change this url in the config file.

On the analytics page you can view the number of mails sent per Notification and the latest time this notification was sent.

### Examples

#### Example #1

View all VerifyEmail mails to [info@example.com](info@example.com).

`/admin/mailviewer?to=info@example.com&notification=VerifyEmail`

#### Example #2

View all mails sent in the last 2 hours.

`/admin/mailviewer?from=2 hours ago`

### Exclusion list

In the config file you can add an array of Notification classes and an array of email addresses that should be excluded. Those notifications and email addresses won't be saved to the database.

## Tests

```sh
composer analyse
composer test
```

## License

[MIT](https://opensource.org/licenses/MIT)
