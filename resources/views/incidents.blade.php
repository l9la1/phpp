<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Incidenten</title>
</head>

<body>
    <div class="row">
        <div class="col-6">
            <table class="table table-bordered table-hover table-striped text-center align-middle">
                <thead class="thead-dark">
                    <tr>
                        <th>Personen betrokken</th>
                        <th>Patienten</th>
                        <th>Wanneer gebeurt</th>
                        <th>Info</th>
                    </tr>
                </thead>
                <tbody>`
                    @foreach ($incidents as $ic)
                        <tr>
                            <td>
                                {{implode(', ',$ic->invovled_persons)}}
                            </td>
                            <td>{{implode(', ',$ic->patient_id)}}</td>`
                            <td>{{$ic->date}}</td>
                            <td>{{$ic->taken_actions}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-6">
            <div id="messages" class="container mt-3 alert"
                style="display:none; border-radius: 8px; font-weight: bold;"></div>
            <div class="card">
                <div class="card-header">Voeg incident toe</div>
                <div class="card-body">
                    <div class="mb-3"><label class="form-label">Patienten die betrokken
                            zijn</label>
                        <table class="table table-bordered table-hover table-striped text-center align-middle"
                            id="pTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Patient</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($patients as $pt)
                                    <tr>
                                        <td>{{ $pt->name }}</td>
                                        <td><input type="checkbox" value="{{ $pt->id }}" /></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mb-3"><label class="form-label">Betrokken hulp verlende
                            personen</label>
                        <table class="table table-bordered table-hover table-striped text-center align-middle"
                            id="eTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Werknemer</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($doctors as $dt)
                                    <tr>
                                        <td>{{ $dt->name }}</td>
                                        <td><input type="checkbox" value="{{ $dt->id }}" /></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mb-3"><label for="info" class="form-label">Wat is er gebeurd en hoe
                            opgelost</label>
                        <textarea name="info" id="info" class="form-control"></textarea>
                    </div>
                    <div class="mb-3"><label for="when" class="form-label">Wanneer gebeurd</label>
                        <input type="datetime-local" name="when" id="when" class="form-control">
                    </div>
                </div>
                <div class="card-footer"><input type="submit" value="Voeg toe" class="btn btn-primary w-100"
                        onclick="addIncident()"></div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"
        integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('js/apiResponse.js') }}"></script>
    <script>
        function addIncident() {
            var pt = $("#pTable");
            var pdt = pt.find("tr>td");
            var pd = [];
            for (var i = 0; i < pdt.length; i++) {
                if ($(pdt[i]).children())
                    if ($(pdt[i]).children().is(":checked"))
                        pd.push($(pdt[i]).children().val());
            }

            var dt = $("#eTable");
            var ddt = dt.find("tr>td");
            var dd = [];
            for (var i = 0; i < ddt.length; i++) {
                if ($(ddt[i]).children())
                    if ($(ddt[i]).children().is(":checked")) {
                        dd.push("d" + $(ddt[i]).children().val());
                    }
            }
            fetch("/api/incident/addIncident", {
                method: "POST",
                body: JSON.stringify({
                    "doctor": dd,
                    "patient": pd,
                    "info": $("#info").val(),
                    "when": $("#when").val()
                }),
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            }).then(er => showMess(er));
        }
    </script>
</body>

</html>
