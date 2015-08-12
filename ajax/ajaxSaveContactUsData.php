<?php

include '../config/config.php';
include '../lib/email/mail_helper_functions.php';
$UserID = 0;

if (checkUserLogin()) {
    $UserID = getSession('user_id');
}
extract($_POST);

$CU_name = validateInput($CU_name);
$CU_email = validateInput($CU_email);
$CU_subject = validateInput($CU_subject);
$CU_message = validateInput($CU_message);
$CU_created_by = $UserID;

if ($CU_name != "") {

    $addContactUsArray = '';
    $addContactUsArray .=' CU_name = "' . $CU_name . '"';
    $addContactUsArray .=', CU_email = "' . $CU_email . '"';
    $addContactUsArray .=', CU_subject = "' . $CU_subject . '"';
    $addContactUsArray .=', CU_message = "' . $CU_message . '"';
    $addContactUsArray .=', CU_created_by = "' . $CU_created_by . '"';


    $sqladdContactUs = "INSERT INTO contact_us SET $addContactUsArray";
    $resultContactUs = mysqli_query($con, $sqladdContactUs);
    if ($resultContactUs) {
        $ID = mysqli_insert_id($con);

        $EmailSubject = "Thank you for sending your query with TicketChai";
        $EmailBody = file_get_contents(baseUrl('email/body/contact_us_email.php?id=' . $ID));
        $sendMailStatus = sendEmailFunction($CU_email, $CU_name, 'noreply@ticketchai.com', $EmailSubject, $EmailBody);

        if ($sendMailStatus) {
            $return_array = array("output" => "success", "msg" => "Information Send Successfully");
            echo json_encode($return_array);
            exit();
        } else {
            $return_array = array("output" => "success", "msg" => "Information Send Successfully But Email Sending Failed");
            echo json_encode($return_array);
            exit();
        }
    } else {
        if (DEBUG) {
            $err = "resultContactUs error: " . mysqli_error($con);
        } else {
            $err = "resultContactUs query failed";
        }
    }
} else {
    $return_array = array("output" => "error", "msg" => "Information Not Send");
    echo json_encode($return_array);
    exit();
}
?>