<?php

namespace App\Filament\Traits;

use Illuminate\Support\Arr;

trait HasColors
{
    protected array $colors = [
        '#FF6384', // Red
        '#36A2EB', // Blue
        '#FFCE56', // Yellow
        '#4BC0C0', // Aqua
        '#9966FF', // Purple
        '#FF9F40', // Orange
        '#77DD77', // Green
        '#FFD700', // Gold
        '#FF6347', // Tomato
        '#20B2AA',  // Light Sea Green
        '#05C4D3',
        '#E7EB7A',
        '#BC7DFA',
        '#56FE46',
        '#BB59A3',
        '#6F34F9',
        '#905B04',
        '#99325E',
        '#E28AF0',
        '#C714B8',
        '#19FD95',
        '#B58D6E',
        '#457B68',
        '#5913A4',
        '#962941',
        '#745A28',
    ];

    public function getColorByIndex(int $index)
    {
        return array_key_exists($index, $this->colors) ? $this->colors[$index] : Arr::random($this->colors);
    }

    public function getRandomColor()
    {
        return Arr::random($this->colors);
    }

    public function getManyColors(int $count): array
    {
        return array_slice($this->colors, 0, $count);
    }
}
