<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Patient registratie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <div class="container">
        <h2 class="text-center mt-4">Registreer u voor de wachtrij</h2> <!-- Label above the form -->

        <form action="{{ route('patients.store') }}" method="POST">
            @csrf
            <label for="name">Naam:</label>
            <input type="text" name="name" required>
            
            <label for="address">Addres:</label>
            <input type="text" name="address" required>
            
            <label for="phonenumber">Telefoon Nummer:</label>
            <input type="text" name="phonenumber" required>

            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="date_of_birth">Geboorte datum:</label>
            <input type="date" name="date_of_birth" required>

            <label for="password">Wachtwoord:</label>
            <input type="password" name="password" required>

            <button type="submit">Registreer</button>
        </form>
    </div>
</body>

