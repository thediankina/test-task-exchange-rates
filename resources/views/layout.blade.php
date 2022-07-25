<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset("css/script.css") }}">
    <link rel="stylesheet" type="text/css" href="{{ asset("css/widget.css") }}">
</head>
<body>
@yield('content')
<script>
    // Установка интервала запуска скрипта
    setInterval(function () {
        $.ajax({
            url: "/refresh",
            type: "GET",
            data: {
                "_token": "{{ csrf_token() }}",
            },
            success: function () {
                console.log("Values updated");
            },
        });
    }, 5 * 60 * 1000); // Каждые 5 минут
</script>
</body>
</html>
