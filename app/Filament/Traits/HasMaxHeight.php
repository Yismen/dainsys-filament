<?php

namespace App\Filament\Traits;

trait HasMaxHeight
{
    protected function getMaxHeight(): ?string
    {
        return '350px';
    }
}
