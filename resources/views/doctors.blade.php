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

<body>
    <h1>Welkom test</h1>
    <table class="table table-dark table-striped">
        <tr>
            <th>client</th>
            <th>datum</th>
            <th>voor wat</th>
            <th>adapt</th>
            <th>remove</th>
        </tr>
        @foreach ($app as $ap)
            <tr id="{{ $ap->Appointment_id }}">
                <td><select>
                    
                @foreach($patient as $pat)
                <option  value='{{ $pat->Patient_id }}'name="name"
                    @if($pat->Patient_id==$ap->pat->Patient_id)
                    selected
                    @endif
                    >{{$pat->Name}} </option>
                @endforeach
                </select>
            </td>
                <td><input type="datetime-local" value='{{ $ap->Appointment_date }}' name="date" /></td>
                <td><input type="text" value="{{ $ap->Reason }}" name="reason" /></td>
                <td><input type="button" value="adapt" onclick="updateData({{ $ap->Appointment_id }})" /></td>
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
        
        function updateData(id) {
            var name, date, reason;
            var r = $("#" + id).children().find("input");
            for (var i = 0; i < r.length; i++) {
                var d = r[i];
                if (d.name == "name") name = d.value;
                else if (d.name == "date") date = d.value;
                else if (d.name == "reason") reason = d.value;
            }
            fetch(location, {
                method: "POST",
                body: JSON.stringify({
                    ids: id,
                    patid: name,
                    dates: date,
                    reasons: reason,
                }),
                headers: {
                    "Content-type": "application/json",
                    "X-CSRF-TOKEN": '{!!csrf_token()!!}'
                }
            });
        }
    </script>
</body>

</html>
