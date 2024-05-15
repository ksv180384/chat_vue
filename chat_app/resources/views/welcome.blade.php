<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/chat.css') }}">

        <title>Тестовый сайт</title>
        @vite(['resources/css/chat.css'])
    </head>
    <body>
        <div id="app"></div>

        @vite('resources/js/app.js')
    </body>
</html>
