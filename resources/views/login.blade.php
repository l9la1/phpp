<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login pagina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <div class="container">
        <h2 class="text-center mt-4">Log hier in uw account</h2> <!-- Label above the form -->

        <form action="{{ route('login.store') }}" method="POST">

            @csrf
            <label for="emial">Email:</label>
            <input type="email" name="email" required>
            
            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>
        <p class="text-center mt-4">Nog geen account? <a href="{{ route('patients.create') }}">Registreer hier voor de wachtrij</a></p>
    </div>
</body>

