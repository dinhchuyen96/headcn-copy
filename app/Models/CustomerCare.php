<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Receipt;
use Illuminate\Support\Facades\Log;
use App\Enum\EOrder;

class CustomerCare extends Model
{

    use HasFactory;

    protected $fillable = [
        'customer_id',
        'contact_type_id',

    ];
}
