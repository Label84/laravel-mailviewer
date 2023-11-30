<?php

return [

    /*
     * Enable/disable MailViewer
     *
     * @type boolean
     */
    'enabled' => env('MAILVIEWER_ENABLED', true),

    /*
     * Table name in the database
     */
    'table_name' => 'mail_viewer_items',

    /**
     * Database connection to use
     */
    'database_connection' => env('DB_CONNECTION', 'mysql'),

    'route' => [
        /**
         * The prefix for routes
         */
        'prefix' => '/admin/mailviewer',

        /**
         * The middleware to use for all routes
         */
        'middleware' => [
            'web',
            // 'auth',
            // TODO: add your (admin) middleware to prevent unwanted access
        ],
    ],

    'view' => [
        /**
         * Sets the name on the overview page
         */
        'title' => 'MailViewer',

        /**
         * Sets the name on the analytics page
         */
        'analytics_title' => 'MailViewer Analytics',

        /**
         * Sets the number of items per page on the overview page
         */
        'items_per_page' => 50,

        /*
        * Open the mail in a new tab or in the same tab
         */
        'use_tabs' => false,

        /**
         * Show the mailer information on the overview page
         */
        'show_mailer' => true,

        /**
         * Sets the preferred URL for the 'Back to Laravel' link
         */
        'back_to_application_link_url' => '/',

        /**
         * Set the title for the 'Back to Laravel' link
         */
        'back_to_application_link_title' => 'Back to Laravel',
    ],

    'database' => [
        'include' => [
            /**
             * Store additional message data to the headers column
             */
            'headers' => true,
            /**
             * Store the body of the message in the database
             */
            'body' => true,
        ],

        'exclude' => [
            /*
             * Notifications to exclude from being stored in the database
             */
            'notification' => [
                // '\Illuminate\Auth\Notifications\ResetPassword',
            ],

            /*
             * Notifications to email addresses to exclude from being stored in the database
             */
            'email' => [
                // 'info@example.com',
            ],
        ],
    ],
];
