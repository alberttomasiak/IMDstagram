$(document).ready(function(){
    $("#username").on("keyup", function(e){
        // username ophalen uit het textvak
        var inputUsername = $("#username").val();

        // display feedback div als de textbox niet leeg is
        if($("#username").val() == ""){
            $( ".usernameFeedback" ).hide();
        }else{
            $( ".usernameFeedback" ).show();
        }


        // AJAX call, verzenden naar php file om query uit te voeren
        $.post( "ajax/realtimeUsernameCheck.php", { username: inputUsername })
            .done(function( availability ) {

                if(availability.status == 'available'){
                    // feedback geven
                    $(".usernameFeedback").text( "Username available" );
                }else{
                    $(".usernameFeedback").text( "Username taken" );
                }

            });

    });
});