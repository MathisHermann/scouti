<!doctype html>
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @livewireStyles

    <title>Scouti</title>

</head>
<body class="bg-gray-100">
<div class="sm:mx-auto sm:w-full sm:max-w-md">
    <h2 class="mt-64 text-center text-3xl font-extrabold text-gray-900">
        Scouti
    </h2>
</div>
@yield('content')

@livewireScripts

<script src="{{ asset('js/app.js') }}"></script>

</body>
