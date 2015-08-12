<?php

include '../config/config.php';
$return_array = array();
extract($_POST);

//$date = array();
//$date = explode("/", $user_DOB);
//$realDate = $date[2] . '-' . $date[0] . '-' . $date[1];

$user_id = validateInput($user_id);
$user_first_name = validateInput($user_first_name);
$user_last_name = validateInput($user_last_name);


if ($user_first_name !== "" && $user_id !== "") {


    $updateUserArray = '';
    $updateUserArray .=' user_first_name = "' . $user_first_name . '"';
    $updateUserArray .=',user_last_name = "' . $user_last_name . '"';

    $updateUserSql = "UPDATE users SET $updateUserArray WHERE user_id = $user_id";
    $runUpdateUser = mysqli_query($con, $updateUserSql);

    if ($runUpdateUser) {
       
        setSession('user_first_name', $user_first_name);
       
        $return_array = array("output" => "success", "user_first_name" => $user_first_name, "msg" => "Your account changed successfully");
        echo json_encode($return_array);
        exit();
    } else {
        $return_array = array("output" => "error", "msg" => "Your account not changed");
        echo json_encode($return_array);
        exit();
    }
}
?>