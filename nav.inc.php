<?php
include_once("classes/Db.class.php");
include_once("classes/User.class.php");

?><nav class="navbar navbar-default">

    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="index.php">
                <img alt="Brand" src="public/images/logo.png">
            </a>
        </div>
        <form class="navbar-form navbar-left" action="" method="post" role="search">
            <div class="form-group">
                <input type="text" id="searchBox" name="search" class="searchid form-control" placeholder="Search">
                <ul id="searchResults"></ul>
            </div>
        </form>
        
        <ul class="nav navbar-nav navbar-right">
            <li><a href="profile.php?profile=<?php echo $_SESSION['username']; ?>"><?php if($_SESSION['loggedin']==true){ echo $_SESSION['username']; } ?></a></li>
            <li><a href="logout.php" class="">Log out</a></li>
        </ul>

    </div>


</nav>

<link rel="stylesheet" href="public/css/style.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script>
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

</script>

<!--
<nav class="navbar navbar-default">

    <ul>
        <li>
            <a href="index.php" class="homeLogo">logo</a>
        </li>
        <li>
            <h4><a class="Log Out" href="logout.php"><a class="username" href="profile.php?profile=<?php echo $_SESSION['username']; ?>"><?php if($_SESSION['loggedin']==true){ echo $_SESSION['username']; } ?></a></a></h4>
        </li>
    </ul>
</nav>-->
