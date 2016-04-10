// LIKE POST SCRIPT
$(document).ready(function() {
    $("#btnLike").on("click", function(e){
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
    });
});
