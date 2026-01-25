<?php

namespace App\Services;

use App\Models\User;
use ReflectionClass;
use Illuminate\Support\Collection;
use App\Models\MailingSubscription;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Mail\Mailable;
use App\Models\Mailable as MailableModel;

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
        return Cache::rememberForever(
            'mailing_subscriptions_for_mailable_'.$mailableClass,
            function () use($mailableClass, $includeSuperAdmins): Collection  {

                $users = $mailable = MailableModel::query()
                    ->where('name', $mailableClass)
                    ->with(['users'])
                    ->first()
                    ->users;

                if (! $includeSuperAdmins) {
                    return $users;
                }

                $super_admins = User::query()
                    ->whereHas('roles', function ($query) {
                        $query->where('name', 'like', 'super admin');
                    })
                    ->get();

            return $super_admins
                ->merge($users)
                ->reject(fn($user) => $user === null);
        });
    }

    public static function subscribers(string|Mailable $mailable, bool $includeSuperAdmins = false): Collection
    {
        return self::users($mailable, $includeSuperAdmins);
    }
}
