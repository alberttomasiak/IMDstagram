$(document).ready(function() {

    //
    //      TRIGGERS
    //

    // LIKE BUTTON EVENT
    $("#btnLike").on("click", like);
    // REALTIME USERNAME CHECK EVENT
    $("#username").on("keyup", realtimeUsernameCheck);
    // FOLLOW EVENT
    //$("#btnFollow").on("click", follow);


    //
    //      FUNCTIONS
    //

    // LIKE FUNCTION (like or stop liking a post)
    function like(e){
        console.log("Like klik");
        var postID = $(this).attr("data-postid");
        var action = $(this).attr("data-action");

        $.post( "ajax/like.php", {postID:postID, action:action} )
            .done(function( response ) {

                if(response.status == 'success'){
                    console.log('Success');
                    if(response.action == 'liked'){
                        $("#btnLike").toggleClass("liked");
                        $("#btnLike").attr('data-action', 'dislike');
                        $("#likeCount").text(+$("#likeCount").text() + 1);
                    }else if(response.action == 'disliked'){
                        $("#btnLike").toggleClass("liked");
                        $("#btnLike").attr('data-action', 'like');
                        $("#likeCount").text(+$("#likeCount").text() - 1);
                    }
                }else{
                    console.log('Fail');
                }

            });
        e.preventDefault();
    }

    // REALTIME USERNAME CHECK FUNCTION (check if username is available)
    function realtimeUsernameCheck(e){
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
    }

    // FOLLOW FUNCTION (follow and stop following a user)
    function follow(e){
        var followingID = $(this).attr("data-id");
        var action = $(this).attr("data-action");
        console.log(followingID, action)
        $.post( "ajax/follow.php", {followingID:followingID, action:action} )
            .done(function( response ) {

                if(response.status == 'success'){
                    console.log('Success');
                    if(response.action == 'following'){
                        $("#btnFollow").val('Following');
                        $("#btnFollow").toggleClass("active");
                        $("#btnFollow").attr('data-action', 'stopfollowing');
                    }else if(response.action == 'notfollowing'){
                        $("#btnFollow").val('Follow');
                        $("#btnFollow").toggleClass("active");
                        $("#btnFollow").attr('data-action', 'follow');
                    }
                }else{
                    console.log('Fail');
                }

            });
        //e.preventDefault();
    }
	
	// SEARCH FUNCTION
	$("#searchBox").keyup(function(){
        var searchQuery = $(this).val();

        $.post('ajax/searchForContent.php', {searchQuery: searchQuery}, function(data){
            if(data == ""){
                $("#searchResults").html("No relevant items found.");
                $('#searchResults').css('display','block');
            }
            else if($('#searchBox').val() == ""){
                $("#searchResults").html("");
                $('#searchResults').css('display','none');
            }else if ($('#searchBox').val() != ""){
                $('#searchResults').css('display', 'block');
                $('#searchResults').html(data);
            }

        });
    });
	
	// UPLOAD POST CHECK
	$("#postForm").submit(function(e){
		var $inputFile = $("#fileToUpload").val();
			
		if($inputFile == "" || $("#postMessage").val() == ""){
			$("#postFeedback").html("The fields cannot be empty.");
			e.preventDefault();
		}else if($inputFile != "" && $("#postMessage") != ""){
			$("#postForm").submit();
			$("#postFeedback").html("");
		}
	});
	
	// UPLOAD POST + FILTER
	
	

});