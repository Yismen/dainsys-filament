<?php

namespace App\Models;

use App\Models\BaseModels\AppModel;
use App\Models\Traits\BelongsToClient;
use App\Models\Traits\BelongsToManager;
use App\Models\Traits\HasManyCampaigns;
use App\Models\Traits\HasManyEmployees;
use App\Models\Traits\HasManyHires;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends AppModel
{
    use BelongsToClient;
    use BelongsToManager;
    use HasManyCampaigns;
    use HasManyEmployees;
    use HasManyHires;
    use SoftDeletes;

    protected $fillable = ['name', 'client_id', 'manager_id', 'description', 'address', 'invoice_net_days'];

    public function invoiceAgents(): HasMany
    {
        return $this->hasMany(InvoiceAgent::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
