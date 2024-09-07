<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
{{--        Montserrat Font --}}
        <link
            href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap"
            rel="stylesheet"
        />

        @viteReactRefresh
        @vite(['resources/js/app.tsx', 'resources/scss/app.scss'])
    </head>
    <body class="font-sans antialiased bg-gray-800 text-white p-4">
        <div id="app"></div>
    </body>
</html>
