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
     * Интервал обновления (виджета)
     *
     * @var int
     */
    public static int $interval;

    /**
     * Идентификаторы валют
     *
     * @var array
     */
    private array $ids;

    /**
     * Настройки видимости (для checkbox)
     *
     * @var array
     */
    public array $rates;

    /**
     * Текущее значение интервала
     *
     * @var int
     */
    public int $current;

    /**
     * Конструктор
     *
     * @return void
     */
    public function __construct()
    {
        // Получение интервала
        $default = config('constants.interval');
        $interval = $this->get('widget', 'interval.js');
        self::$interval = intval(is_null($interval) ? $default : json_decode($interval));

        // Получение/инициализация настроек
        self::$settings = $this->get('script', 'settings.js') ?? $this->init();
        self::$visibility = $this->get('widget', 'settings.js') ?? $this->init();

        // Отметка текущего интервала
        $this->current = self::$interval;

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
        // Сохранение текущих настроек
        $this->put('script', self::$settings, 'settings.js');
        $this->put('widget', self::$visibility, 'settings.js');

        // Сохранение текущего интервала
        $this->put('widget', self::$interval, 'interval.js');
    }

    /**
     * Получить настройки из файла
     *
     * @param string $disk
     * @param string $filename
     * @return mixed
     */
    private function get(string $disk, string $filename): mixed
    {
        $json = Storage::disk($disk)->get($filename);
        return is_null($json) ? null : json_decode($json, true);
    }

    /**
     * Сохранить настройки
     *
     * @param string $disk
     * @param array|int $settings
     * @param string $filename
     * @return void
     */
    private function put(string $disk, array|int $settings, string $filename): void
    {
        $json = json_encode($settings);
        Storage::disk($disk)->put($filename, $json);
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
