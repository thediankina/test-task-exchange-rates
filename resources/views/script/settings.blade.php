@extends('layout')

@section('title')
    Настройки отслеживания
@endsection

@section('content')
    <a href="/" id="return">На главную</a>
    <form method="post" id="tracing-form" action="settings/update">
        @csrf
        <div class="checkbox-codes">
            @foreach($codes as $code)
                <div class="code">
                    <input type="checkbox" name="ids[]" value="{{ $code['id'] }}" {{ $code['trace'] ? 'checked' : '' }}>
                    <div class="label">
                        {{ $code['nominal'] }}&nbsp;{{ $code['char_code'] }}
                        <div class="name">{{ $code['name'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </form>
    <script type="text/javascript">
        $('#tracing-form').on('change', function (event) {
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
