<?php

namespace App\Http\Controllers\Script;

use App\Http\Controllers\Controller;
use App\Models\Rate;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Контроллер для управления отслеживаемыми валютами
 */
class MainController extends Controller
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
     * Получить текущие настройки
     *
     * @return View
     */
    public function index(): View
    {
        $codes = Rate::query()
            ->select(['id', 'char_code', 'track'])
            ->get()
            ->toArray();

        return view('script/index', ['codes' => $codes]);
    }

    /**
     * Изменить список отслеживаемых валют
     *
     * @param Request $request
     */
    public function update(Request $request)
    {
        $selected = array_flip($request->input('ids') ?? []);

        // Отметка выбранных валют
        // $selected = array_map(fn($key) => 1, $changes);

        foreach ($this->settings as $id => $status) {
            $selected[$id] = array_key_exists($id, $selected) ? 1 : 0;
        }

        // Фиксация изменений отметок
        $settings = array_diff_assoc($selected, $this->settings);

        // Применение к существующим настройкам
        $this->settings = array_merge($this->settings, $settings);
        foreach ($settings as $id => $status) {
            Rate::query()
                ->where('id', '=', $id)
                ->update(['track' => $this->settings[$id]]);
        }
    }
}
