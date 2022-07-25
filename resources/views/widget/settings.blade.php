<div class="settings">
    <form method="post" id="visibility-form" action="widget/update">
        @csrf
        <div class="checkbox-codes">
            @foreach($rates as $id => $rate)
                <input type="checkbox" id="code" name="ids[]"
                       value="{{ $rate['id'] }}" {{ $rate['visible'] ? 'checked' : '' }}>
                <label for="code">{{ $rate['char_code'] }}</label>
            @endforeach
        </div>
        <div class="interval">
            <input type="range" id="slider" min="5" max="360" step="5">
            <br>
            <div class="interval-status">
                <label for="slider">Интервал обновления:&emsp;</label>
                <div id="current-interval"></div>
            </div>
        </div>
    </form>
    <script type="text/javascript">
        // Получение элементов ползунка и показателя текущего интервала
        let slider = document.getElementById("slider");
        let currentInterval = document.getElementById("current-interval");

        // Привести текущий интервал в читаемый вид
        convert(slider.value);

        // Фиксация значения интервала в ползунке
        slider.oninput = function() {
            convert(this.value);
        };

        // Автоматическое сохранение любых изменений в форме
        $('#visibility-form').on('change', function (event) {
            event.preventDefault();

            // Сбор идентификаторов выбранных валют
            let ids = [];
            $('input:checked').each(function () {
                ids.push(this.value);
            });

            let interval = slider.value;

            // Отправка через AJAX
            $.ajax({
                url: "/widget/update",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    ids: ids,
                    interval: interval,
                },
                success: function () {
                    $("#values").load("/ #values > *");
                },
            });
        });

        // Конвертация секунд в часы/минуты
        function convert(value) {
            let hours = Math.floor(parseInt(value) / 60);
            let minutes = parseInt(value) % 60;

            // Если интервал составляет больше часа
            if (parseInt(value) >= 60) {
                if (parseInt(value) >= 60 && minutes === 0) {
                    currentInterval.innerHTML = hours.toString() + " ч.";
                }
                if (parseInt(value) >= 60 && minutes !== 0) {
                    currentInterval.innerHTML = hours.toString() + " ч. " + minutes.toString() + " мин.";
                }
            }
            // Если меньше часа
            if (parseInt(value) < 60) {
                currentInterval.innerHTML = minutes.toString() + " мин.";
            }
        }
    </script>
</div>
