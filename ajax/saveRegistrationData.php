<?php

include '../config/config.php';
include '../lib/email/mail_helper_functions.php';
$user_hash = session_id();
$return_array = array();
$countUser = 0;
$user_email = '';
extract($_POST);

if ($user_email != "") {
    $checkUser = "SELECT * FROM users WHERE user_email = '$user_email'";
    $checkResult = mysqli_query($con, $checkUser);
    $countUser = mysqli_num_rows($checkResult);
    if ($countUser >= 1) {
        $return_array = array("output" => "error", "msg" => "Customer already exists");
        echo json_encode($return_array);
        exit();
    } else {
        $user_first_name = validateInput($user_first_name);
        $user_email = validateInput($user_email);
        $user_signup_password = validateInput($user_password);
        $user_password = securedPass($user_signup_password);
        $user_verification = "no";


        $insertUserArray = '';
        $insertUserArray .=' user_first_name = "' . $user_first_name . '"';
        $insertUserArray .=', user_email = "' . $user_email . '"';
        $insertUserArray .=', user_password = "' . $user_password . '"';
        $insertUserArray .=', user_verification = "' . $user_verification . '"';
        $insertUserArray .=', user_hash = "' . $user_hash . '"';
        $insertUserArray .=', user_agree_tc = "' . "I AGREE" . '"';

        $runUserArray = "INSERT INTO users SET $insertUserArray";
        $result = mysqli_query($con, $runUserArray);

        if ($result) {
            $userID = mysqli_insert_id($con);
            setSession('user_id', $userID);
            setSession('user_email', $user_email);
            setSession('user_first_name', $user_first_name);
            setSession('user_verification', $user_verification);
            setSession('user_hash', $user_hash);


            //updating temp cart if exist with user id
            $sqlUpdateTmpCart = "UPDATE event_temp_cart SET ETC_user_id=$userID WHERE ETC_session_id='$user_hash'";
            $resulltUpdateTmpCart = mysqli_query($con, $sqlUpdateTmpCart);

            //updating dynamic form values if exist
            $sqlUpdateDynamicFormValue = "UPDATE event_form_values SET EFV_user_id=$userID WHERE EFV_session_id='$user_hash'";
            $resultUpdateDynamicFormValue = mysqli_query($con, $sqlUpdateDynamicFormValue);

            if ($resulltUpdateTmpCart && $resultUpdateDynamicFormValue) {

                $EmailSubject = "Thank you for registering with TicketChai";
                $EmailBody = file_get_contents(baseUrl('email/body/signup.php?user_id=' . $userID));
                $sendMailStatus = sendEmailFunction($user_email, $user_first_name, 'noreply@ticketchai.com', $EmailSubject, $EmailBody);
                
                if($sendMailStatus){
                    $return_array = array("output" => "success", "user_first_name" => $user_first_name, "msg" => "Customer saved successfully");
                    echo json_encode($return_array);
                    exit();
                }else{
                    $return_array = array("output" => "error", "user_first_name" => $user_first_name, "msg" => "Login was successful but email sending failed.");
                    echo json_encode($return_array);
                    exit();
                }
            } else {
                $return_array = array("output" => "error", "user_first_name" => $user_first_name, "msg" => "Login was successful but update process failed.");
                echo json_encode($return_array);
                exit();
            }

        } else {
            $return_array = array("output" => "error", "msg" => "Customer is not inserted");
            echo json_encode($return_array);
            exit();
        }
    }
}
?>
