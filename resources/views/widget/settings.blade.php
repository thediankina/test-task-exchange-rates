<div class="settings">
    <p>Настройки видимости</p>
    <form method="post" id="visibility-form" action="widget/update">
        @csrf
        <div class="checkbox-codes">
            @foreach($rates as $id => $rate)
                <input type="checkbox" id="code" name="ids[]"
                       value="{{ $rate['id'] }}" {{ $rate['visible'] ? 'checked' : '' }}>
                <label for="code">{{ $rate['char_code'] }}</label>
            @endforeach
        </div>
        <button type="submit">Выбрать</button>
    </form>
    <a href="widget/refresh">Обновить</a>
    <script>
        $('#visibility-form').on('submit', function (event) {
            event.preventDefault();

            let ids = [];
            $('input:checked').each(function () {
                ids.push(this.value);
            });

            $.ajax({
                url: "/widget/update",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    ids: ids,
                },
            });
        });
    </script>
</div>
