<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>administratie</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="queue">wachtlijst</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="appointment">afspraak</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="client">patienten</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="invoice">facaturen</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
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
            <table class="table table-bordered table-hover table-striped text-center align-middle" id="que">
                <thead class="thead-dark">
                    <tr>
                        <th>priority</th>
                        <th>patient naam</th>
                        <th>verplaats naar patient lijst</th>
                        <th>delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($queue as $que)
                        <tr id="tr{{ $que->pat->id }}">
                            <td>
                                <p><input type="checkbox" id="c{{ $que->id }}"
                                        @if ($que->priority == 1) checked @endif
                                        onclick="updatePriority({{ $que->id }})" /></p>
                            </td>
                            <td>
                                <p>{{ $que->pat->name }}</p>
                            </td>
                            <td><button class="btn btn-warning btn-sm"
                                    onclick="makePatient({{ $que->pat->id }})">verplaats</button></td>
                            <td><button class="btn btn-danger btn-sm"
                                    onclick="removeFromQueue({{ $que->id }})">verwijder</button></td>
                        </tr>
                    @endforeach
                </tbody>
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

            // To set the priority
            function updatePriority(id) {
                fetch("/api/administrator/setPriority/" + id + "/" + ($("#c" + id).is(":checked") ? 1 : 0))
            }
            // This is to move a patient of the queue to the patientlist
            function makePatient(id) {
                $("#roomnumber").show();
                var i = setInterval(() => {
                    if ($("#roomnumber").is(":hidden")) {
                        fetch("/api/administrator/assign_room/" + $("#rNumber").val() + "/" + id + "/").then(er => {
                            showMess(er);
                            clearInterval(i);
                        });
                    }
                }, 500);
            }

            // To remove a person of the queue
            function removeFromQueue(id) {
                if (confirm("ben je zeker om de patient van de wachtlijst te verwijderen"))
                    fetch("/api/administrator/removeQueue/" + id).then(er => {
                        showMess(er);
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
<table class="table table-bordered table-hover table-striped text-center align-middle">
    <thead class="thead-dark">
        <tr>
            <th>patient naam</th>
            <th>doctor</th>
            <th>reden</th>
            <th>datum</th>
            <th>aanpassen</th>
            <th>verwijderen</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($app as $ap)
            <tr id="{{ $ap->id }}">
                <td>{{ $ap->pat->name }}</td>
                <td><select id="s{{ $ap->id }}" class="form-control">
                        @foreach ($doctor as $d)
                            <option @if ($d->id == $ap->doc->id) selected value="{{ $d->id }}" @endif>
                                {{ $d->name }}</option>
                        @endforeach
                        <select></td>
                <td>{{ $ap->reason }}</td>
                <td><input id="d{{ $ap->id }}"type="datetime-local" value="{{ $ap->appointment_date }}"
                        class="form-control" /></td>
                <td><button class="btn btn-warning btn-sm" onclick="changeAppoint({{ $ap->id }})">pas
                        aan</button></td>
                <td><button class="btn btn-danger btn-sm" onclick="deleteAppoint({{ $ap->id }})">verwijder
                        afspraak</button>
                </td>
            </tr>
        @endforeach
    </tbody>
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
            showMess(er);
        })

    }

    function deleteAppoint(id) {
        if (confirm("Ben je zeker ervan om de afspraak te verwijderen")) {
            fetch("/api/administrator/deleteApp/" + id).then(er => {
                showMess(er);
            });
        }
    }
</script>
</body>

</html>
@elseif($what == 'client')
<div id="messages" class="container mt-3 alert" style="display:none;"></div>
<table class="table table-bordered table-hover table-striped text-center align-middle">
    <thead class="thead-dark">
        <tr>
            <th>naam</th>
            <th>adres</th>
            <th>telefoonnummer</th>
            <th>geboortedatum</th>
            <th>kamer nummer</th>
            <th>familie</th>
            <th>pas aan</th>
            <th>verwijder patient</th>
        </tr>
    <tbody>
        @foreach ($pat as $pt)
            <tr id="{{ $pt->id }}">
                <td>{{ $pt->name }}</td>
                <td><input class="form-control" id="a{{ $pt->id }}" type="text"
                        value="{{ $pt->address }}" />
                </td>
                <td><input class="form-control" type="text" id="p{{ $pt->id }}"
                        value="{{ $pt->phonenumber }}" /></td>
                <td>{{ $pt->date_of_birth }}</td>
                <td>
                    <select class="form-control" id="s{{ $pt->id }}">
                        <option value="-1" @if ($pt->assigned_room_id == -1) selected @endif>extern</option>
                        @foreach ($rooms as $rm)
                            <option value={{ $rm->id }} @if ($rm->id == $pt->assigned_room_id) selected @endif>
                                {{ $rm->roomnumber }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <button class="btn btn-secondary"
                        onclick="$('#f'+{!! $pt->id !!}).toggle('slow','linear');">familie</button>
                    <div id="f{{ $pt->id }}" style="display:none">
                        @if (!empty($pt->familyMembers[0]))
                            <div class="card d-inline-block" id="fm{{ $pt->familyMembers[0]->id }}">
                                <div class="card-header">
                                    <h3 class="card-title">familielid 1</h3>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3 text-start">
                                        <label class="form-label">Naam:</label>
                                        <p class="form-control">{{ $pt->familyMembers[0]->name }} </p>
                                    </div>
                                    <div class="mb-3 text-start">
                                        <label class="form-label">Relatie:</label>
                                        <p class="form-control">{{ $pt->familyMembers[0]->relation }}
                                        </p>
                                    </div>
                                    <div class="mb-3 text-start">
                                        <label class="form-label">Telefoonnummer:</label>
                                        <input type="text" class="form-control"
                                            id="fp{{ $pt->familyMembers[0]->id }}"
                                            value='{{ $pt->familyMembers[0]->contact_number }}' />
                                    </div>
                                    <div class="mb-3 text-start">
                                        <button class="btn btn-warning"
                                            onclick="changeFam({{ $pt->familyMembers[0]->id }})">pas aan</button>
                                        <button class="btn btn-danger"
                                            onclick="removeFam({{ $pt->familyMembers[0]->id }})">verwijder</button>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if (!empty($pt->familyMembers[1]))
                            <div class="card d-inline-block" id="fm{{ $pt->familyMembers[1]->id }}">
                                <div class="card-header">
                                    <h3 class="card-title">familielid 2</h3>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3 text-start">
                                        <label class="form-label">Naam:</label>
                                        <p class="form-control">{{ $pt->familyMembers[1]->name }} </p>
                                    </div>
                                    <div class="mb-3 text-start">
                                        <label class="form-label">Relatie:</label>
                                        <p class="form-control">{{ $pt->familyMembers[1]->relation }}
                                        </p>
                                    </div>
                                    <div class="mb-3 text-start">
                                        <label class="form-label">Telefoonnummer:</label>
                                        <input type="text" class="form-control"
                                            id="fp{{ $pt->familyMembers[1]->id }}"
                                            value='{{ $pt->familyMembers[1]->contact_number }}' />
                                    </div>
                                    <div class="mb-3 text-start">
                                        <button class="btn btn-warning"
                                            onclick="changeFam({{ $pt->familyMembers[1]->id }})">pas aan</button>
                                        <button class="btn btn-danger"
                                            onclick="removeFam({{ $pt->familyMembers[1]->id }})">verwijder</button>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="card d-inline-block">
                                <form action="addFamily" method="post">
                                    @csrf
                                    <input type="hidden" value="{{ $pt->id }}" name="ptid" />
                                    <div class="card-header">
                                        <h3 class="card-title">nieuwe familielid<br />toevoegen</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3 text-start">
                                            @error('name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            <label class="form-label">Naam:</label>
                                            <input type="text" name="name" class="form-control" />
                                        </div>
                                
                                        <div class="mb-3 text-start">
                                            @error('relation')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            <label class="form-label">Relatie:</label>
                                            <input type="text" name="relation" class="form-control" />
                                        </div>
                                
                                        <div class="mb-3 text-start">
                                            @error('phone')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            <label class="form-label">Telefoonnummer:</label>
                                            <input type="text" name="phone" class="form-control" />
                                        </div>
                                
                                        <div class="mb-3 text-start">
                                            <input type="submit" class="btn btn-success" value="voeg toe" />
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif
                </td>
                <td><button class="btn btn-warning btn-sm" onclick="addaptPatient({{ $pt->id }})">pas
                        aan</button></td>
                <td><button class="btn btn-danger btn-sm" onclick="removePatient({{ $pt->id }})">verwijder
                        patient</button></td>
            </tr>
        @endforeach
    </tbody>
</table>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"
    integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('js/apiResponse.js') }}"></script>
<script>
    function addaptPatient(id) {
        fetch("/api/administrator/change_patient", {
            method: "POST",
            body: JSON.stringify({
                id: id,
                address: $("#a" + id).val(),
                roomNm: $("#s" + id).val(),
                phone: $("#p" + id).val()
            }),
            headers: {
                "Content-type": "application/json",
                "X-CSRF-TOKEN": '{!! csrf_token() !!}'
            }
        }).then(mess => {
            showMess(mess);
        });
    }

    function removePatient(id) {
        if (confirm("Ben je zeker om de patient permanent te verwijderen"))
            fetch("/api/administrator/delete_patient/" + id).then(m => {
                showMess(m);
            });
    }

    function changeFam(id) {
        fetch("/api/administrator/change_family", {
            method: "POST",
            body: JSON.stringify({
                id: id,
                phone: $("#fp" + id).val()
            }),
            headers: {
                "Content-type": "application/json",
                "X-CSRF-TOKEN": '{!! csrf_token() !!}'
            }
        }).then(er => {
            showMess(er);
        })
    }

    function removeFam(id) {
        if (confirm("Ben je zeker om het familielid te verwijderen"))
            fetch("/api/administrator/delete_fam/" + id).then(er => {
                showMess(er, function() {
                    $("#fm" + id).hide("slow", "linear");
                    $("#fm" + id).empty();
                });
            });
    }
</script>
</body>

</html>
@elseif($what == 'invoice')
<!--
see the status of invoices
create new invoices
-->
</body>

</html>
@else
<img src="https://http.cat/404" style="width:100vw;height:100vh;" />
</body>

</html>
@endif
