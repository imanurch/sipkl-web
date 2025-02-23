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
    <div class="grid grid-cols-12 h-screen md:overflow-hidden">
        <div class="col-span-12 md:col-span-4">
            <div class="space-y-20 py-12 sm:py-24 px-12">
                <div class="flex space-x-2 place-items-center justify-center">
                    <img class="size-8" src="{{ url('storage/images/logo.png') }}" alt="">
                    <h3 class="text-xl-bold">SIPKL</h3>
                </div>
                <div class="w-full space-y-10">
                    <div class="text-center space-y-2">
                        <h1 class="text-md-medium">Selamat Datang!</h1>
                        <h6 class="text-sm text-neutral-300">Log In untuk melanjutkan ke SIPKL</h6>
                    </div>
                    <form action="{{ route('sipkl.login') }}" method="POST">
                        <div class="space-y-6">
                            <div class="space-y-6">
                                @csrf
                                <div class="input-group">
                                    <label class="input-label" for="">Email/Username</label>
                                    <input name="username" class="input" type="text"
                                        placeholder="Masukkan Email/Username" required>
                                </div>
                                <div class="input-group">
                                    <label class="input-label" for="">Password</label>
                                    <input name="password" class="input" type="password"
                                        placeholder="Masukkan Password" required>
                                </div>
                                <div class="flex space-x-2 place-items-center">
                                    <input type="checkbox" name="remember" id="">
                                    <label for="" class="input-label">Ingat Saya</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-xs btn-default-fill w-full">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="relative col-span-12 md:col-span-8">
            <div class="absolute inset-0 bg-brand-900 bg-opacity-80"></div>
            <div class="absolute inset-0 translate-y-1/2 translate-x-16 space-y-4">
                <p class="text-xl-semibold text-neutral-0 italic">"Sukses tidak diberikan, tapi diperoleh. <br>Kamu
                    harus bekerja keras untuk mendapatkannya."</p>
                <p class="text-md text-neutral-0">- The Rock -</p>
            </div>
            <img class="object-none object-top  h-full w-full" src="{{ url('storage/images/school.jpg') }}"
                alt="">
        </div>
    </div>

</body>

</html>
