<?php

namespace App\Console\Commands;


use App\Jobs\RegenerateIuidForModelJob;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use ReflectionClass;

class RegenerateUuidsForModels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dainsys:regenerate-uuids-for-models';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if(app()->isProduction())
        {
            throw new \Exception('App is in production, this action is forbidden!');
            return self::FAILURE;
        }

        $modelsPath = app_path('Models');
        $trait = \Illuminate\Database\Eloquent\Concerns\HasUuids::class;

        foreach (File::files($modelsPath) as $file) {
            $class = $this->classFromFile($file->getRealPath());

            if (! class_exists($class)) {
                continue;
            }

            if (! is_subclass_of($class, Model::class)) {
                continue;
            }

            $reflection = new ReflectionClass($class);

            if (in_array($trait, $this->allTraits($reflection), true)) {
                $table = (new $class)->getTable();

                \dispatch(new RegenerateIuidForModelJob($table));

                $this->line($class);
            }
        }

        return self::SUCCESS;
    }

    protected function classFromFile(string $path): string
    {
        $contents = file_get_contents($path);

        preg_match('/namespace\s+(.+?);/', $contents, $ns);
        preg_match('/class\s+(\w+)/', $contents, $class);

        try {
            $full_class = $ns[1] . '\\' . $class[1];
        } catch (\Throwable $th) {
            dd($ns,  $class, $path);
        }

        return $full_class;
    }

    protected function allTraits(ReflectionClass $class): array
    {
        $traits = [];

        do {
            $traits += $class->getTraits();
        } while ($class = $class->getParentClass());

        foreach ($traits as $trait) {
            $traits += $trait->getTraits();
        }

        return array_keys($traits);
    }
}
