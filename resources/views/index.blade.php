@extends('layout')

@section('title')
    Значения выбранных курсов
@endsection

@section('content')
    <a href="/settings">Настройки</a>
    <x-widget>
        Widget is here
    </x-widget>
    <h3>Настройки видимости</h3>
    <form method="post" id="visibility-form" action="widget/update">
        @csrf
        <div class="checkbox-codes">
            @foreach($values as $value)
                <input type="checkbox" id="code" name="ids[]"
                       value="{{ $value->id_rate }}" {{ $value->visible ? 'checked' : '' }}>
                <label for="code">{{ $value->code->char_code }}</label>
            @endforeach
        </div>
        <button type="submit">Выбрать</button>
    </form>
    <a href="/refresh">Обновить</a>
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
@endsection
