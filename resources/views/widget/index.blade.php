<div class="widget" style="width: {{ $size }}">
    <div class="frame settings-buttons">
        <a id="available-icon" href="/settings"><img src="{{ asset("images/gear.svg") }}" alt=""></a>
        <a id="visible-icon"><img src="{{ asset("images/eye.svg") }}" alt=""></a>
    </div>
    <div class="frame rates-values" id="widget">
        <div id="values-component">
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
        <div id="settings-component">
            <x-settings></x-settings>
        </div>
    </div>
</div>
<script type="text/javascript">
    // Получение окна виджета, блоков значений и настроек
    let widget = document.getElementById("widget");
    let values = document.getElementById("values-component");
    let settings = document.getElementById("settings-component");

    // Установка значений по умолчанию
    removeAll(widget);
    widget.appendChild(values);

    let button = document.getElementById("visible-icon");

    // При нажатии на значок изменить вывод в окне
    button.addEventListener("click", function() {
        let child = widget.firstChild;

        widget.removeChild(child);
        widget.appendChild(child === settings ? values : settings);
    });

    // Очищение блока виджета
    function removeAll(parent) {
        while (parent.firstChild) {
            parent.removeChild(parent.firstChild);
        }
    }
</script>
