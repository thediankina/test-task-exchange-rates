<?php

namespace App\View\Components;

use App\Models\Rate;
use App\Models\Value;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class Widget extends Component
{
    /**
     * @var Collection
     */
    protected Collection $rates;

    /**
     * Конструктор
     *
     * @return void
     */
    public function __construct()
    {
        // Получение настроек видимости
        $component = new Settings();
        $settings = $component::$visibility;

        // Вывод значений выбранных курсов валют
        $selected = array_keys($settings, config('constants.selected'));
        $this->rates = Rate::all()->whereIn('id', $selected);

        // Получение разницы между значениями курсов вчера и сегодня
        foreach ($this->rates as $rate) {
            $last = $rate->values->last();
            $rate->values->last = $last->value ?? config('constants.empty');
            $changes = Value::query()
                ->where('id_rate', '=', $rate->id)
                ->orderBy('created_at', 'desc')
                -> take(2)
                ->get()
                ->toArray();

            $values = array_column($changes, 'value');
            foreach ($values as $id => $value) {
                $values[$id] = Value::convert($value);
            }

            if (count($values) == 2) {
                // Определение знака
                $difference = $values[0] - $values[1];
                $sign = (($difference) <=> 0) == 1;
                $difference = strval(abs($difference));
                $rate->values->increasing = $sign;
                $rate->values->difference = Value::convert($difference);
            } else {
                // Если курс отслеживается впервые
                $rate->values->increasing = null;
                $rate->values->difference = config('constants.empty');
            }
        }
    }

    /**
     * Генерация вида
     *
     * @return View
     */
    public function render(): View
    {
        return view('widget.index', ['rates' => $this->rates]);
    }
}
