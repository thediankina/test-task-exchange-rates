<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
