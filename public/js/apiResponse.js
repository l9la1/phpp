function showMess(mess,callback=null) {
    mess.json().then(mes => {
        if (mes.suc) {
            $("#messages").addClass("alert-success");
            $("#messages").html(mes.suc);
            $("#messages").slideDown("slow", "linear", function () {
                setTimeout(function () {
                    $("#messages").slideUp("slow", "linear");
                    $("#messages").text("");
                    $("#messages").removeClass("alert-success");
                    if(callback!=null)
                        callback();
                }, 5000);
            })
        } else if (mes.err) {
            var m="";
            for(let key in mes.err)
                for(let i in mes.err[key])
                m+=mes.err[key][i]+"<br/>";
            $("#messages").addClass("alert-danger");
            $("#messages").html(m);
            $("#messages").slideDown("slow", "linear", function () {
                setTimeout(function () {
                    $("#messages").slideUp("slow", "linear");
                    $("#messages").text("");
                    $("#messages").removeClass("alert-danger");
                    if(callback!=null)
                        callback();
                }, 5000);
            })
        }
    });
}