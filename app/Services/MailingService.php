<?php

namespace App\Services;

use App\Models\User;
use ReflectionClass;
use App\Models\Mailing;
use Illuminate\Support\Collection;
use App\Models\MailingSubscription;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Mail\Mailable;

class MailingService
{
    protected static array $files;

    public static function toArray(): array
    {
        return Cache::rememberForever('sites_list', function () {
            return self::getFiles();
        });
    }

    protected static function getFiles()
    {
        $path = app_path('Mail');
        $filesystem = new Filesystem();

        if ($filesystem->exists($path)) {
            foreach ($filesystem->allFiles($path) as $file) {
                $namespace = str($file->getContents())->after('namespace ')->before(';')->trim()->__toString();
                $class = "{$namespace}\\{$file->getFilenameWithoutExtension()}";
                $reflection = new ReflectionClass($class);

                if ($file->isFile() && $reflection->implementsInterface(Mailable::class)) {
                    self::$files[$class] = $class;
                }
            }
        }

        return self::$files;
    }

    public static function users(string|Mailable $mailable): Collection
    {
        $mailable = $mailable instanceof Mailable ? get_class($mailable) : $mailable;

        $subscriptions =  MailingSubscription::where('mailable', $mailable)
            ->with('user')
            ->get();

        $users = $subscriptions->pluck('user');

        $super_admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'like', 'super admin');
        })->get();

        return $super_admins->merge($users);
    }

    public static function subscribers(string|Mailable $mailable): Collection
    {
        return self::users($mailable);
    }
}
