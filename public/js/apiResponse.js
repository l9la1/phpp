// This is to show the returned data in a div
function showMess(mess, callback = null) {
    mess.json().then(mes => {
        if (mes.suc) {
            var m = "";
            if (testJSON(mes.suc)) {
                for (let key in mes.suc)
                    for (let i in mes.suc[key])
                        m += mes.suc[key][i] + "<br/>";
            } else
                m = mes.suc;
            $("#messages").addClass("alert-success");
            $("#messages").html(m);
            $("#messages").slideDown("slow", "linear", function () {
                setTimeout(function () {
                    $("#messages").slideUp("slow", "linear");
                    $("#messages").text("");
                    $("#messages").removeClass("alert-success");
                    if (callback != null)
                        callback();
                }, 5000);
            })
        } else if (mes.err) {
            var m = "";
            if (testJSON(mes.err)) {
                for (let key in mes.err)
                    for (let i in mes.err[key])
                        m += mes.err[key][i] + "<br/>";
            } else
                m = mes.err;
            $("#messages").addClass("alert-danger");
            $("#messages").html(m);
            $("#messages").slideDown("slow", "linear", function () {
                setTimeout(function () {
                    $("#messages").slideUp("slow", "linear", function () {
                        $("#messages").removeClass("alert-danger");
                        $("#messages").text("");
                        if (callback != null)
                            callback();
                    });
                }, 5000);
            })
        }
        document.getElementById('messages').scrollIntoView({ 
            behavior: 'smooth' 
          });
    });
}

function testJSON(text) {
    if(typeof text ==="string") return false;
    else return true;
}