<?php

namespace App\Services\Instances;

use App\Contracts\InstanceFromNameContract;
use InvalidArgumentException;
use Throwable;

class FromModel implements InstanceFromNameContract
{
    protected $namespace;

    public function __construct(string $namespace)
    {
        $this->namespace = $namespace;
    }

    public function get($class)
    {
        $string = str($class)->contains($this->namespace) ?
            $class :
            implode('\\', [
                $this->namespace,
                $class,
            ]);
        try {
            return new $string;
        } catch (Throwable $th) {
            throw new InvalidArgumentException("Class {$string} Not Found");
        }
    }
}
