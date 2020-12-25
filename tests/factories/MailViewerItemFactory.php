<?php

namespace Label84\MailViewer\tests\Factories;

use Faker\Generator as Faker;
use Illuminate\Mail\Events\MessageSent;
use Label84\MailViewer\Models\MailViewerItem;

$factory->define(MailViewerItem::class, function (Faker $faker) {
    return [
        'event_type' => MessageSent::class,
        'mailer' => $faker->randomElement([
            'smtp',
            'postmark',
            'log',
        ]),
        'headers' => null,
        'notification' => $faker->randomElement([
            '\Illuminate\Auth\Notifications\ResetPassword',
            '\Illuminate\Auth\Notifications\VerifyEmail',
        ]),
        'recipients' => ['to' => [$faker->safeEmail, $faker->safeEmail], 'cc' => [], 'bcc' => []],
        'subject' => $faker->sentence,
        'body' => $faker->randomHtml(2, 3),
        'sent_at' => $faker->dateTimeBetween('-30 days', 'today'),
    ];
});
