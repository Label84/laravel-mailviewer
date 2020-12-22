<?php

return [

    /*
     * Enable the package
     *
     * @type boolean
     */
    'enabled' => true,

    /*
     * Database settings
     */
    'table_name' => 'mail_viewer_items',
    'database_connection' => env('DB_CONNECTION', 'mysql'),

    /*
     * Route settings
     */
    'route' => [
        'prefix' => 'admin/mailviewer',
        'middleware' => ['web', 'auth'],
    ],

    /*
     * View settings
     */
    'view' => [
        'title' => 'MailViewer',
        'items_per_page' => 50,
        'use_tabs' => false,
        'show_mailer' => true,
    ],

    /*
     * Database settings
     */
    'database' => [

        /*
         * If set, it will save additional message data to the headers column in the database table.
         */
        'include' => [
            'headers' => true,
        ],

        'exclude' => [
            /*
             * All notification listed, are excluded and won't be saved to the database.
             */
            'notification' => [
                // '\Illuminate\Auth\Notifications\ResetPassword',
            ],

            /*
             * All email addresses listed [to], are excluded and won't be saved to the database.
             */
            'email' => [
                // 'info@example.com',
            ],
        ],
    ],

    /*
     * When the cleanup command is executed, all records older than the days specified will be deleted.
     */
    'delete_items_older_than_days' => 30,
];
