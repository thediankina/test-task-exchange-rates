<?php

namespace App\Http\Controllers;

use App\View\Components\Settings;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var Settings
     */
    protected Settings $component;

    /**
     * @var array
     */
    protected array $settings;

    /**
     * @var array
     */
    protected array $visibility;

    /**
     * Вывести главную страницу
     *
     * @return View
     */
    public function index(): View
    {
        return view('index');
    }

    /**
     * Конструктор
     *
     * @return void
     */
    public function __construct()
    {
        $this->component = new Settings();
        // Выдача настроек контроллерам из хранилищ
        $this->settings = $this->component::$settings;
        $this->visibility = $this->component::$visibility;
    }

    /**
     * Деструктор
     *
     * @return void
     */
    public function __destruct()
    {
        // Синхронизация настроек после любых действий
        $this->component::$settings = $this->settings;
        $this->component::$visibility = $this->visibility;
    }
}
