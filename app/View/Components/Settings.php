<?php

namespace App\View\Components;

use App\Models\Rate;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Component;

/**
 * Компонент "Настройки"
 */
class Settings extends Component
{
    /**
     * Настройки отслеживания
     *
     * @var array
     */
    public static array $settings;

    /**
     * Настройки видимости
     *
     * @var array
     */
    public static array $visibility;

    /**
     * @var array
     */
    private array $ids;

    /**
     * Checkbox настроек видимости
     *
     * @var array
     */
    public array $rates;

    /**
     * Конструктор
     *
     * @return void
     */
    public function __construct()
    {
        self::$settings = $this->get('script') ?? $this->init();
        self::$visibility = $this->get('widget') ?? $this->init();

        // Получение доступных валют
        $available = array_filter(self::$settings, function ($status) {
            return ($status == config('constants.selected'));
        });

        $this->rates = Rate::all()
            ->whereIn('id', array_keys($available))
            ->toArray();

        // Получение и отметка видимости валют
        $visible = array_filter(self::$visibility, function ($status) {
            return ($status == config('constants.selected'));
        });

        foreach ($this->rates as $id => $rate) {
            $this->rates[$id]['visible'] = array_key_exists($rate['id'], $visible) ? 1 : 0;
        }
    }

    /**
     * Деструктор
     *
     * @return void
     */
    public function __destruct()
    {
        $this->put('script', self::$settings);
        $this->put('widget', self::$visibility);
    }

    /**
     * Получить настройки из файла
     *
     * @param string $disk
     * @return mixed
     */
    private function get(string $disk): mixed
    {
        $json = Storage::disk($disk)->get('settings.js');
        return is_null($json) ? null : json_decode($json, true);
    }

    /**
     * Сохранить настройки
     *
     * @param string $disk
     * @param array $settings
     * @return void
     */
    private function put(string $disk, array $settings): void
    {
        $json = json_encode($settings);
        Storage::disk($disk)->put('settings.js', $json);
    }

    /**
     * Инициализация настроек
     *
     * @param array $settings
     * @return array
     */
    public function init(array $settings = []): array
    {
        if (!isset($this->ids)) {
            $this->ids = Rate::query()
                ->select('id')
                ->get()
                ->toArray();
        }

        array_walk_recursive($this->ids, function ($id) use (&$settings) {
            $settings[$id] = config('constants.unselected');
        });

        return $settings;
    }

    /**
     * @return View
     */
    public function render(): View
    {
        return view('widget.settings');
    }
}
