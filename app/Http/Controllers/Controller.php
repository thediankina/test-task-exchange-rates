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
     * Конструктор
     */
    public function __construct()
    {
        if (!isset($this->settings)) {
            $ids = Rate::query()
                ->select('id')
                ->get()
                ->toArray();

            array_walk_recursive($ids, function ($id) {
                $this->settings[$id] = config('constants.unselected');
            });
        }

        $json = Storage::disk('script')->get('settings.js');
        $this->settings = json_decode($json, true);
    }

    /**
     * Деструктор
     */
    public function __destruct()
    {
        $json = json_encode($this->settings);
        Storage::disk('script')->put('settings.js', $json);

        unset($this->settings);
    }
}
