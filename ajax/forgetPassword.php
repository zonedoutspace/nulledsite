<?php

include '../config/config.php';
include '../lib/email/mail_helper_functions.php';
$user_hash = "";
$user_email = "";
$return_array = array();
$countValidUser = 0;
$userID = 0;
$user_first_name = "";
extract($_POST);


if ($user_email != "") {
    $checkValidUser = "SELECT * FROM users WHERE user_email = '$user_email'";
    $resultValidUser = mysqli_query($con, $checkValidUser);
    if ($resultValidUser) {
        $countValidUser = mysqli_num_rows($resultValidUser);
        $resultValidUserObj = mysqli_fetch_object($resultValidUser);
        if (isset($resultValidUserObj->user_hash)) {
            $user_hash = $resultValidUserObj->user_hash;
            $userID = $resultValidUserObj->user_id;
            $user_first_name = $resultValidUserObj->user_first_name;
        }
    } else {
        if (DEBUG) {
            $return_array = array("output" => "error", "msg" => "resultValidUser error: " . mysqli_error($con));
            echo json_encode($return_array);
            exit();
        } else {
            $return_array = array("output" => "error", "msg" => "resultValidUser query failed.");
            echo json_encode($return_array);
            exit();
        }
    }

    if ($countValidUser >= 1) {
        if ($resultValidUserObj->user_social_type == '') {
            $user_email = validateInput($user_email);

            $EmailSubject = "Password Change Request";
            $EmailBody = file_get_contents(baseUrl('email/body/forget_password.php?user_id=' . $userID));
            $sendMailStatus = sendEmailFunction($user_email, $user_first_name, 'noreply@ticketchai.com', $EmailSubject, $EmailBody);

//            $EmailSubject = "Password change request";
//            $EmailBody = "If you want to reset your password please click the link to set your new password."
//                    . "<a href='" . baseUrl() . "reset_password.php?m=" . base64_encode($user_email) . "&h=" . $user_hash . "'>Click Here</a>";
//            $sendEmail = sendEmailFunction($user_email, $resultValidUserObj->user_first_name, 'info@ticketchai.com', $EmailSubject, $EmailBody);
            if ($sendMailStatus) {
                $return_array = array("output" => "success", "msg" => "An email with password reset option already sent");
                echo json_encode($return_array);
                exit();
            } else {
                $return_array = array("output" => "error", "msg" => "Email sending failed.");
                echo json_encode($return_array);
                exit();
            }
        } elseif ($resultValidUserObj->user_social_type == 'google') {
            $return_array = array("output" => "info", "msg" => "Please use Google+ to login with this email.");
            echo json_encode($return_array);
            exit();
        } elseif ($resultValidUserObj->user_social_type == 'facebook') {
            $return_array = array("output" => "info", "msg" => "Please use Facebook to login with this email.");
            echo json_encode($return_array);
            exit();
        }
    } else {
        $return_array = array("output" => "error", "msg" => "Email address not found in record");
        echo json_encode($return_array);
        exit();
    }
}
?>