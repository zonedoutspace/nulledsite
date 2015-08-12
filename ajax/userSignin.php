
<?php

include '../config/config.php';
$return_array = array();
$countUserLogin = 0;
$sessionID = session_id();
extract($_POST);

if ($user_email != "" AND $user_password != "") {
    $user_email = validateInput($user_email);
    $user_LoginPassword = validateInput($user_password);
    $user_password = securedPass($user_LoginPassword);

    if ($user_email) {
        $checkUserSql = "SELECT * FROM users WHERE user_email = '$user_email' AND user_password = '$user_password'";
        $checkUserResult = mysqli_query($con, $checkUserSql);
        $countUserLogin = mysqli_num_rows($checkUserResult);
        if ($countUserLogin > 0) {
            while ($row = mysqli_fetch_object($checkUserResult)) {
                $userID = $row->user_id;
                $user_first_name = $row->user_first_name;
                $user_verification = $row->user_verification;
                $user_hash = $row->user_hash;

                //setting session for user
                setSession('user_email', $user_email);
                setSession('user_id', $userID);
                setSession('user_first_name', $user_first_name);
                setSession('user_verification', $user_verification);
                setSession('user_hash', $user_hash);
                
                //updating temp cart if exist with user id
                $sqlUpdateTmpCart = "UPDATE event_temp_cart SET ETC_user_id=$userID WHERE ETC_session_id='$sessionID'";
                $resulltUpdateTmpCart = mysqli_query($con,$sqlUpdateTmpCart);
                
                //updating dynamic form values if exist
                $sqlUpdateDynamicFormValue = "UPDATE event_form_values SET EFV_user_id=$userID WHERE EFV_session_id='$sessionID'";
                $resultUpdateDynamicFormValue = mysqli_query($con,$sqlUpdateDynamicFormValue);
                
                if($resulltUpdateTmpCart && $resultUpdateDynamicFormValue){
                    $return_array = array("output" => "success", "user_first_name" => $user_first_name, "msg" => "Successfully logged in");
                    echo json_encode($return_array);
                    exit();
                } else {
                    $return_array = array("output" => "error", "user_first_name" => $user_first_name, "msg" => "Login was successful but update process failed.");
                    echo json_encode($return_array);
                    exit();
                }
            }
        } else {
            $return_array = array("output" => "error", "msg" => "Invalid email or password");
            echo json_encode($return_array);
            exit();
        }
    } else {
        $return_array = array("output" => "error", "msg" => "Invalid email or password");
        echo json_encode($return_array);
        exit();
    }
}
?>
