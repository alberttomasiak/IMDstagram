$(document).ready(function() {

    //
    //      TRIGGERS
    //

    // LIKE BUTTON EVENT
    //$("#btnLike").on("click", like);
    $(".btnLike").on("click", like);
    // REALTIME USERNAME CHECK EVENT
    $("#username").on("keyup", realtimeUsernameCheck);
    // FOLLOW EVENT
    $("#btnFollow").on("click", follow);
    //$("#btnPlaceComment").on("click", placeComment);
    $(".btnPlaceComment").on("click", placeComment);


    //
    //      FUNCTIONS
    //

    /*function like(e){
        console.log("Like klik");
        var postID = $("#likePostID").val();

        $.post( "ajax/like.php", {postID:postID} )
            .done(function( response ) {

                if(response.status == 'success'){
                    console.log('Success');
                    if(response.action == 'liked'){
                        $("#btnLike").toggleClass("heart--like");
                        $("#likeCount").text(+$("#likeCount").text() + 1);
                    }else if(response.action == 'disliked'){
                        $("#btnLike").toggleClass("heart--like");
                        $("#likeCount").text(+$("#likeCount").text() - 1);
                    }
                }else{
                    console.log('Fail');
                }

            });
        e.preventDefault();
    }*/

    function like(e){
        var postID = $(this).attr("data-postid");
        var button = this;
        var counter = $(this).closest('.post').find('.likeCount');
        console.log("Like klik " + postID);

        $.post( "ajax/like.php", {postID:postID} )
            .done(function( response ) {

                if(response.status == 'success'){
                    console.log('Success');
                    if(response.action == 'liked'){
                        $(button).toggleClass("heart--like");
                        $(counter).text(+counter.text() + 1);
                        console.log(counter);
                    }else if(response.action == 'disliked'){
                        $(button).toggleClass("heart--like");
                        $(counter).text(+counter.text() - 1);
                        console.log(counter);
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

    function follow(e){
        console.log('clicked');
        var profileID = $(this).attr("data-id");
        var action = $(this).attr("data-action");
        console.log(profileID, action)
        $.post( "ajax/follow.php", {profileID:profileID, action:action} )
            .done(function( response ) {

                if(response.status == 'success'){
                    console.log('Success');
                    if(response.action == 'following'){
                        $("#btnFollow").val('Following');
                        $("#btnFollow").attr('class', 'btn btn-success');
                        $("#btnFollow").attr('data-action', 'stopfollowing');
                        $("#stats--followers").text(+$("#stats--followers").text()+1);
                    }else if(response.action == 'notfollowing'){
                        $("#btnFollow").val('Follow');
                        $("#btnFollow").attr('class', 'btn btn-primary');
                        $("#btnFollow").attr('data-action', 'follow');
                        $("#stats--followers").text(+$("#stats--followers").text()-1);
                    }else if(response.action == 'pending'){
                        $("#btnFollow").val('Pending');
                        $("#btnFollow").attr('class', 'btn btn-default');
                        $("#btnFollow").attr('data-action', 'stopfollowing');
                    }
                }else{
                    console.log('Fail');
                }
            });
        e.preventDefault();
    };

    // PLACE COMMENT
    function placeComment(e){
        console.log('click');
        var button = this;
        var inputfield = $(this).closest('.post__actions').find('.inputComment');
        var comment = $(this).closest('form').find('.inputComment').val();
        var postID = $(this).attr("data-postid");
        var list = $(this).closest('.post').find('.comments__list');
        console.log("comment: "+comment);
        console.log("postID: "+postID);
        console.log(list);
        $.post( "ajax/comment.php", {comment:comment, postID:postID} )
            .done(function( response ) {

                if(response.status == 'success'){
                    console.log('Success');
                    var url = "'profile.php?profile=" + response.username + "'";
                    var li = "<li class='comments__list__item'><p><a href="+ url +">"+ response.username +"</a> "+ response.text +"</p></li>";
                    $(list).append(li);
                    $(inputfield).val("");
                }else{
                    console.log('Fail');
                }
            });
        e.preventDefault();
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
	
	// POST FLAG
	$('.post__flag').on("click", function(e){
					var flagID = $(this).parent().find('input[type="hidden"]').val();
						$.ajax({
							url: "ajax/flagpost.php",
							type: "POST",
							data: {flagID: flagID},
							dataType: 'json',
							cache: false,
							success: function(status){
								if(status.check == "true"){
									$('.f'+flagID).addClass('flagged');
								}else if(status.check != "true"){
									$('.f'+flagID).removeClass('flagged');
								}
							},
							error: function (request, status, error) {
								console.log(error);
						}
					});
				e.preventDefault();
			});
	

});