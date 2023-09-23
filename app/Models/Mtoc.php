<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mtoc extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'mtoc';
    protected $fillable = [
        'mtocd', 'model_code', 'type_code', 'option_code', 'color_code', 'color_name',
        'suggest_price'

    ];

    public function getMTOC()
    {
        return $this->model_code . $this->type_code . $this->option_code . $this->color_code;
    }

}
