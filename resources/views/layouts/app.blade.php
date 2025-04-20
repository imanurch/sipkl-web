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

{{-- {!! Flasher\Laravel\LaravelFacade::render() !!} --}}
{!! Flasher::render() !!}

<body>
    <div x-data="{ sidebarSM: false }" class="layout relative">
        <div class="layout-sidebar sm:fixed sm:block h-screen overflow-y-auto"
            :class="sidebarSM == true ? 'fixed z-50 inset-y-0 left-0' : 'hidden'">
            @include('components.sidebar.sidebar')
        </div>
        <div class="w-full max-w-full overflow-x-hidden sm:ms-56">
            <div class="h-14 border-b border-neutral-50 px-9 flex justify-end place-items-center">
                <div class="sm:hidden cursor-pointer absolute z-50 " :class="sidebarSM == true ? 'start-64' : 'start-8'">
                    <svg @click="sidebarSM=!sidebarSM" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 7L4 7" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M20 12L4 12" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M20 17L4 17" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                </div>
                <div x-data="{ userMenu: false }" class="relative h-full place-content-center w-fit">
                    <div @click="userMenu=!userMenu"
                        class="cursor-pointer h-full flex space-x-2 place-items-center text-xs-medium">
                        {{-- <div class="bg-neutral-50 p-1.5 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"
                                fill="none">
                                <path
                                    d="M15 15.75C15 14.7033 15 14.18 14.8708 13.7541C14.58 12.7953 13.8297 12.045 12.8709 11.7542C12.445 11.625 11.9217 11.625 10.875 11.625H7.125C6.07833 11.625 5.55499 11.625 5.12914 11.7542C4.17034 12.045 3.42003 12.7953 3.12918 13.7541C3 14.18 3 14.7033 3 15.75M12.375 5.625C12.375 7.48896 10.864 9 9 9C7.13604 9 5.625 7.48896 5.625 5.625C5.625 3.76104 7.13604 2.25 9 2.25C10.864 2.25 12.375 3.76104 12.375 5.625Z"
                                    stroke="black" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div> --}}
                        {{-- <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="9" r="3" stroke="#1C274C" stroke-width="1" />
                            <circle cx="12" cy="12" r="10" stroke="#1C274C" stroke-width="1" />
                            <path
                                d="M17.9691 20C17.81 17.1085 16.9247 15 11.9999 15C7.07521 15 6.18991 17.1085 6.03076 20"
                                stroke="#1C274C" stroke-width="1" stroke-linecap="round" />
                        </svg> --}}
                        <span class="w-20 truncate">{{ session('user_bio')->name }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                            fill="none">
                            <path d="M5 7.5L10 12.5L15 7.5" stroke="#667085" stroke-width="0.933333"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <div x-show="userMenu" @click.away="userMenu=false"
                        class="bg-neutral-0 border border-neutral-100 rounded absolute p-1">
                        <a href="{{ auth()->user()->role == 'admin' ? route('admin.account') : (auth()->user()->role == 'advisor' ? route('advisor.account') : route('student.account')) }}"
                            class="user-menu">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"
                                fill="none">
                                <path
                                    d="M15 15.75C15 14.7033 15 14.18 14.8708 13.7541C14.58 12.7953 13.8297 12.045 12.8709 11.7542C12.445 11.625 11.9217 11.625 10.875 11.625H7.125C6.07833 11.625 5.55499 11.625 5.12914 11.7542C4.17034 12.045 3.42003 12.7953 3.12918 13.7541C3 14.18 3 14.7033 3 15.75M12.375 5.625C12.375 7.48896 10.864 9 9 9C7.13604 9 5.625 7.48896 5.625 5.625C5.625 3.76104 7.13604 2.25 9 2.25C10.864 2.25 12.375 3.76104 12.375 5.625Z"
                                    stroke="black" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <p>Akun</p>
                        </a>
                        <a href="{{ route('sipkl.logout') }}" class="user-menu">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14"
                                fill="none">
                                <path
                                    d="M10.5001 4.66667L12.8334 7M12.8334 7L10.5001 9.33333M12.8334 7H5.25008M8.75008 2.45236C8.00648 2.00566 7.14314 1.75 6.2223 1.75C3.4302 1.75 1.16675 4.1005 1.16675 7C1.16675 9.89949 3.4302 12.25 6.2223 12.25C7.14314 12.25 8.00648 11.9943 8.75008 11.5476"
                                    stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <p class="whitespace-nowrap">Log Out</p>
                        </a>
                    </div>
                </div>
            </div>
            <div class="layout-content">
                <h1 class="text-xl-bold">@yield('page-title')</h1>

                {{-- <div class="flex justify-between place-items-center">
                    <h1 class="display-xs-bold">@yield('page-title')</h1>
                    <div class="flex space-x-2 place-items-center bg-neutral-50 rounded py-2 px-5 text-xs-medium">
                        <img class="size-5" src="" alt=" ">
                        <span>{{ session('user_bio')->name }}</span>
                        <span>@yield('profil')</span>
                    </div>
                </div> --}}
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>
