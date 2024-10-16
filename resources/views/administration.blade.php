<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Document</title>
</head>

<body>
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
    <div class="row">
        <div class="col-6 mt-3 ">
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
        </div>
        <div class="col-6">
            <!--
            table with all the appointments
            addapt the date patient and doctor
            -->
        </div>
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
                    fetch("api/administrator/assign_room/" + $("#rNumber").val() + "/" + id).then(er => {
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
                fetch("api/administrator/removeQueue/" + id).then(er => {
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
