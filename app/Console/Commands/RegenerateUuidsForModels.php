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
    protected $signature = 'dainsys:regenerate-uuids-for-models {tables?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate uuids for all tables in the models folder. If a tables string is passed, it would only generate for that. Separate multiple tables by comma';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (app()->isProduction()) {
            throw new \Exception('App is in production, this action is forbidden!');

            return self::FAILURE;
        }

        $tables = $this->argument('tables') ?
            $this->parseTables() :
            $this->parseModels();

        foreach ($tables as $table) {
            \dispatch(new RegenerateIuidForModelJob($table));

            $this->line($table);
        }

        return self::SUCCESS;
    }

    protected function parseTables(): array
    {
        $tables = \collect(
            \explode(',', $this->argument('tables'))
        )->each(function ($table) {
            return \trim($table);
        });

        return $tables->toArray();
    }

    protected function parseModels(): array
    {
        $modelsPath = app_path('Models');

        $models = [];

        foreach (File::files($modelsPath) as $file) {
            $class = $this->classFromFile($file->getRealPath());

            if (! class_exists($class)) {
                continue;
            }

            if (! is_subclass_of($class, Model::class)) {
                continue;
            }

            if ($this->usesUuidTrait($class)) {
                $models[] = (new $class)->getTable();
            }
        }

        return $models;

    }

    protected function usesUuidTrait(string $class): bool
    {
        $trait = \Illuminate\Database\Eloquent\Concerns\HasUuids::class;

        $reflection = new ReflectionClass($class);

        return in_array($trait, $this->allTraits($reflection), true);

    }

    protected function classFromFile(string $path): string
    {
        $contents = file_get_contents($path);

        preg_match('/namespace\s+(.+?);/', $contents, $ns);
        preg_match('/class\s+(\w+)/', $contents, $class);

        return $ns[1].'\\'.$class[1];
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
