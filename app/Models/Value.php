<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Модель таблицы значений курсов валют
 */
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
     * Последнее актуальное значение
     *
     * @var string
     */
    public string $last;

    /**
     * Признак увеличения значения по отношению к предыдущему дню
     *
     * @var bool|null
     */
    public bool|null $increasing;

    /**
     * Числовое изменение значения по отношению к предыдущему дню
     *
     * @var string
     */
    public string $difference;

    /**
     * Получение названия валюты
     *
     * @return HasOne
     */
    public function code(): HasOne
    {
        return $this->hasOne(Rate::class, 'id', 'id_rate');
    }

    /**
     * Конвертация строки в вещественное число и наоборот
     *
     * @param string $value
     * @return float|string
     */
    public static function convert(string $value): float|string
    {
         if (count(explode(",", $value)) < 2) {
             $parts = explode(".", $value);
             return implode(",", $parts);
         } else {
             $parts = explode(",", $value);
             return floatval(implode(".", $parts));
         }
    }
}
