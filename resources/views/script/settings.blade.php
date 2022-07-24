@extends('layout')

@section('title')
    Настройки отслеживания
@endsection

@section('content')
    <p>Настройки отслеживания</p>
    <form method="post" id="tracing-form" action="settings/update">
        @csrf
        <div class="checkbox-codes">
            @foreach($codes as $code)
                <input type="checkbox" id="code" name="ids[]"
                       value="{{ $code['id'] }}" {{ $code['trace'] ? 'checked' : '' }}>
                <label for="code">{{ $code['char_code'] }}</label>
            @endforeach
        </div>
        <button type="submit">Выбрать</button>
    </form>

    <a href="/">Widget</a>
    <script type="text/javascript">
        $('#tracing-form').on('submit', function (event) {
            event.preventDefault();

            let ids = [];
            $('input:checked').each(function () {
                ids.push(this.value);
            });

            $.ajax({
                url: "/settings/update",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    ids: ids,
                },
            });
        });
    </script>
@endsection
