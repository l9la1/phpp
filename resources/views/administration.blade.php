<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Document</title>
    <style>
        .nav ul li {
            list-style-type: none;
            display: inline;
            padding: 1rem;
        }

        .nav ul li a {
            text-decoration: none;
            color: #000;
        }

        .nav ul li:hover {
            background-color: #ccc;
        }

        .nav {
            background-color: #aaa;
        }
    </style>
</head>

<body>
    <div class="nav">
        <ul>
            <li><a href="queue">queue</a></li>
            <li><a href="appointment">afspraak</a></li>
            <li><a href="queue">queue</a></li>
            <li><a href="queue">queue</a></li>
        </ul>
    </div>
    <!--
    administration can
    see addapt  the queue
    can see the doctors appointment and can change it
    can see the incidents
    can add new invoices
    adding personnelaccount with the appropriate role
    also check if doctor appointments overlapping each other only of the doctor self
    -->

    <!--queue-->
    <!--
        search function
        show it in table
        add appropriate buttons to the table
    -->
    @if ($what == 'queue')
        <div id="messages" class="container mt-3 alert alert-success" style="display:none;"></div>
        <div id="roomnumber"
            style="height:250px;width:250px;box-shadow:5px 5px 15px #aaa;position: absolute;background-color:#eee;z-index:100;margin-left:31.5rem;display:none;">
            <label class="form-label">kamer nummer</label>
            <select class="form-control" id="rNumber">
                <option value="-1">extern</option>
                @foreach ($rooms as $room)
                    <option value={{ $room->roomnumber }}>{{ $room->roomnumber }}</option>
                @endforeach
            </select>
            <button class="btn btn-success" onclick="$('#roomnumber').hide()">verplaats</button>
        </div>
        <div class="input-group">
            <input type="text" placeholder="Zoek mensen op de wachtlijst" class="form-control" id="search"
                oninput="search($(this).val())" />
        </div>
        <div style="height:400px;overflow-y:scroll;">
            <table class="table table-bordered table-hover" id="que">
                <tr>
                    <th>priority</th>
                    <th>patient naam</th>
                    <th>verplaats naar patient lijst</th>
                    <th>delete</th>
                </tr>
                @foreach ($queue as $que)
                    <tr id="tr{{ $que->pat->id }}">
                        <td>
                            <p>{{ $que->priority }}</p>
                        </td>
                        <td>
                            <p>{{ $que->pat->name }}</p>
                        </td>
                        <td><button class="btn btn-warning"
                                onclick="makePatient({{ $que->pat->id }})">verplaats</button></td>
                        <td><button class="btn btn-danger"
                                onclick="removeFromQueue({{ $que->id }})">verwijder</button></td>
                    </tr>
                @endforeach
            </table>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"
            integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script>
            // This is to search through the table of data
            function search(searchFor) {
                var table = $("#que");
                var tr = table.children().find("tr");
                for (var i = 0; i < tr.length; i++) {
                    var p = $(tr[i]).find("p");
                    for (var j = 0; j < $(p).length; j++) {
                        if (!$(p[j]).text().includes(searchFor))
                            $(tr[i]).hide();
                        else {
                            $(tr[i]).show();
                            break;
                        }
                    }
                }
            }

            // This is to move a patient of the queue to the patientlist
            function makePatient(id) {
                $("#roomnumber").show();
                var i = setInterval(() => {
                    if ($("#roomnumber").is(":hidden")) {
                        fetch("/api/administrator/assign_room/" + $("#rNumber").val() + "/" + id).then(er => {
                            er.json().then(mess => {
                                $("#messages").show();
                                $("#messages").text(mess.mes);
                                $("#tr" + id).hide("slow", "linear", function() {
                                    $("#tr" + id).empty();
                                });
                            });
                            setTimeout(() => {
                                $("#messages").text();
                                $("#messages").hide();
                                $("#rNumber").prop('selectedIndex', 0);
                            }, 5000);
                        })
                        clearInterval(i);
                    }
                }, 500);
            }

            // To remove a person of the queue
            function removeFromQueue(id) {
                if (confirm("ben je zeker om de patient van de wachtlijst te verwijderen"))
                    fetch("/api/administrator/removeQueue/" + id).then(er => {
                        er.json().then(mess => {
                            $("#messages").show();
                            $("#messages").text(mess.mes);
                            $("#tr" + id).hide("slow", "linear", function() {
                                $("#tr" + id).empty();
                            });
                        });
                        setTimeout(() => {
                            $("#messages").text();
                            $("#messages").hide();
                            $("#rNumber").prop('selectedIndex', 0);
                        }, 5000);
                    })
            }
        </script>
</body>

</html>
@elseif($what == 'appointment')
<div id="messages" class="container mt-3 alert alert-success" style="display:none;"></div>
<!--
            table with all the appointments
            addapt the date patient and doctor
            -->
<table class="table table-bordered table-hover">
    <tr>
        <th>patient naam</th>
        <th>doctor</th>
        <th>reden</th>
        <th>datum</th>
        <th>aanpassen</th>
        <th>verwijderen</th>
    </tr>
    @foreach ($app as $ap)
        <tr id="{{ $ap->id }}">
            <td>{{ $ap->pat->name }}</td>
            <td><select id="s{{ $ap->id }}">
                    @foreach ($doctor as $d)
                        <option @if ($d->id == $ap->doc->id) selected value="{{ $d->id }}" @endif>
                            {{ $d->name }}</option>
                    @endforeach
                    <select></td>
            <td>{{ $ap->reason }}</td>
            <td><input id="d{{ $ap->id }}"type="datetime-local" value="{{ $ap->appointment_date }}" /></td>
            <td><button class="btn btn-warning" onclick="changeAppoint({{ $ap->id }})">pas aan</button></td>
            <td><button class="btn btn-danger" onclick="deleteAppoint({{ $ap->id }})">verwijder afspraak</button>
            </td>
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
    function changeAppoint(id) {
        fetch("/api/administrator/changeApp/" + id + "/" + encodeURIComponent($("#d" + id).val()) + "/" + $("#s" + id)
            .val()).then(er => {
            if (er.ok) {
                er.json().then(mess => {
                    $("#messages").text(mess.mes);
                    $("#messages").show("slow", "linear", function() {
                        setTimeout(function() {
                            $("#messages").hide("slow");
                        }, 2000);
                    });
                })

            } else
                console.log(er);
        })

    }

    function deleteAppoint(id) {
        fetch("/api/administrator/deleteApp/" + id).then(er => {
            if (er.ok) {
                er.json().then(mess => {
                    $("#messages").text(mess.mes);
                    $("#messages").show("slow", "linear", function() {
                        $("#" + id).hide("slow", "linear", function() {
                            $("#" + id).empty();
                        })
                        setTimeout(function() {
                            $("#messages").hide("slow");
                        }, 2000);
                    });
                });

            }
        });
    }
</script>
</body>

</html>
@endif
