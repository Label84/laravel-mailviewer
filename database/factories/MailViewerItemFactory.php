<?php

namespace Label84\MailViewer\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Mail\Events\MessageSent;
use Label84\MailViewer\Models\MailViewerItem;

class MailViewerItemFactory extends Factory
{
    protected $model = MailViewerItem::class;

    public function definition()
    {
        return [
            'event_type' => MessageSent::class,
            'mailer' => $this->faker->randomElement([
                'smtp',
                'postmark',
                'log',
            ]),
            'headers' => null,
            'notification' => $this->faker->randomElement([
                '\Illuminate\Auth\Notifications\ResetPassword',
                '\Illuminate\Auth\Notifications\VerifyEmail',
            ]),
            'recipients' => ['to' => [$this->faker->safeEmail, $this->faker->safeEmail], 'cc' => [], 'bcc' => []],
            'subject' => $this->faker->sentence,
            'body' => $this->faker->randomHtml(2, 3),
            'sent_at' => $this->faker->dateTimeBetween('-30 days', 'today'),
        ];
    }
}
