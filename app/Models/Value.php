<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Value extends Model
{
    /**
     * Название таблицы, соответствующей модели
     *
     * @var string
     */
    protected $table = 'rate_value';

    /**
     * Массово назначаемые атрибуты
     *
     * @var array
     */
    protected $fillable = ['id_rate', 'value'];

    /**
     * @var integer
     */
    public $visible;

    public function code(): HasOne
    {
        return $this->hasOne(Rate::class, 'id', 'id_rate');
    }
}
