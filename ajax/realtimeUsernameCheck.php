<?php
include_once('../classes/User.class.php');

$user = new User();

if(!empty($_POST['username']))
{

    $user->Username = $_POST['username'];
    $nameAvailability = $user->UsernameAvailable();

    if($nameAvailability == 1){
        $availability['status'] = 'available';
        $availability['message'] = 'Username available';
    }else{
        $availability['status'] = 'taken';
        $availability['message'] = 'Username taken';
    }

    header('Content-type: application/json');
    echo json_encode($availability);

}
?>