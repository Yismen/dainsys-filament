<?php

namespace App\Services;

class NotificationChannelResolver
{
    /**
     * @return array<int, string>
     */
    public function resolve(string $key): array
    {
        $mode = $this->modeFor($key);

        return match ($mode) {
            'mail_only' => ['mail'],
            'both' => ['database', 'mail'],
            default => ['database'],
        };
    }

    private function modeFor(string $key): string
    {
        $override = config("notification_channels.overrides.{$key}");

        if (is_string($override) && in_array($override, ['database_only', 'mail_only', 'both'], true)) {
            return $override;
        }

        $mode = config('notification_channels.mode', 'database_only');

        if (! is_string($mode) || ! in_array($mode, ['database_only', 'mail_only', 'both'], true)) {
            return 'database_only';
        }

        return $mode;
    }
}
