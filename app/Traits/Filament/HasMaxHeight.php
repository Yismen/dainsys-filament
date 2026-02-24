<?php

namespace App\Traits\Filament;

trait HasMaxHeight
{
    protected function getMaxHeight(): ?string
    {
        return '350px';
    }
}
