<?php

namespace App\Http\Controllers;

use App\Models\Rate;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Контроллер для управления валютами
 */
class RateController extends Controller
{
    /**
     * Настройки отслеживания
     *
     * @var array
     */
    protected array $settings;

    /**
     * Конструктор, получающий настройки
     */
    public function __construct()
    {
        $sets = Rate::query()
            ->select(['id', 'track'])
            ->get()
            ->toArray();

        foreach ($sets as $set) {
            $this->settings[$set['id']] = $set['track'];
        }
    }

    /**
     * Главная страница настроек
     *
     * @return View
     */
    public function index(): View
    {
        $codes = Rate::query()
            ->select(['id', 'char_code'])
            ->get()
            ->toArray();

        return view('index', ['codes' => $codes]);
    }

    /**
     * Обновить список отслеживаемых курсов
     *
     * @param Request $request
     */
    public function update(Request $request)
    {
        if ($request->input('ids')) {
            $changes = array_flip($request->input('ids'));
            // Установка отслеживаемых валют
            $selected = array_map(fn($key) => 1, $changes);
            // Получить разницу между массивами
            //dd(array_diff($this->settings, $selected));
            $this->settings = array_merge($this->settings, $selected);
            Rate::query()
                ->whereIn('id', array_keys($selected))
                ->update(['track' => 1]);
        }
    }
}
