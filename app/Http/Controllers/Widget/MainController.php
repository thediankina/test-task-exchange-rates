<?php

namespace App\Http\Controllers\Widget;

use App\Http\Controllers\Controller;
use App\View\Components\Settings;
use Illuminate\Http\Request;

/**
 * Контроллер для управления виджетом
 */
class MainController extends Controller
{
    /**
     * Внести изменения в список отображаемых
     * и/или интервал обновления в виджете
     *
     * @param Request $request
     *
     * @return void
     */
    public function update(Request $request): void
    {
        // Разбор полученных данных
        $selected = array_flip($request->input('ids') ?? []);
        $interval = (int) $request->input('interval') ?? '';

        foreach ($this->visibility as $id => $status) {
            $selected[$id] = array_key_exists($id, $selected) ? 1 : 0;
        }

        // Фиксация изменений
        $settings = array_diff_assoc($selected, $this->visibility);

        // Применение к существующим настройкам
        $this->visibility = array_merge($this->visibility, $settings);
        Settings::$interval = $interval;
    }
}
