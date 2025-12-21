<?php

namespace App\Models\LiveVox\Contracts;

use Illuminate\Database\Eloquent\Model;

abstract class LivevoxModel extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = $this->overrideTableName();
    }

    public function getConnectionName()
    {
        return 'livevox';
    }

    /**
     * Return a string representing the name of the table in livevox
     */
    abstract public function overrideTableName(): string;
}
