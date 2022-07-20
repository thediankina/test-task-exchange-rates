<?php

namespace App\Http\Controllers\Widget;

use App\Http\Controllers\Controller;
use App\Models\Value;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Контроллер для управления виджетом
 */
class MainController extends Controller
{
    /**
     * Получить значения курсов
     *
     * @return View
     */
    public function index(): View
    {
        // Получение значений за сегодняшний день
        $values = Value::query()
            ->select(['id', 'id_rate', 'value'])
            ->where('created_at', '=', date('Y-m-d'))
            ->get();

        // Добавление признака видимости
        foreach ($values as $value) {
            $visible = $this->visibility[$value->id_rate] == 1 ? 1 : 0;
            $value->visible = $visible;
        }

        return view('index', ['values' => $values]);
    }

    /**
     * Внести изменения в список отображаемых
     *
     * @param Request $request
     * @return void
     */
    public function update(Request $request): void
    {
        // Разбор полученных данных
        $selected = array_flip($request->input('ids') ?? []);
        foreach ($this->visibility as $id => $status) {
            $selected[$id] = array_key_exists($id, $selected) ? 1 : 0;
        }
        // Фиксация изменений
        $settings = array_diff_assoc($selected, $this->visibility);

        // Применение к существующим настройкам
        $this->visibility = array_merge($this->visibility, $settings);
    }

    /**
     * Обновить значения
     *
     * @param Request $request
     * @return void
     */
    public function refresh(Request $request): void
    {
        // Обновление из XML
        $xml = simplexml_load_file(config('constants.source'));
        if ($xml) {
            // Получение разрешений на отслеживание
            $settings = array_filter($this->settings, function ($status) {
                return $status == config('constants.selected') ?? false;
            });
            $rates = $xml->children()->Valute;
            foreach ($rates as $rate) {
                $id = (string) $rate->attributes()->ID;
                // Проверка наличия разрешения на валюту
                if (array_key_exists($id, $settings)) {
                    $value = (string) $rate->children()->Value;
                    // Если нет значения за сегодня, то добавить, иначе обновить
                    $today = date('Y-m-d');
                    Value::query()
                        ->updateOrCreate(
                            ['id_rate' => $id, 'created_at' => $today],
                            ['value' => $value]
                        );
                }
            }
        } else {
            // Если источник недоступен
            abort(503);
        }
    }
}
