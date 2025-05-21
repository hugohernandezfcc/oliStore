<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

        <meta name="description" content="{{ $description }}" />
        <meta property="og:title" content="{{ $title }}" />
        <meta property="og:url" content="{{ $wa }}" />
        <meta property="og:description" content="{{ $description }}" />
        <meta property="og:image" content="{{ $image }}" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->

    </head>
    <body class="font-sans antialiased" onload="window.location.href = '{{ $wa }}';">
    </body>
    <style>
        button {
            touch-action: manipulation !important;
        }
    </style>
</html>
