<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Doctor Appointment System</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body onload="setTime()">
    <nav class="nav justify-content-center">
        <li class="nav-item">
            <a class="nav-link" href="/doctor">Afspraken</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/medical">Medische dosier</a>
        </li>
    </nav>
    <div id="messages" class="container mt-3"></div>

    <div class="container">
        <h1 class="header-title">Doctor's Appointment System</h1>
    </div>

    <div class="container mb-5">
        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white text-center">
                        <h5 class="mb-0">Add New Appointment</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" action="/addApointment" onsubmit="addAppointment(event)" id="addAp">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Client</label>
                                <select class="form-select" name="patientName" required>
                                    @foreach ($patient as $pat)
                                        <option value='{{ $pat->id }}'>{{ $pat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Datum</label>
                                <input type="datetime-local" class="form-control" name="date" value="2024-10-25T10:10"
                                    id="nAppDate" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Reden</label>
                                <textarea class="form-control" name="reason" id="nAppReason" required></textarea>
                            </div>
                            <div class="text-end">
                                <input type="submit" class="btn btn-custom" value="Voeg Afspraak Toe" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-success text-white text-center">
                        <h5 class="mb-0">Upcoming Appointments</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="client-col">Client</th>
                                        <th class="date-col">Datum</th>
                                        <th class="reason-col">Voor Wat</th>
                                        <th class="action-col">Adapt</th>
                                        <th class="action-col">Remove</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($app as $ap)
                                    @if($ap->pat->approval_state==1)
                                        <tr id="{{ $ap->id }}">
                                            <td>
                                                <select id="s{{ $ap->id }}" class="form-select">
                                                    @foreach ($patient as $pat)
                                                        <option value='{{ $pat->id }}'
                                                            @if ($pat->id == $ap->pat->id) selected @endif>
                                                            {{ $pat->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="datetime-local" value='{{ $ap->appointment_date }}'
                                                    name="date" class="form-control" />
                                            </td>
                                            <td>
                                                <textarea
                                                    class="form-control">{{ $ap->reason }}</textarea>
                                            </td>
                                            <td>
                                                <input type="button" value="Pas aan" class="btn btn-warning"
                                                    onclick="updateData({{ $ap->id }})" />
                                            </td>
                                            <td>
                                                <button class="btn btn-danger" onclick="deleteAppointment({{ $ap->id }})">Delete</button>
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
        // First ask if you are sure 
        // If so then make a api call to delete the appointment
        // If errors show them and if not refresh the page
        function deleteAppointment(id) {
            if (confirm("Ben je zeker om de afspraak te verwijderen")) fetch("/api/doctor/deleteApp/" + id).then(err=>showError(err));
        }

        // This is the function that is called to show all the errors returned by the api
        function showError(response) {
            var mess = $("#messages");
            if (!response.ok) {
                    response.json().then(error => {
                        var err = error.errors;
                        // Check if there are errors
                        if (err) {
                            // Loop through until the right point
                            for (let field in error)
                                for (let i in error[field])
                                    for (let j in error[field][i])
                                        mess.append("<p class='alert alert-danger'>" + i + " : " + error[field][i][
                                                j]
                                            .toString()
                                            .replace(',', '&emsp;') + "</p>");
                            // Remove the errors
                            setTimeout(() => {
                                for (var i = 0; i < mess.children().length; i++)
                                    $(mess.children()[i]).hide("slow", "swing", function() {
                                        mess.empty();
                                    });
                            }, 5000);
                        }
                    });
            } else {
                window.location.reload();
                $("#nAppReason").val("");
            }
        }

        // This is to set the date picker to current date and prevent that you can make a appoint earlier then today
        function setTime() {
            $("#nAppDate").attr({
                "min": getCurrentDateTime()
            });
            $("#nAppDate").val(getCurrentDateTime());
        }

        // Get current date
        function getCurrentDateTime() {
            const now = new Date();

            // Format date parts
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0'); // Months are 0-based, so add 1
            const day = String(now.getDate()).padStart(2, '0');

            // Format time parts
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');

            // Combine date and time into desired format
            return `${year}-${month}-${day}T${hours}:${minutes}`;
        }

        // This is to add a new appointment by making a api call with the right data
        function addAppointment(e) {
            e.preventDefault();
            const data = new FormData(document.getElementById("addAp"));

            fetch("/api/doctor/addApointment", {
                method: "POST",
                body: data,
            }, ).then(err=>showError(err));
        }

        // This is to update the appointment by sending the corresponding data with a api call including the csrf key that is needed
        function updateData(id) {
            fetch(location, {
                method: "POST",
                body: JSON.stringify({
                    id: id,
                    patid: $("#s" + id).val(),
                    date:  $("#" + id).children().find("input").val(),
                    reason: $("#"+id).children().find("textarea").val(),
                }),
                headers: {
                    "Content-type": "application/json",
                    "X-CSRF-TOKEN": '{!! csrf_token() !!}'
                }
            }).then(err=>showError(err));
        }
    </script>
</body>

</html>
