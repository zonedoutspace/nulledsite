<?php

include '../config/config.php';
include '../lib/email/mail_helper_functions.php';
$user_hash = session_id();
$uesr_email = "";
$user_first_name = "";
$user_last_name = "";
$user_social_id = "";
$user_social_type = "";
$user_verification = "";
$user_gender = "";
$user_social_type = '';
$countUserInfo = 0;
$countGetUser = 0;
$return_array = array();
$sessionID = session_id();
extract($_POST);


if ($user_email != "" && $user_social_type != "" && $user_social_id != "") {
    $checkSocialUserSql = "SELECT * FROM users WHERE user_email = '$user_email' AND"
            . " user_social_id = '$user_social_id' AND user_social_type = '$user_social_type'";
    $checkSocialUserResult = mysqli_query($con, $checkSocialUserSql);
    $countSocialUser = mysqli_num_rows($checkSocialUserResult);

    if ($countSocialUser == 0) {
        $user_first_name = validateInput($user_first_name);
        $user_last_name = validateInput($user_last_name);
        $user_email = validateInput($user_email);
        $user_social_id = validateInput($user_social_id);
        $user_social_type = validateInput($user_social_type);
        $user_hash = validateInput($user_hash);

        $insertSocialUserArray = '';
        $insertSocialUserArray .=' user_first_name = "' . $user_first_name . '"';
        $insertSocialUserArray .=',user_last_name = "' . $user_last_name . '"';
        $insertSocialUserArray .=',user_email = "' . $user_email . '"';
        $insertSocialUserArray .=',user_social_id = "' . $user_social_id . '"';
        $insertSocialUserArray .=',user_social_type = "' . $user_social_type . '"';
        $insertSocialUserArray .=',user_gender = "' . $user_gender . '"';
        $insertSocialUserArray .=',user_hash = "' . $user_hash . '"';
        $insertSocialUserArray .=',user_verification = "' . $user_verification . '"';
        $insertSocialUserArray .=',user_agree_tc = "' . "I AGREE" . '"';

        $runInsertSocialUserSql = "INSERT INTO users SET $insertSocialUserArray";
        $resultSocialArray = mysqli_query($con, $runInsertSocialUserSql);


        if ($resultSocialArray) {
            $userInfoSql = "SELECT * FROM users WHERE user_email = '$user_email' AND user_social_type='$user_social_type'";
            $resultUserInfo = mysqli_query($con, $userInfoSql);
            $countUserInfo = mysqli_num_rows($resultUserInfo);
            if ($countUserInfo >= 1) {
                while ($row = mysqli_fetch_object($resultUserInfo)) {
                    $userID = $row->user_id;
                    $user_email = $row->user_email;
                    $user_first_name = $row->user_first_name;
                    $user_verification = $row->user_verification;
                    $user_hash = $row->user_hash;


                    setSession('user_email', $user_email);
                    setSession('user_id', $userID);
                    setSession('user_first_name', $user_first_name);
                    setSession('user_verification', $user_verification);
                    setSession('user_hash', $user_hash);


                    //updating temp cart if exist with user id
                    $sqlUpdateTmpCart = "UPDATE event_temp_cart SET ETC_user_id=$userID WHERE ETC_session_id='$sessionID'";
                    $resulltUpdateTmpCart = mysqli_query($con, $sqlUpdateTmpCart);

                    //updating dynamic form values if exist
                    $sqlUpdateDynamicFormValue = "UPDATE event_form_values SET EFV_user_id=$userID WHERE EFV_session_id='$sessionID'";
                    $resultUpdateDynamicFormValue = mysqli_query($con, $sqlUpdateDynamicFormValue);

                    if ($resulltUpdateTmpCart && $resultUpdateDynamicFormValue) {

                        $EmailSubject = "Thank you for registering with TicketChai";
                        $EmailBody = file_get_contents(baseUrl('email/body/signup.php?user_id=' . $userID));
                        $sendMailStatus = sendEmailFunction($user_email, $user_first_name, 'noreply@ticketchai.com', $EmailSubject, $EmailBody);


                        if ($sendMailStatus) {
                            $return_array = array("output" => "success", "user_first_name" => $user_first_name, "msg" => "Customer saved successfully");
                            echo json_encode($return_array);
                            exit();
                        } else {
                            $return_array = array("output" => "error", "user_first_name" => $user_first_name, "msg" => "Login was successful but email sending failed.");
                            echo json_encode($return_array);
                            exit();
                        }
                    } else {
                        $return_array = array("output" => "error", "user_first_name" => $user_first_name, "msg" => "Login was successful but update process failed.");
                        echo json_encode($return_array);
                        exit();
                    }
                }
            }
        } else {
            $return_array = array("output" => "error", "msg" => "User registration failed.");
            echo json_encode($return_array);
            exit();
        }
    } else {

        $getUserSql = "SELECT * FROM users WHERE user_email = '$user_email' AND user_social_type = '$user_social_type' AND user_social_id = '$user_social_id'";
        $resultGetUser = mysqli_query($con, $getUserSql);
        $countGetUser = mysqli_num_rows($resultGetUser);
        if ($countGetUser >= 1) {
            while ($getUser = mysqli_fetch_object($resultGetUser)) {
                $userID = $getUser->user_id;
                $user_email = $getUser->user_email;
                $user_first_name = $getUser->user_first_name;
                $user_verification = $getUser->user_verification;
                $user_hash = $getUser->user_hash;

                setSession('user_email', $user_email);
                setSession('user_id', $userID);
                setSession('user_first_name', $user_first_name);
                setSession('user_verification', $user_verification);
                setSession('user_hash', $user_hash);

                //updating temp cart if exist with user id
                $sqlUpdateTmpCart = "UPDATE event_temp_cart SET ETC_user_id=$userID WHERE ETC_session_id='$sessionID'";
                $resulltUpdateTmpCart = mysqli_query($con, $sqlUpdateTmpCart);

                //updating dynamic form values if exist
                $sqlUpdateDynamicFormValue = "UPDATE event_form_values SET EFV_user_id=$userID WHERE EFV_session_id='$sessionID'";
                $resultUpdateDynamicFormValue = mysqli_query($con, $sqlUpdateDynamicFormValue);

                if ($resulltUpdateTmpCart && $resultUpdateDynamicFormValue) {
//                    $EmailSubject = "Thank you for registering with TicketChai";
//                    $EmailBody = "Thank you for registering with TicketChai";
//                    sendEmailFunction($user_email, $user_first_name, 'info@ticketchai.com', $EmailSubject, $EmailBody);

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
            $return_array = array("output" => "error", "msg" => "Login failed.");
            echo json_encode($return_array);
            exit();
        }
    }
} else {
    $return_array = array("output" => "error", "msg" => "Registration failed.");
    echo json_encode($return_array);
    exit();
}
?>
