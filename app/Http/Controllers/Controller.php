<?php

namespace App\Http\Controllers;

use App\View\Components\Settings;
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
     * Конструктор
     *
     * @return void
     */
    public function __construct()
    {
        $this->component = new Settings();
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
        $this->component::$settings = $this->settings;
        $this->component::$visibility = $this->visibility;
    }
}
