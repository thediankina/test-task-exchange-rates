<div class="widget" style="width: {{ $size }}">
    <div class="frame settings-buttons">
        <a id="available-icon" href="/settings"><img src="{{ asset("images/gear.svg") }}" alt=""></a>
        <a id="visible-icon"><img src="{{ asset("images/eye.svg") }}" alt=""></a>
    </div>
    <div class="frame widget-components">
        <!-- "Матрешка" для корректного отображения после AJAX обновления -->
        <div id="values-component">
            <!-- Таблица значений -->
            <div id="values">
                @foreach($rates as $rate)
                    <div class="row">
                        <div class="cell">
                            <div class="rate">
                                {{ $rate->nominal }}&nbsp;{{ $rate->char_code }}<br>
                                <div class="name">{{ $rate->name }}</div>
                            </div>
                        </div>
                        <div class="cell">
                            <div class="value">
                                {{ $rate->values->last }}
                            </div>
                        </div>
                        <div class="cell">
                            @if($rate->values->increasing)
                                <div style="color: darkgreen">&uarr;</div>
                            @else
                                @if(is_null($rate->values->increasing))
                                    &nbsp;
                                @else
                                    <div style="color: darkred">&darr;</div>
                                @endif
                            @endif
                        </div>
                        <div class="cell">
                            <div class="percentage">
                                &nbsp;{{ $rate->values->difference }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <!-- Настройки видимости -->
        <div id="settings-component" hidden>
            <!-- Подключение представления компонента настроек -->
            <x-settings></x-settings>
        </div>
    </div>
    <script type="text/javascript">
        // Дать возможность переключать вид
        render();

        // Отключить кеширование
        $.ajaxSetup({
            cache: false
        });

        // Взять значение интервала из ползунка
        let interval = slider.value * 60 * 1000;

        // Установка интервала обновления содержимого
        setInterval(function () {
            // Перезагрузить данные блока #values
            $("#values").load("/ #values > *");
            console.log("Widget refreshed");
        }, interval);

        // Отображение виджета
        function render() {
            // Получение блоков значений и настроек
            let values = document.getElementById("values-component");
            let settings = document.getElementById("settings-component");

            // Кнопка для смены вывода
            let button = document.getElementById("visible-icon");
            // При нажатии на кнопку изменить вывод в окне виджета
            button.addEventListener("click", function () {
                if (settings.hidden && !values.hidden) {
                    settings.hidden = false;
                    values.hidden = true;
                } else {
                    if (values.hidden && !settings.hidden) {
                        values.hidden = false;
                        settings.hidden = true;
                    }
                }
            });
        }

        // Отображение виджета (old)
        /*function renderWidget(clone) {
            // Получение окна виджета, блоков значений и настроек
            let container = document.getElementById("container");
            let values = document.getElementById("values-component");
            let settings = document.getElementById("settings-component");


            // Установка значений по умолчанию
            removeAll(container);
            container.appendChild(values);

            let button = document.getElementById("visible-icon");

            // При нажатии на значок изменить вывод в окне
            button.addEventListener("click", function () {
                let child = container.firstChild;

                container.removeChild(child);
                container.appendChild(child === settings ? values : settings);
            });
        }

        // Очищение блока виджета
        function removeAll(parent) {
            while (parent.firstChild) {
                parent.removeChild(parent.firstChild);
            }
        }*/
    </script>
</div>
