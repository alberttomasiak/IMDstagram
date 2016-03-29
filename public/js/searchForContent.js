$(document).ready(function(){
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
});