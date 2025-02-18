<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>SIPKL</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="layout relative">
        <div class="layout-sidebar">
            {{-- pake if else buat checking role --}}
            @include('components.sidebar.admin_sidebar')
        </div>
        <div class="layout-content">
            <div class="flex justify-between place-items-center">
                <h1 class="display-xs-bold">@yield('page-title')</h1>
                <div class="flex space-x-2 place-items-center bg-neutral-50 rounded py-2 px-5 text-xs-medium">
                    <img class="size-5" src="" alt=" ">
                    <span>@yield('profil')</span>
                </div>
            </div>
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>
