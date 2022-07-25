<?php

namespace App\View\Components;

use App\Models\Rate;
use App\Models\Value;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

/**
 * Компонент "Виджет курсов валют"
 */
class Widget extends Component
{
    /**
     * @var Collection
     */
    public Collection $rates;

    /**
     * Размер компонента
     *
     * @var string
     */
    public string $size;

    /**
     * Конструктор
     *
     * @param string $size
     *
     * @return void
     */
    public function __construct(string $size = "100%")
    {
        // Установка размера
        $this->size = $size;

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
                ->take(2)
                ->get()
                ->toArray();

            $values = array_column($changes, 'value');
            foreach ($values as $id => $value) {
                $values[$id] = Value::convert($value);
            }

            if (count($values) == 2) {
                // Определение знака
                $difference = $values[0] - $values[1];
                // ($difference) <=> 0) возвращает 1 (вверх), -1 (вниз) и 0 (не изменился)
                $sign = (($difference) <=> 0) == 1;
                // А $sign определяет положение стрелки
                if ((($difference) <=> 0) == 0) {
                    // Если значение не изменилось
                    $rate->values->increasing = null;
                    $rate->values->difference = config('constants.empty');
                } else {
                    $percent = round((abs($difference)/$values[1]) * 100, 2);
                    $difference = $percent . config('constants.percent');
                    $rate->values->increasing = $sign;
                    $rate->values->difference = Value::convert($difference);
                }
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
