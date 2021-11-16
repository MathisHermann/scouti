<!doctype html>
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @livewireStyles
    <style>
        [x-cloak] {display: none;}
    </style>
    <title>Scouti</title>

</head>
<body class="bg-gray-100">

@yield('content')

@livewireScripts

<script src="{{ asset('js/app.js') }}"></script>

</body>
