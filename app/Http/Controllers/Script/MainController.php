<?php

namespace App\Http\Controllers\Script;

use App\Http\Controllers\Controller;
use App\Models\Rate;
use App\Models\Value;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Контроллер для управления отслеживаемыми валютами
 */
class MainController extends Controller
{
    /**
     * Получить текущие настройки отслеживания
     *
     * @return View
     */
    public function index(): View
    {
        $codes = Rate::query()
            ->select(['id', 'char_code'])
            ->get()
            ->toArray();

        // Добавление признака отслеживания
        array_walk($codes, function ($code, $key) use (&$codes) {
            $trace = $this->settings[$code['id']] == 1 ? 1 : 0;
            $codes[$key]['trace'] = $trace;
        });

        return view('script/index', ['codes' => $codes]);
    }

    /**
     * Внести изменения в список отслеживаемых
     *
     * @param Request $request
     */
    public function update(Request $request)
    {
        // Разбор полученных данных
        $selected = array_flip($request->input('ids') ?? []);
        foreach ($this->settings as $id => $status) {
            $selected[$id] = array_key_exists($id, $selected) ? 1 : 0;
        }
        // Фиксация изменений
        $settings = array_diff_assoc($selected, $this->settings);

        // Применение к существующим настройкам
        $this->settings = array_merge($this->settings, $settings);
    }

    /**
     * Получить значения курсов
     *
     * @return View
     */
    public function show(): View
    {
        $values = Value::query()
            ->select('id', 'id_rate', 'value')
            ->get()
            ->toArray();

        return view('script/list', ['values' => $values]);
    }

    /**
     * Обновить значения
     *
     * @param Request $request
     */
    public function refresh(Request $request)
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
                    Value::query()
                        ->updateOrCreate(
                            [
                                'id_rate' => $id,
                                'created_at' => date('Y-m-d'),
                            ],
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
