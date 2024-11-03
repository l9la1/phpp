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
                                    onclick="removeFromQueue({{ $que->id }},{{ $que->pat->id }})">verwijder</button>
                            </td>
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
        <script src="{{ asset('js/apiResponse.js') }}"></script>

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
                            $("#tr" + id).hide("slow", "linear", function() {
                                $("#tr" + id).empty();
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
                        showMess(er);
                        $("#tr" + trid).hide("slow", "linear", function() {
                            $("#tr" + trid).empty();
                        });
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
<script src="{{ asset('js/apiResponse.js') }}"></script>

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
                        <div class="d-flex">
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
                        </div>
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
<div class="row">
    <div class="col-6 ps-5">
        <div style="overflow:scroll;max-height:500px;box-shadow:5px 5px 15px #ccc" class="mt-5">
            <h3 class="text-center" style="background-color:#aaa;">Bestaande facaturen</h3>
            <table class="table table-bordered table-hover table-striped text-center align-middle">
                <thead class="thead-dark">
                    <tr>
                        <th>patient naam</th>
                        <th>huur kosten</th>
                        <th>ziekten kosten</th>
                        <th>totaal</th>
                        <th>betaald</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($finance as $fin)
                        <tr>
                            <td>{{ $fin->pat->name }}</td>
                            <td>€{{ number_format($fin->hire_cost, 2, ',', '.') }}</td>
                            <td>€{{ number_format($fin->caretaking_costs, 2, ',', '.') }}</td>
                            <td>€{{ number_format($fin->hire_cost+$fin->caretaking_costs, 2, ',', '.') }}</td>
                            <td>
                                @if ($fin->payed)
                                    betaald
                                @else
                                    openstaande facature
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-6 pe-5">
        <div class="card mt-5" style="box-shadow:5px 5px 15px #ccc">
            <div class="card-header">
                <h3 class="text-center">Maak nieuwe facature</h3>
            </div>
            <div class="card-body">
                <form action="addInvoice" method="post">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">patient</label>
                        <select class="form-control" name="patient">
                            @foreach ($pat as $pt)
                                <option value="{{ $pt->id }}">{{ $pt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">huur kosten</label>
                        <input type="number" value="0.00" step=".01" class="form-control" name="hiring" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">verzorgings kosten</label>
                        <input type="number" value="0.00" step=".01" class="form-control"
                            name="caretaking" />
                    </div>
                    <div class="mb-3">
                        <input type="submit" value="voeg toe" class="btn btn-success" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>

</html>
@elseif($what == 'add_account')
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
            <form action="add_doc" method="post">
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
