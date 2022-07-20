<?php

namespace App\Http\Controllers;

use App\Models\Rate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Настройки отслеживания
     *
     * @var array
     */
    protected array $settings;

    /**
     * Настройки видимости
     *
     * @var array
     */
    protected array $visibility;

    /**
     * @var array
     */
    private array $ids;

    /**
     * Конструктор
     *
     * @return void
     */
    public function __construct()
    {
        if (!$this->get('script')) {
            $this->settings = $this->init('script');
        } else {
            $this->settings = $this->get('script');
        }

        if (!$this->get('widget')) {
            $this->visibility = $this->init('widget');
        } else {
            $this->visibility = $this->get('widget');
        }
    }

    /**
     * Деструктор
     *
     * @return void
     */
    public function __destruct()
    {
        $this->put('script', $this->settings);
        $this->put('widget', $this->visibility);

        unset($this->settings);
        unset($this->visibility);
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
        return $json ? json_decode($json, true) : false;
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
     * @param string $disk
     * @param array $settings
     * @return array
     */
    public function init(string $disk, array $settings = []): array
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
}
