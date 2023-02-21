<?php

return [

    /*
     * Enable the package.
     *
     * @type boolean
     */
    'enabled' => env('MAILVIEWER_ENABLED', true),

    /*
     * The table name in the database used to store the mails.
     */
    'table_name' => 'mail_viewer_items',

    /**
     * The database connection to the table.
     */
    'database_connection' => env('DB_CONNECTION', 'mysql'),

    'route' => [
        /**
         * URL to visit the page.
         */
        'prefix' => '/admin/mailviewer',

        /**
         * Apply Middleware to prevent unwanted access to the page.
         */
        'middleware' => [
            'web',
            // 'auth',
        ],
    ],

    'view' => [
        /**
         * Sets the name on the overview page.
         */
        'title' => 'MailViewer',

        /**
         * Sets the name on the overview page.
         */
        'analytics_title' => 'MailViewer Analytics',

        /**
         * Limit the number of items per page.
         */
        'items_per_page' => 50,

        /*
        * Open a new tab to preview the mail.
         */
        'use_tabs' => false,

        /**
         * Hide/show the mailer name in the top-right corner.
         */
        'show_mailer' => true,

        /**
         * Set the preferred URL for the 'Back to Laravel' link
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
             * Store additional message data to the headers column.
             */
            'headers' => true,
        ],

        'exclude' => [
            /*
             * Exclude notifications to be stored in the database.
             */
            'notification' => [
                // '\Illuminate\Auth\Notifications\ResetPassword',
            ],

            /*
             * Exclude all notifications sent to certain email addresses to be stored in the database.
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
