<?php

namespace App\View\Components;

use App\Models\Rate;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Component;

class Widget extends Component
{
    /**
     * @var Collection
     */
    protected Collection $rates;

    /**
     * Создать экземпляр виджета
     *
     * @return void
     */
    public function __construct()
    {
        $json = Storage::disk('widget')->get('settings.js');
        $data = json_decode($json, true);
        $settings = array_keys($data, config('constants.selected'));
        $this->rates = Rate::all(['id', 'char_code'])->whereIn('id', $settings);
        //dd($this->rates);
    }

    /**
     * Отобразить представление для компонента
     *
     * @return View
     */
    public function render(): View
    {
        return view('widget.index');
    }

    /**
     * Привязка данных к представлению
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view): void
    {
        $view->with('rates', $this->rates);
    }
}
