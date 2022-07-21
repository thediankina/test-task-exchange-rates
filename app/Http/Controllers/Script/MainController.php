<?php

namespace App\Http\Controllers\Script;

use App\Http\Controllers\Controller;
use App\Models\Rate;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Контроллер для управления скриптом
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

        return view('script.settings', ['codes' => $codes]);
    }

    /**
     * Внести изменения в список отслеживаемых
     *
     * @param Request $request
     * @return void
     */
    public function update(Request $request): void
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
}
