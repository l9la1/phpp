<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Incidenten</title>
    <style>
        .table-responsive {
            max-height: 250px;
            overflow-y: auto;
        }

        /* Adjust card shadow and spacing */
        .card {
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
        }

        /* Subtle background and spacing adjustments */
        body {
            background-color: #f5f7fa;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row">
            <div class="col-md-6 mb-4">
                <table class="table table-bordered table-hover table-striped text-center">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Personen Betrokken</th>
                            <th>Patienten</th>
                            <th>Wanneer Gebeurt</th>
                            <th>Info</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($incidents as $ic)
                            <tr>
                                <td>{{ implode(', ', $ic->invovled_persons) }}</td>
                                <td>{{ implode(', ', $ic->patient_id) }}</td>
                                <td>{{ $ic->date }}</td>
                                <td>{{ $ic->taken_actions }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <div id="messages" class="container mt-3 alert"
                    style="display:none; border-radius: 8px; font-weight: bold;"></div>
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white text-center">
                        <h3 class="text-capitalize mb-0">Voeg Incident Toe</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label font-weight-bold text-secondary">Patienten die betrokken
                                zijn</label>
                            <input type="text" class="form-control mb-2"
                                oninput="searchPatient($(this).val(),'pTable')" placeholder="Zoek patienten">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover text-center" id="pTable">
                                    <thead class="bg-info text-white">
                                        <tr>
                                            <th>Patient</th>
                                            <th>Selecteer</th>
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
                        </div>
                        <div class="mb-4">
                            <label class="form-label font-weight-bold text-secondary">Betrokken Hulpverlenende
                                Personen</label>
                            <input type="text" class="form-control mb-2"
                                oninput="searchPatient($(this).val(),'eTable')" placeholder="Zoek personen">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover text-center" id="eTable">
                                    <thead class="bg-warning text-dark">
                                        <tr>
                                            <th>Werknemer</th>
                                            <th>Selecteer</th>
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
                        </div>
                        <div class="mb-3">
                            <label for="info" class="form-label font-weight-bold text-secondary">Wat is er gebeurd
                                en hoe opgelost</label>
                            <textarea name="info" id="info" class="form-control" rows="4"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="when" class="form-label font-weight-bold text-secondary">Wanneer
                                gebeurd</label>
                            <input type="datetime-local" name="when" id="when" class="form-control">
                        </div>
                    </div>
                    <div class="card-footer bg-light">
                        <input type="submit" value="Voeg toe" class="btn btn-primary w-100 font-weight-bold"
                            onclick="addIncident()">
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
    <script src="{{ asset('js/apiResponse.js') }}"></script>
    <script>
        // Make api call to url and add a new incident in de db 
        function addIncident() {
            // Check wich patients are checked
            var pt = $("#pTable");
            var pdt = pt.find("tr>td");
            var pd = [];
            for (var i = 0; i < pdt.length; i++) {
                if ($(pdt[i]).children())
                    if ($(pdt[i]).children().is(":checked"))
                        pd.push($(pdt[i]).children().val());
            }

            // Check wich doctors are checked
            var dt = $("#eTable");
            var ddt = dt.find("tr>td");
            var dd = [];
            for (var i = 0; i < ddt.length; i++) {
                if ($(ddt[i]).children())
                    if ($(ddt[i]).children().is(":checked")) {
                        dd.push("d" + $(ddt[i]).children().val());
                    }
            }

            // Make api call
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
            }).then(er => showMess(er, function() {
                location.reload();
            }));
        }

        // This is to search for persons in the given id off a table
        function searchPatient(val, id) {
            var tb = $("#" + id);
            var tr = tb.find("tr");

            for (var i = 0; i < tr.length; i++) {
                var td = $(tr[i]).find("td");
                for (var j = 0; j < td.length; j++) {
                    if ($(td[j]).text().includes(val)) {
                        $(tr[i]).show();
                        break;
                    } else
                        $(tr[i]).hide();
                }
            }
        }
    </script>
</body>

</html>
