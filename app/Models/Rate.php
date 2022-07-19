<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rate extends Model
{
    /**
     * Название таблицы, соответствующей модели
     * Заполнить с помощью ./vendor/bin/sail artisan db:seed --class=RateSeeder
     * @see RateSeeder
     *
     * @var string
     */
    protected $table = 'rate_information';

    /**
     * Не auto-increment id
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Тип данных для primary key
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Отключены метки времени updated_at и created_at
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Получить все значения валюты
     *
     * @return HasMany
     */
    public function values(): HasMany
    {
        return $this->hasMany(Value::class);
    }
}
