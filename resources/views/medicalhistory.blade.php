<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Patient | @if (isset($patient))
            {{ $patient->name }}
        @else
            Medisch dosier
        @endif
    </title>
</head>

<body>
    @if (isset($patient))
        <div class="row">
            <div class="col-6">
                <table class="table table-bordered table-hover table-striped text-center align-middle" id="pTable">
                    <thead class="thead-dark">
                        <tr>
                            <th>Datum</th>
                            <th>info</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($medicalhistory as $mh)
                            <tr>
                                <td><strong>{{ $mh->date }}</strong></td>
                                <td> {{ $mh->info }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-6">
                <form method="post" action="addInformation">
                    @csrf
                    <input type="hidden" name="id" value="{{ $patient->id }}" />
                    <div class="card">
                        <div class="card-header">Voeg informatie toe</div>
                        <div class="card-body">
                            @error('info')
                                <p class="alert alert-danger">{{ $message }}</p>
                            @enderror
                            <textarea class="w-100  form-control" name="info" placeholder="Vul informatie over {{ $patient->name }}"
                                maxlength="255" minlength="10"></textarea>
                        </div>
                        <div class="card-footer">
                            <input type="submit" value="Voeg toe" class="btn btn-primary btn-small w-100" />
                        </div>
                    </div>
                </form>
            </div>
        @else
            <input type="text" oninput="searchPatient($(this).val())" class="form-control w-100">
            <table class="table table-bordered table-hover table-striped text-center align-middle" id="patient">
                <thead class="thead-dark">
                    <tr>
                        <th>Patient naam</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($patients as $pt)
                        <tr>
                            <td>{{ $pt->name }}</td>
                            <td><a href="/medical/{{ $pt->id }}" class="btn btn-primary btn-bg"><i
                                        class="bi bi-file-earmark-medical-fill"></i></a>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <script>
                // Search for patient in table patient
                function searchPatient(input) {
                    var table = $("#patient");
                    var tr = table.find("tr");
                    for (var i = 0; i < tr.length; i++) {
                        var td = $(tr[i]).children();
                        for (var j = 0; j < td.length; j++) {
                            if ($(td[j]).text().includes(input)) {
                                $(tr[i]).show();
                                break;
                            } else $(tr[i]).hide();

                        }
                    }
                }
            </script>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"
        integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>

</html>
