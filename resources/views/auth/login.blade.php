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
    <div class="">
        <form action="{{ route('sipkl.login') }}" method="POST">
            <div>
                <div class="input-group">
                    <label class="input-label" for="">Email/Username</label>
                    <input name="username" class="input" type="text" placeholder="Masukkan Email/Username" required>
                </div>
                <div class="input-group">
                    <label class="input-label" for="">Password</label>
                    <input name="password" class="input" type="password" placeholder="*****" required>
                </div>
            </div>
            <button type="submit" class="btn btn-xs btn-default-fill">Login</button>
        </form>
    </div>
</body>

</html>
