<?php

use App\Services\NotificationChannelResolver;

it('uses database channel by default', function (): void {
    config()->set('notification_channels.mode', 'database_only');
    config()->set('notification_channels.overrides', []);

    $channels = app(NotificationChannelResolver::class)->resolve('tickets.created');

    expect($channels)->toBe(['database']);
});

it('uses both channels when global mode is both', function (): void {
    config()->set('notification_channels.mode', 'both');
    config()->set('notification_channels.overrides', []);

    $channels = app(NotificationChannelResolver::class)->resolve('tickets.created');

    expect($channels)->toBe(['database', 'mail']);
});

it('prefers per notification override over global mode', function (): void {
    config()->set('notification_channels.mode', 'database_only');
    config()->set('notification_channels.overrides.tickets.created', 'mail_only');

    $channels = app(NotificationChannelResolver::class)->resolve('tickets.created');

    expect($channels)->toBe(['mail']);
});
