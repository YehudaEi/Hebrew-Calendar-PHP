function messageAlert(s,a,e=!1){var l='<div class="alert alert-'+a+' alert-dismissible">'+s+'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';e?$("#message").html(l):$("#message").append(l)}

$(document).ready(function(){
    $(document).on("click", "#submit", function(){
        $("#submit").attr('disabled','disabled');
        $("#submit").text("טוען...");

        $.ajax({
            type: "POST",
            url: "api.php",
            data: JSON.stringify({
                date: $("#date").val(),
                name: $("#name").val(),
                night: $("#night")[0].checked,
                type: $("#type").val()
            }),
            contentType: "application/json; charset=utf-8",
            success: function(data){
                $("#submit").removeAttr('disabled');
                $("#submit").text("חשב");
                $("#result").html(data);
            },
            failure: function(errMsg){messageAlert(errMsg, "danger");}
        });
    });
});