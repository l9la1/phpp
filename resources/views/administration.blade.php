<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Administratie</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="queue">Wachtlijst</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="appointment">Afspraak</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="client">Patienten</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="invoice">Facaturen</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="add_account">Toevoegen account</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="room">Kamers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/incidents" target="_blank">Incidenten</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}">Log uit</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    @if ($what == 'queue')
        <!--queue-->
        <!--
        search function
        show it in table
        add appropriate buttons to the table-->
        <div id="messages" class="container mt-3 alert alert-success"
            style="display:none; border-radius: 8px; font-weight: bold;"></div>

        <div id="roomnumber" class="p-3"
            style="height:250px;width:250px;box-shadow:5px 5px 15px rgba(0,0,0,0.2);position: absolute;background-color:#f7f9fc;z-index:100;margin-left:31.5rem;display:none; border-radius: 8px;">
            <label class="form-label">Kamer Nummer</label>
            <select class="form-control" id="rNumber" style="margin-bottom: 1rem;">
                <option value="-1">Extern</option>
                @foreach ($rooms as $room)
                    @if ($room->status == 'free')
                        <option value="{{ $room->id }}">{{ $room->id }}</option>
                    @endif
                @endforeach
            </select>
            <button class="btn btn-success" style="width: 100%;" onclick="$('#roomnumber').hide()">Verplaats</button>
        </div>

        <div class="input-group mb-3">
            <input type="text" placeholder="Zoek mensen op de wachtlijst" class="form-control" id="search"
                style="border-radius: 5px; box-shadow: inset 0px 2px 4px rgba(0, 0, 0, 0.1);"
                oninput="search($(this).val())" />
        </div>

        
            <table class="table table-hover table-striped text-center align-middle" id="que"
                style="background-color: #fff;">
                <thead style="background-color: #20c997; color: white;">
                    <tr>
                        <th>Prioriteit</th>
                        <th>Patient Naam</th>
                        <th>Verplaats naar patient lijst</th>
                        <th>Verwijder</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($queue as $que)
                        <tr id="tr{{ $que->pat->id }}"
                            style="background-color: #ffffff; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.05); margin-bottom: 10px;">
                            <td>
                                <input type="checkbox" id="c{{ $que->id }}"
                                    @if ($que->priority == 1) checked @endif
                                    onclick="updatePriority({{ $que->id }})" />
                            </td>
                            <td>{{ $que->pat->name }}</td>
                            <td><button class="btn btn-warning btn-sm text-white"
                                    onclick="makePatient({{ $que->pat->id }})">Verplaats</button></td>
                            <td><button class="btn btn-danger btn-sm"
                                    onclick="removeFromQueue({{ $que->id }},{{ $que->pat->id }})"><i
                                        class="bi bi-trash"></i></button>
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
        <script src="{{ asset('js/apiResponse.js') }}"></script>

        <script>
            // This is to search through the table of data
            function search(searchFor) {
                var table = $("#que");
                var tr = table.children().find("tr");
                for (var i = 0; i < tr.length; i++) {
                    var p = $(tr[i]).find("td");
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
                            showMess(er, function() {
                                $("#tr" + id).hide("slow", "linear", function() {
                                    $("#tr" + id).empty();
                                });
                            });
                            clearInterval(i);
                        });
                    }
                }, 500);
            }

            // To remove a person of the queue
            function removeFromQueue(id, trid) {
                if (confirm("ben je zeker om de patient van de wachtlijst te verwijderen"))
                    fetch("/api/administrator/removeQueue/" + id).then(er => {
                        showMess(er, function() {
                            $("#tr" + trid).hide("slow", "linear", function() {
                                $("#tr" + trid).empty();
                            });
                        });
                    })
            }
        </script>
</body>

</html>
@elseif($what == 'appointment')
<!--
appointments
search for them
addapt them
-->
<div id="messages" class="container mt-3 alert alert-success"
    style="display:none; border-radius: 8px; font-weight: bold;"></div>
<div class="input-group mb-3">
    <input type="text" placeholder="Zoek mensen in de agenda" class="form-control" id="search"
        style="border-radius: 5px; box-shadow: inset 0px 2px 4px rgba(0, 0, 0, 0.1);" oninput="search($(this).val())" />
</div>
<table class="table table-hover table-striped text-center align-middle"
    style="border-collapse: separate; border-spacing: 0 15px; background-color: #f7f9fc;" id="aTable">
    <thead style="background-color: #20c997; color: #fff;">
        <tr>
            <th>Patient Naam</th>
            <th>Doctor</th>
            <th>Reden</th>
            <th>Datum</th>
            <th>Aanpassen</th>
            <th>Verwijderen</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($app as $ap)
            <tr id="{{ $ap->id }}"
                style="background-color: #ffffff; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);">
                <td>{{ $ap->pat->name }}</td>
                <td>
                    <select id="s{{ $ap->id }}" class="form-control">
                        @foreach ($doctor as $d)
                            <option @if ($d->id == $ap->doc->id) selected @endif value="{{ $d->id }}">
                                {{ $d->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>{{ $ap->reason }}</td>
                <td><input id="d{{ $ap->id }}" type="datetime-local" value="{{ $ap->appointment_date }}"
                        class="form-control" /></td>
                <td><button class="btn btn-warning btn-sm" style="color: #fff;"
                        onclick="changeAppoint({{ $ap->id }})">Pas Aan</button></td>
                <td><button class="btn btn-danger btn-sm" style="color: #fff;"
                        onclick="deleteAppoint({{ $ap->id }})"><i class="bi bi-trash"></i></button></td>
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
    // This is to search thourgh the table of appointments
    function search(val) {
        var tb = $("#aTable");
        var tr = tb.find("tr");

        for (var i = 0; i < tr.length; i++) {
            var td = $(tr[i]).find("td");
            for (var j = 0; j < td.length; j++) {
                if ($(td[j]).children().length > 0)
                    if (!$(td[j]).children().val().includes(val)) {
                        $(tr[i]).hide();
                    } else {
                        $(tr[i]).show();
                        break;
                    }
                else
                if (!$(td[j]).text().includes(val)) {
                    $(tr[i]).hide();
                } else {
                    $(tr[i]).show();
                    break;
                }
            }
        }
    }

    // To save the changed appointment
    function changeAppoint(id) {
        fetch("/api/administrator/changeApp/", {
            method: "post",
            body: JSON.stringify({
                id: id,
                date: $("#d" + id).val(),
                doctor: $("#s" + id).val()
            }),
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        }).then(er => {
            showMess(er);
        })

    }

    // Ask if you are sure if so then delete it 
    function deleteAppoint(id) {
        if (confirm("Ben je zeker ervan om de afspraak te verwijderen")) {
            fetch("/api/administrator/deleteApp/" + id).then(er => {
                showMess(er, function() {
                    $("#" + id).hide("slow", "linear", function() {
                        $("#" + id).empty();
                    });
                });
            });
        }
    }
</script>
</body>

</html>
@elseif($what == 'client')
<!--
for the client
addapt client
addapt the family of the client
remove client
-->
<div id="messages" class="container mt-3 alert" style="display:none; border-radius: 8px; font-weight: bold;"></div>
<div class="input-group mb-3">
    <input type="text" placeholder="Zoek patienten" class="form-control" id="search"
        style="border-radius: 5px; box-shadow: inset 0px 2px 4px rgba(0, 0, 0, 0.1);"
        oninput="search($(this).val())" />
</div>
<table class="table table-bordered table-hover table-striped text-center align-middle" id="pTable">
    <thead class="thead-dark">
        <tr>
            <th>naam</th>
            <th>adres</th>
            <th>telefoonnummer</th>
            <th>geboortedatum</th>
            <th>kamer nummer</th>
            <th>familie</th>
            <th></th>
        </tr>
    <tbody>
        @foreach ($pat as $pt)
            <tr id="{{ $pt->id }}">
                <td>{{ $pt->name }}</td>
                <td>
                    @if ($pt->dead == 0)
                        <input class="form-control" id="a{{ $pt->id }}" type="text"
                            value="{{ $pt->address }}" />
                    @else
                        {{ $pt->address }}
                    @endif
                </td>
                <td>
                    @if ($pt->dead == 0)
                        <input class="form-control" type="text" id="p{{ $pt->id }}"
                            value="{{ $pt->phonenumber }}" />
                    @else
                        {{ $pt->phonenumber }}
                    @endif
                </td>
                <td>{{ $pt->date_of_birth }}</td>
                <td>
                    @if ($pt->dead == 0)
                        <select class="form-control" id="s{{ $pt->id }}">
                            <option value="-1" @if ($pt->assigned_room_id == -1) selected @endif>extern</option>
                            @foreach ($rooms as $rm)
                                @if ($rm->id == $pt->assigned_room_id)
                                    <option value={{ $rm->id }} selected>
                                        {{ $rm->id }}</option>
                                @elseif($rm->status == 'free')
                                    <option value={{ $rm->id }}>
                                        {{ $rm->id }}</option>
                                @endif
                            @endforeach
                        </select>
                    @endif
                </td>
                <td>
                    <button class="btn btn-secondary btn-sm"
                        onclick="$('#f'+{!! $pt->id !!}).toggle('slow','linear');">familie</button>
                    <div id="f{{ $pt->id }}" style="display:none">
                        <div class="d-flex">
                            <!--If there is no family show card to create a new family member-->
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
                                            @if ($pt->dead == 0)
                                                <input type="text" class="form-control"
                                                    id="fp{{ $pt->familyMembers[0]->id }}"
                                                    value='{{ $pt->familyMembers[0]->contact_number }}' />
                                            @else
                                                <p class="form-control">{{ $pt->familyMembers[0]->contact_number }}
                                                </p>
                                            @endif
                                        </div>
                                        <div class="mb-3 text-start">
                                            @if ($pt->dead == 0)
                                                <button class="btn btn-warning"
                                                    onclick="changeFam({{ $pt->familyMembers[0]->id }})">pas
                                                    aan</button>
                                                <button class="btn btn-danger"
                                                    onclick="removeFam({{ $pt->familyMembers[0]->id }})"><i
                                                        class="bi bi-trash"></i></button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @elseif($pt->dead == 0)
                                <div class="card d-inline-block">
                                    <form action="addFamily" method="post"
                                        onsubmit="makeApiCall(event,'id0'+{{ $pt->id }})"
                                        id="id0{{ $pt->id }}">
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
                            <!--If there is no family show card to create a new family member-->
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
                                            @if ($pt->dead == 0)
                                                <input type="text" class="form-control"
                                                    id="fp{{ $pt->familyMembers[1]->id }}"
                                                    value='{{ $pt->familyMembers[1]->contact_number }}' />
                                            @else
                                                <p class="form-control">{{ $pt->familyMembers[1]->contact_number }}
                                                </p>
                                            @endif
                                        </div>
                                        <div class="mb-3 text-start">
                                            @if ($pt->dead == 0)
                                                <button class="btn btn-warning"
                                                    onclick="changeFam({{ $pt->familyMembers[1]->id }})">pas
                                                    aan</button>
                                                <button class="btn btn-danger"
                                                    onclick="removeFam({{ $pt->familyMembers[1]->id }})"><i
                                                        class="bi bi-trash"></i></button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @elseif($pt->dead==0)
                                <div class="card d-inline-block">
                                    <form action="addFamily" method="post"
                                        onsubmit="makeApiCall(event,'id1'+{{ $pt->id }})"
                                        id="id1{{ $pt->id }}">
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
                        </div>
                </td>
                <td>
                    <ul class="action-list">
                        <li><a href="/medical/{{ $pt->id }}" class="btn btn-success btn-sm" target="_blank"
                                rel="noopener noreferrer"><i class="bi bi-file-earmark-medical-fill"></i></a></li>
                    @if($pt->dead==0)
                        <li><button class="btn btn-warning" onclick="addaptPatient({{ $pt->id }})"><i
                                    class="bi bi-pencil"></i></button></li>
                        <li><button class="btn btn-danger" onclick="removePatient({{ $pt->id }})"><i
                                    class="bi bi-trash"></i></button></li>
                        <li><button class="btn btn-dark border-none" onclick="killPatient({{ $pt->id }})"><img
                                    src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Ftse3.mm.bing.net%2Fth%3Fid%3DOIP.ciFUIiHSO-kAIL9ZO2-0ugHaIl%26pid%3DApi&f=1&ipt=04cec10803c662be0473655c4d571f4d39670530754687a2f3886701e76193bf&ipo=images"
                                    style="width:inherit;height:inherit" /></button></li>
                    @endif
                    </ul>
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
<script src="{{ asset('js/apiResponse.js') }}"></script>
<script>
    // This is to create a new family member
    function makeApiCall(e, id) {
        e.preventDefault();
        const data = new FormData(document.getElementById(id));

        fetch("/api/administrator/addFamily", {
            method: "POST",
            body: data,
            headers: {
                'Accept': 'application/json'
            }
        }, ).then(err => showMess(err, function() {
            location.reload();
        }));
    }

    // To search through the clients not through the family data
    function search(val) {
        var tb = $("#pTable");
        var tr = tb.find("tr");

        for (var i = 0; i < tr.length; i++) {
            var td = $(tr[i]).find("td");
            for (var j = 0; j < td.length; j++) {
                if ($(td[j]).children().length > 0)
                    if (!$(td[j]).children().val().includes(val)) {
                        $(tr[i]).hide();
                    } else {
                        $(tr[i]).show();
                        break;
                    }
                else
                if (!$(td[j]).text().includes(val)) {
                    $(tr[i]).hide();
                } else {
                    $(tr[i]).show();
                    break;
                }
            }
        }
    }

    // To store the addapted data of the patient
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
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        }).then(mess => {
            showMess(mess);
        });
    }

    // Delete a patient only if you are sure
    function removePatient(id) {
        if (confirm("Ben je zeker om de patient permanent te verwijderen"))
            fetch("/api/administrator/delete_patient/" + id).then(m => {
                showMess(m, function() {
                    $("#" + id).hide("slow", "linear", function() {
                        $("#" + id).empty();
                    })
                });
            });
    }

    // This is to store the addapted data of the family
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


    // This is to remove a family member only if you are sure to do it
    function removeFam(id) {
        if (confirm("Ben je zeker om het familielid te verwijderen"))
            fetch("/api/administrator/delete_fam/" + id).then(er => {
                showMess(er, function() {
                    $("#fm" + id).hide("slow", "linear");
                    $("#fm" + id).empty();
                    location.reload();
                });
            });
    }

    function killPatient(id) {
        if (confirm("Ben je zeker om deze patient als dood te melden.\n Het kan niet meer ongedaan worden gedaan")) {
            if (confirm(
                    "Ben je echt zeker om deze patient als dood te melden.\n Het kan niet meer ongedaan worden gedaan"))
                if (confirm(
                        "Ben je echt echt zeker om deze patient als dood te melden.\n Het kan niet meer ongedaan worden gedaan"
                        ))
                    fetch("/api/administrator/killPatient/" + id).then(er => showMess(er, function() {
                        $("#" + id).hide("slow", "linear", function() {
                            $("#" + id).empty();
                        })
                    }));
        }
    }
</script>
</body>

</html>
@elseif($what == 'invoice')
<!--
see the status of invoices
create new invoices
-->
<div class="row">
    <div class="col-6 ps-5">
        <div style="overflow-y:scroll; max-height:500px; box-shadow: 0px 10px 20px rgba(0,0,0,0.1); border-radius: 10px;"
            class="mt-5">
            <h3 class="text-center"
                style="background-color:#6c757d; color: #fff; padding: 15px; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                Bestaande Facaturen
            </h3>
            <table class="table table-bordered table-hover text-center align-middle"
                style="background-color: #f9f9f9;">
                <thead style="background-color: #007bff; color: #fff;">
                    <tr>
                        <th>Patient Naam</th>
                        <th>Huur Kosten</th>
                        <th>Ziekten Kosten</th>
                        <th>Totaal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($finance as $fin)
                        <tr>
                            <td>{{ $fin->pat->name }}</td>
                            <td>€{{ number_format($fin->hire_cost, 2, ',', '.') }}</td>
                            <td>€{{ number_format($fin->caretaking_costs, 2, ',', '.') }}</td>
                            <td>€{{ number_format($fin->hire_cost + $fin->caretaking_costs, 2, ',', '.') }}</td>
                            <td>
                                <span class="badge {{ $fin->payed ? 'bg-success' : 'bg-danger' }}">
                                    {{ $fin->payed ? 'Betaald' : 'Openstaand' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-6 pe-5">
        <div class="card mt-5" style="box-shadow: 0px 10px 20px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-header text-center"
                style="background-color: #28a745; color: #fff; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                <h3>Maak Nieuwe Facature</h3>
            </div>
            <div class="card-body" style="background-color: #f9f9f9;">
                <form action="addInvoice" method="post">
                    @csrf
                    <div class="mb-3">
                        @error('patient')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <label class="form-label" style="color: #495057;">Patient</label>
                        <select class="form-control" name="patient" style="border-radius: 5px;">
                            @foreach ($pat as $pt)
                                <option value="{{ $pt->id }}">{{ $pt->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        @error('caretaking')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <label class="form-label" style="color: #495057;">Verzorgings Kosten</label>
                        <input type="number" value="0.00" step=".01" class="form-control" name="caretaking"
                            style="border-radius: 5px;" />
                    </div>

                    <div class="mb-3 text-center">
                        <input type="submit" value="Voeg Toe" class="btn btn-primary"
                            style="width: 100%; border-radius: 5px;" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>

</html>
@elseif($what=='room')
<div class="row ps-5 pe-5 pt-2">
    <div class="col-6">
        <div id="messages" class="container mt-3 alert"
            style="display:none; border-radius: 8px; font-weight: bold;"></div>
        <h3 class="text-center"
            style="background-color:#6c757d; color: #fff; padding: 15px; border-top-left-radius: 10px; border-top-right-radius: 10px;">
            Bestaande Kamers
        </h3>
        <table class="table table-bordered table-hover table-striped text-center align-middle" id="pTable">
            <thead class="thead-dark">
                <tr>
                    <td>#</td>
                    <td>hoeveel personen kamer</td>
                    <td>price</td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                @foreach ($rooms as $rm)
                    <tr id="tr{{ $rm->id }}">
                        @if ($rm->status == 'bezet')
                            <td>{{ $rm->id }}</td>
                            <td>{{ $rm->bed_amount }}</td>
                            <td>€ {{ number_format($rm->price, 2, ',', '.') }}</td>
                            <td></td>
                        @else
                            <td>{{ $rm->id }}</td>
                            <td><input type="number" class="form-control" value="{{ $rm->bed_amount }}"
                                    id="b{{ $rm->id }}" max="2" min="1" /></td>
                            <td>€<input type="number" step="0.01" class="form-control"
                                    style="width:19rem;display:inline;"min="0.01" value="{{ $rm->price }}"
                                    id="p{{ $rm->id }}" /></td>
                            <td>
                                <ul class="action-list">
                                    <li><button class="btn btn-primary" onclick="addaptRoom({{ $rm->id }})"><i
                                                class="bi bi-pencil"></i></button></li>
                                    <li><button class="btn btn-danger" onclick="removeRoom({{ $rm->id }})"><i
                                                class="bi bi-trash"></i></button></li>
                                </ul>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-6">
        @if (Session::has('success'))
            <div class="alert alert-success" id="mes">
                {{ Session::get('success') }}
            </div>
        @endif
        <form method="post" action="addRoom">
            @csrf
            <div class="card">
                <div class="card-header text-center text-capitalize">
                    <h3>maak nieuwe kamer</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Hoeveel personen kamer</label>
                        @error('bedamount')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <input type="number" min="1" max="2" value="1" name="bedamount"
                            class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Prijs</label>
                        @error('price')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <input type="number" step="0.01" value="0.01" min="0.01" name="price"
                            class="form-control" required />
                    </div>
                </div>
                <div class="card-footer">
                    <input type="submit" value="voeg kamer toe" class="btn btn-success w-100" />
                </div>
            </div>
        </form>
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
    // This is when there is data sended and there is a message hide it after a specified time
    document.addEventListener("DOMContentLoaded", function() {
        var mes = $("#mes");
        if (mes)
            setTimeout(() => {
                mes.hide("slow", "linear");
            }, 2000);
    });

    // This is to addapt a room
    function addaptRoom(id) {
        fetch("/api/administrator/update_room", {
            method: "POST",
            body: JSON.stringify({
                id: id,
                bedAmount: $("#b" + id).val(),
                price: $("#p" + id).val()
            }),
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        }).then(res => {
            showMess(res);
        })
    }

    // This is to remove a room
    function removeRoom(id) {
        if (confirm('Ben je zeker om de kamer te verwijderen'))
            fetch("/api/administrator/remove_room/" + id).then(res => {
                showMess(res, function() {
                    $("#tr" + id).hide("slow", "linear", function() {
                        $("#tr" + id).empty();
                    });
                });
            });
    }
</script>
</body>

</html>

@elseif($what == "add_account")
<div class="modal modal-sheet position-static d-block bg-body-secondary p-4 py-md-5" tabindex="-1" role="dialog" id="modalSignin">
<div class="d-flex flex-column flex-md-row p-4 gap-4 py-md-5 align-items-center justify-content-center">
  <ul class="dropdown-menu position-static d-grid gap-1 p-2 rounded-3 mx-0 shadow w-220px" data-bs-theme="light">
    <li><a href="#" class="dropdown-item rounded-2" onclick="changeContent('dokter')" >Dokter</a></li>
    <li><a href="#" class="dropdown-item rounded-2" onclick="changeContent('administratie')" >Administratie</a></li>
  </ul>
</div>
<div id="content">
<div id="dokterTemplate" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content rounded-4 shadow">
        <div class="modal-header p-5 pb-4 border-bottom-0">
            <h1 class="fw-bold mb-0 fs-2">Toevoegen dokter</h1>
        </div>
        <div class="modal-body p-5 pt-0">
            <form action="add_doc" method="post">
            @csrf
            <div class="form-floating mb-3">
                <input type="text" class="form-control rounded-3" name="name" placeholder="Magere Hein" required>
                <label for="name">Naam</label>
            </div>
            <div class="form-floating mb-3">
                <input type="date" class="form-control rounded-3" name="date_of_birth" required>
                <label for="date_of_birth">Geboortedatum</label>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control rounded-3" name="contact_email" placeholder="name@example.com" required>
                <label for="contact_email">Email</label>
            </div>
            <label>Telefoonnummer formaat: 0612345678</label>
            <div class="form-floating mb-3">
                <input type="tel" pattern="[0-9]{10}" class="form-control rounded-3" name="contact_phone" placeholder="06-123-456-78" required>
                <label for="contact_phone">Telefoonnummer</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control rounded-3" name="specialty" placeholder="Hartchirurg, Kaakchirurg" required>
                <label for="specialty">Specialiteit</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control rounded-3" name="password" placeholder="Hartchirurg, Kaakchirurg">
                <label for="password">Wachtwoord</label>
            </div>
            <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Voeg toe</button>
            </form>
        </div>
        </div>
    </div>
    </div>
    <div id="administratieTemplate" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content rounded-4 shadow">
        <div class="modal-header p-5 pb-4 border-bottom-0">
            <h1 class="fw-bold mb-0 fs-2">Toevoegen administratie</h1>
        </div>
        <div class="modal-body p-5 pt-0">
            <form action="add_admin" method="post">
            @csrf
            <div class="form-floating mb-3">
                <input type="text" class="form-control rounded-3" name="name" placeholder="Magere Hein" required>
                <label for="name">Naam</label>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control rounded-3" name="admin_mail" placeholder="name@example.com" required>
                <label for="admin_mail">Email</label>
            </div>
            <label>Minimaal 8, Maximaal 25</label>
            <div class="form-floating mb-3">
                <input type="password" maxlength="25" class="form-control rounded-3" name="password" placeholder="password" required>
                <label for="password">Wachtwoord</label>
            </div>
            <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Voeg toe</button>
            </form>
        </div>
        </div>
    </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>
<script>
    function changeContent(value) {
        // Hide all templates first
        document.getElementById('dokterTemplate').style.display = 'none';
        document.getElementById('administratieTemplate').style.display = 'none';

        // Show the selected template
        if (value === 'dokter') {
            document.getElementById('dokterTemplate').style.display = 'block';
        } else if (value === 'administratie') {
            document.getElementById('administratieTemplate').style.display = 'block';
        }
    }
</script>
</form>

@if (Session::has('success'))
    <div class="alert alert-succes" id="mes">
        {{ session::get('success' )}}
    </div>
    @endif
</body>

</html>
@else
<img src="https://http.cat/404" style="width:100vw;height:100vh;" />
</body>

</html>
@endif
