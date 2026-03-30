<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use ReflectionClass;

class MailingService
{
    protected static array $files = [];

    public static function toArray(): array
    {
        return self::getFiles();
    }

    protected static function getFiles(): array
    {
        return Cache::remember('mailing_files_list', now()->addDay(), function () {
            $path = app_path('Mail');
            $filesystem = new Filesystem;

            if ($filesystem->exists($path)) {
                foreach ($filesystem->allFiles($path) as $file) {
                    $namespace = str($file->getContents())->after('namespace ')->before(';')->trim()->__toString();
                    $class = "{$namespace}\\{$file->getFilenameWithoutExtension()}";
                    $reflection = new ReflectionClass($class);

                    if ($file->isFile() && $reflection->implementsInterface(Mailable::class)) {
                        self::$files[$class] = str($class)->afterLast('\\')->headline()->toString();
                    }
                }
            }

            return self::$files;
        });
    }

    public static function users(string|Mailable $mailable, bool $includeSuperAdmins = true): Collection
    {
        $mailableClass = $mailable instanceof Mailable ? get_class($mailable) : $mailable;

        $userIds = Cache::rememberForever(
            'mailing_subscriptions_for_mailable_'.$mailableClass,
            function () use ($mailableClass, $includeSuperAdmins): array {

                $users = User::query()
                    ->withWhereHas('mailables', function ($query) use ($mailableClass): void {
                        $query->where('name', $mailableClass);
                    })
                    ->get()
                    ->map(fn ($user): array => ['id' => (string) $user->id, 'name' => (string) $user->name])
                    ->values()
                    ->all();

                if (! $includeSuperAdmins) {
                    return $users;
                }

                $super_admins = User::query()
                    ->whereHas('roles', function ($query): void {
                        $query->where('name', 'like', 'super admin');
                    })
                    ->get()
                    ->map(fn ($user): array => ['id' => (string) $user->id, 'name' => (string) $user->name])
                    ->values()
                    ->all();

                $merged = array_merge($super_admins, $users);
                $unique = collect($merged)->unique('id')->all();

                return array_values($unique);
            }
        );

        return User::whereIn('id', collect($userIds)->pluck('id')->toArray())->get();
    }

    public static function subscribers(string|Mailable $mailable, bool $includeSuperAdmins = true): Collection
    {
        return self::users($mailable, $includeSuperAdmins);
    }
}
