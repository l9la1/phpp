<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Doctor</title>
</head>

<body onload="setTime()">
    <div id="messages">
    </div>
    <h1>Welkom test</h1>
    <div class="card float-end" style="width:500px; margin-right:10px;">
        <div class="card-body">
            <form method="post" action="/addApointment" onsubmit="addAppointment(event)" id="addAp">
                @csrf
                <div class="input-group">
                    <label class="input-group-text">client</label>
                    <select class="form-control" name="patientName">

                        @foreach ($patient as $pat)
                            <option value='{{ $pat->id }}'name="name">{{ $pat->name }} </option>
                        @endforeach
                    </select>
                </div>
                <div class="input-group">
                    <lablel class="input-group-text">datum</lablel>
                    <input type="datetime-local" class="form-control" name="date" value="2024-10-25T10:10"
                        id="nAppDate" />
                </div>
                <div class="input-group">
                    <lablel class="input-group-text">reden</lablel>
                    <textarea type="text" class="form-control" name="reason" id="nAppReason"></textarea>
                </div>

        </div>
        <div class="card-footer">
            <input type="submit" class="btn btn-success float-end" value="voeg afspraak toe" />
            </form>
        </div>
    </div>
    <table class="table table-dark table-striped">
        <tr>
            <th>client</th>
            <th>datum</th>
            <th>voor wat</th>
            <th>adapt</th>
            <th>remove</th>
        </tr>
        @foreach ($app as $ap)
            <tr id="{{ $ap->id }}">
                <td><select id="s{{ $ap->id }}">

                        @foreach ($patient as $pat)
                            <option value='{{ $pat->id }}'name="name"
                                @if ($pat->id == $ap->pat->id) selected @endif>{{ $pat->name }} </option>
                        @endforeach
                    </select>
                </td>
                <td><input type="datetime-local" value='{{ $ap->appointment_date }}' name="date" /></td>
                <td><input type="text" value="{{ $ap->reason }}" name="reason" /></td>
                <td><input type="button" value="adapt" class="btn btn-primary"
                        onclick="updateData({{ $ap->id }})" /></td>
                <td><button class="btn btn-danger" onclick="deleteAppointment({{ $ap->id }})">Delete</button></td>
            </tr>
        @endforeach
    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"
        integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        function deleteAppointment(id) {
            if (confirm("Ben je zeker om de afspraak te verwijderen")) {
                fetch(location + "api/deleteApp/" + id).then(err=>showError(err));
            }
        }

        function showError(response) {
            var mess = $("#messages");
            if (!response.ok) {
                    response.json().then(error => {
                        var err = error.errors;
                        if (err) {
                            for (let field in error)
                                for (let i in error[field])
                                    for (let j in error[field][i])
                                        mess.append("<p class='alert alert-danger'>" + i + " : " + error[field][i][
                                                j]
                                            .toString()
                                            .replace(',', '&emsp;') + "</p>");

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

        function setTime() {
            $("#nAppDate").attr({
                "min": getCurrentDateTime()
            });
            $("#nAppDate").val(getCurrentDateTime());
        }

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

        function addAppointment(e) {
            e.preventDefault();
            const data = new FormData(document.getElementById("addAp"));

            fetch(location + "api/addApointment", {
                method: "POST",
                body: data,
            }, ).then(err=>showError(err));
        }

        function updateData(id) {
            var name, date, reason;
            var r = $("#" + id).children().find("input");
            for (var i = 0; i < r.length; i++) {
                var d = r[i];
                if (d.name == "date") date = d.value;
                else if (d.name == "reason") reason = d.value;
            }
            fetch(location, {
                method: "POST",
                body: JSON.stringify({
                    id: id,
                    patid: $("#s" + id).val(),
                    date: date,
                    reason: reason,
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
