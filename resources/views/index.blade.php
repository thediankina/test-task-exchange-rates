<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Настройка отслеживания</title>
</head>
<body>
<form method="post" id="tracking-form" action="settings/update">
    @csrf
    <div class="checkbox-codes">
        @foreach($codes as $code)
            <input type="checkbox" id="code" name="ids[]" value="{{ $code['id'] }}">
            <label for="code">{{ $code['char_code'] }}</label>
        @endforeach
    </div>
    <button type="submit">Выбрать</button>
</form>
</body>
</html>
