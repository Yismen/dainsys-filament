<?php

namespace App\Services\HC;

use App\Models\Site;
use Illuminate\Database\Eloquent\Model;

class BySite extends AbstractHCService
{
    protected function model(): Model
    {
        return new Site;
    }
}
