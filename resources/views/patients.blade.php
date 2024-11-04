<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welkom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

    <div class="container">
        <h1 class="header-title ">Welkom {{ $patient->name }}</h1>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header bg-success text-white text-center">
                    <h5 class="mb-0">Facatures</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Huur kosten</th>
                                    <th>Verzorgings kosten</th>
                                    <th>Totaal</th>
                                    <th>betaald</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($financial as $fin)
                                    <tr>
                                        <td>€{{ number_format($fin->hire_cost, 2, ',', '.') }}</td>
                                        <td>€{{ number_format($fin->caretaking_costs, 2, ',', '.') }}</td>
                                        <td>€{{ number_format($fin->caretaking_costs + $fin->hire_cost, 2, ',', '.') }}
                                        </td>
                                        <td>
                                            @if ($fin->payed == 0)
                                                <button onclick="payed({{ $fin->id }})"
                                                    class="btn btn-success">Betaal</button>
                                            @else
                                                Ja
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card">
                <div class="card-header bg-success text-white text-center">
                    <h5 class="mb-0">Upcoming Appointments</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Wanneer</th>
                                    <th>Arts</th>
                                    <th>Voor wat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($appointments as $ap)
                                    <tr>
                                        <td>{{ $ap->appointment_date }}</td>
                                        <td>{{ $ap->doc->name }}</td>
                                        <td>{{ $ap->reason }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"
        integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        function payed(id) {
            fetch("/api/patient/setPayed/" + id).then(() => {
                location.reload();
            });
        }
    </script>
</body>

</html>
