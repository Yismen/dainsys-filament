<?php

return [
    'mode' => env('NOTIFICATION_CHANNEL_MODE', 'database_only'),

    'overrides' => [
        'tickets.created' => env('NOTIFICATION_CHANNEL_TICKETS_CREATED'),
        'tickets.completed' => env('NOTIFICATION_CHANNEL_TICKETS_COMPLETED'),
        'tickets.assigned' => env('NOTIFICATION_CHANNEL_TICKETS_ASSIGNED'),
        'tickets.deleted' => env('NOTIFICATION_CHANNEL_TICKETS_DELETED'),
        'tickets.reopened' => env('NOTIFICATION_CHANNEL_TICKETS_REOPENED'),
        'tickets.reply_created' => env('NOTIFICATION_CHANNEL_TICKETS_REPLY_CREATED'),
        'hr_activity.created' => env('NOTIFICATION_CHANNEL_HR_ACTIVITY_CREATED'),
        'hr_activity.completed' => env('NOTIFICATION_CHANNEL_HR_ACTIVITY_COMPLETED'),
        'employees.hired' => env('NOTIFICATION_CHANNEL_EMPLOYEES_HIRED'),
        'employees.suspended' => env('NOTIFICATION_CHANNEL_EMPLOYEES_SUSPENDED'),
        'employees.reactivated' => env('NOTIFICATION_CHANNEL_EMPLOYEES_REACTIVATED'),
        'employees.terminated' => env('NOTIFICATION_CHANNEL_EMPLOYEES_TERMINATED'),
        'reports.birthdays' => env('NOTIFICATION_CHANNEL_REPORTS_BIRTHDAYS'),
        'reports.suspended_employees' => env('NOTIFICATION_CHANNEL_REPORTS_SUSPENDED_EMPLOYEES'),
        'reports.tickets_expired' => env('NOTIFICATION_CHANNEL_REPORTS_TICKETS_EXPIRED'),
        'reports.livevox_production' => env('NOTIFICATION_CHANNEL_REPORTS_LIVEVOX_PRODUCTION'),
    ],
];
