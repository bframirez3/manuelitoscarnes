<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/favicon.ico" />
    <title>Manuelito´s</title>
    <!-- Styles -->
    <style>
    </style>
</head>

<body class="antialiased">

    <body>
        <div id="panel"></div>
        <!-- @vite('resources/js/app.js')-->
        @vite('resources/js/panel.js')
    </body>
</body>

</html>