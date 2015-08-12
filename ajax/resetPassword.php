<?php

include '../config/config.php';
include '../lib/email/mail_helper_functions.php';
$return_array = array();
$user_email = "";
$user_hash_new = session_id();
$new_password = "";
$user_id = "";
$user_first_name = "";
extract($_POST);

$user_email = validateInput($user_email);
$new_password = validateInput($new_password);
$user_password = securedPass($new_password);

if ($user_email != "" AND $user_hash != "") {

    $sqlVerifiedUser = "SELECT * FROM users WHERE user_email = '$user_email'";
    $resultVerifiedUser = mysqli_query($con, $sqlVerifiedUser);
    if ($resultVerifiedUser) {
        $countVerifiedUser = mysqli_num_rows($resultVerifiedUser);
        $resultVerifiedUserObj = mysqli_fetch_object($resultVerifiedUser);
        $user_verification = $resultVerifiedUserObj->user_verification;
        $user_id = $resultVerifiedUserObj->user_id;
        $user_first_name = $resultVerifiedUserObj->user_first_name;

        $updatePassArray = '';
        $updatePassArray .=' user_password = "' . $user_password . '"';
        $updatePassArray .=',user_hash = "' . $user_hash_new . '"';
        $updatePassArray .=',user_verification = "' . $user_verification . '"';


        $updateSql = "UPDATE users SET $updatePassArray WHERE user_id = $user_id";
        $resultUpdateSql = mysqli_query($con, $updateSql);
        if ($resultUpdateSql) {

            $EmailSubject = "Password Change Notification";
            $EmailBody = file_get_contents(baseUrl('email/body/reset_password.php?user_id=' . $user_id));
            $sendMailStatus = sendEmailFunction($user_email, $user_first_name, 'noreply@ticketchai.com', $EmailSubject, $EmailBody);

//            $EmailSubject = "Password change notification";
//            $EmailBody = "Hi " . $user_first_name . " your password has been changed successfully at TicketChai.";
//            $sendEmail = sendEmailFunction($user_email, $user_first_name, 'info@ticketchai.com', $EmailSubject, $EmailBody);

            if ($sendMailStatus) {
                setSession('user_verification', $user_verification);
                setSession('user_hash', $user_hash);

                $return_array = array("output" => "success", "msg" => "Password changed successfully.");
                echo json_encode($return_array);
                exit();
            } else {
                $return_array = array("output" => "error", "msg" => "Password not changed. Something went wrong.");
                echo json_encode($return_array);
                exit();
            }
        } else {
            if (DEBUG) {
                $return_array = array("output" => "error", "msg" => "resultUpdateSql error: " . mysqli_error($con));
                echo json_encode($return_array);
                exit();
            } else {
                $return_array = array("output" => "error", "msg" => "resultUpdateSql query failed.");
                echo json_encode($return_array);
                exit();
            }
        }
    } else {
        if (DEBUG) {
            $return_array = array("output" => "error", "msg" => "resultVerifiedUser error: " . mysqli_error($con));
            echo json_encode($return_array);
            exit();
        } else {
            $return_array = array("output" => "error", "msg" => "resultVerifiedUser query failed.");
            echo json_encode($return_array);
            exit();
        }
    }
} else {
    $return_array = array("output" => "error", "msg" => "Password not changed");
    echo json_encode($return_array);
    exit();
}
?>