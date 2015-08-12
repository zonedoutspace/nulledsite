<?php

include '../config/config.php';
include '../lib/email/mail_helper_functions.php';
$user_new_password = "";
$user_old_password = "";
$countPasswordRow = 0;
$user_email = getSession('user_email');
$user_id = getSession('user_id');
$user_first_name = getSession('user_first_name');
extract($_POST);
$user_old_password_input = validateInput($user_old_password);
$user_old_password = securedPass($user_old_password_input);
$user_new_password_input = validateInput($user_new_password);
$user_new_password = securedPass($user_new_password_input);

if ($user_old_password != "") {
    $matchPasswordSql = "SELECT * FROM users WHERE user_password = '$user_old_password' AND user_email = '$user_email'";
    $resultMatchPassword = mysqli_query($con, $matchPasswordSql);
    if ($resultMatchPassword) {
        $countPasswordRow = mysqli_num_rows($resultMatchPassword);
        if ($countPasswordRow > 0) {
            //echo "Oh Yes";exit();
            $updatePassArray = '';
            $updatePassArray .=' user_password = "' . $user_new_password . '"';

            $updatePasswordSql = "UPDATE users SET $updatePassArray WHERE user_id = $user_id AND user_email = '$user_email'";
            $resultUpdatePasswordSql = mysqli_query($con, $updatePasswordSql);

            if ($resultUpdatePasswordSql) {


                $EmailSubject = "Password Change Notification";
                $EmailBody = file_get_contents(baseUrl('email/body/change_password.php?user_id=' . $user_id));
                $sendMailStatus = sendEmailFunction($user_email, $user_first_name, 'noreply@ticketchai.com', $EmailSubject, $EmailBody);

//                $EmailSubject = "Password change notification";
//                $EmailBody = "Hi " . $user_first_name . " your password has been changed successfully at TicketChai.";
//                $sendEmail = sendEmailFunction($user_email, $user_first_name, 'info@ticketchai.com', $EmailSubject, $EmailBody);

                if ($sendMailStatus) {
                    $return_array = array("output" => "success", "msg" => "Password changed successfully");
                    echo json_encode($return_array);
                    exit();
                } else {
                    $return_array = array("output" => "error", "msg" => "Password not changed");
                    echo json_encode($return_array);
                    exit();
                }
            } else {
                if (DEBUG) {
                    $return_array = array("output" => "error", "msg" => "resultUpdatePasswordSql error: " . mysqli_error($con));
                    echo json_encode($return_array);
                    exit();
                } else {
                    $return_array = array("output" => "error", "msg" => "resultUpdatePasswordSql query failed.");
                    echo json_encode($return_array);
                    exit();
                }
            }
        } else {
            // echo "Damnn IT";exit();
            $return_array = array("output" => "error", "msg" => "You entered wrong password");
            echo json_encode($return_array);
            exit();
        }
    } else {
        if (DEBUG) {
            $return_array = array("output" => "error", "msg" => "resultUpdatePasswordSql error: " . mysqli_error($con));
            echo json_encode($return_array);
            exit();
        } else {
            $return_array = array("output" => "error", "msg" => "resultUpdatePasswordSql query failed.");
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

