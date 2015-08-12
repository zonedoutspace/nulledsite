<?php

include '../config/config.php';
include '../lib/email/mail_helper_functions.php';
extract($_POST);
//debug($_POST);

$MI_first_name = validateInput($MI_first_name);
$MI_last_name = validateInput($MI_last_name);
$MI_email_address = validateInput($MI_email_address);
$MI_address = validateInput($MI_address);
$MI_number = validateInput($MI_number);
$MI_event_title = validateInput($MI_event_title);
$MI_event_date_time = validateInput($MI_event_date_time);
$MI_is_closed_event = validateInput($MI_is_closed_event);
$MI_event_description = validateInput($MI_event_description);
$MI_about_ticket = validateInput($MI_about_ticket);
$MI_venue_name = validateInput($MI_venue_name);
$MI_venue_address = validateInput($MI_venue_address);
$MI_created_on = date("Y-m-d h:i:s");



if ($MI_first_name != "") {

    $addMerchantArray = '';
    $addMerchantArray .=' MI_first_name = "' . $MI_first_name . '"';
    $addMerchantArray .=', MI_last_name = "' . $MI_last_name . '"';
    $addMerchantArray .=', MI_email_address = "' . $MI_email_address . '"';
    $addMerchantArray .=', MI_address = "' . $MI_address . '"';
    $addMerchantArray .=', MI_number = "' . $MI_number . '"';
    $addMerchantArray .=', MI_event_title = "' . $MI_event_title . '"';
    $addMerchantArray .=', MI_event_date_time = "' . $MI_event_date_time . '"';
    $addMerchantArray .=', MI_is_closed_event = "' . $MI_is_closed_event . '"';
    $addMerchantArray .=', MI_event_description = "' . $MI_event_description . '"';
    $addMerchantArray .=', MI_about_ticket = "' . $MI_about_ticket . '"';
    $addMerchantArray .=', MI_venue_name = "' . $MI_venue_name . '"';
    $addMerchantArray .=', MI_venue_address = "' . $MI_venue_address . '"';
    $addMerchantArray .=', MI_created_on = "' . $MI_created_on . '"';



    $sqladdMerchant = "INSERT INTO merchant_info SET $addMerchantArray";
    $resultMerchant = mysqli_query($con, $sqladdMerchant);
    if ($resultMerchant) {
        $formID = mysqli_insert_id($con);
        $EmailSubject = "Thank you for requesting new event with TicketChai";
        $EmailBody = file_get_contents(baseUrl('email/body/event_request_form.php?form_id=' . $formID));
        $sendMailStatus = sendEmailFunction($MI_email_address, $MI_first_name, 'noreply@ticketchai.com', $EmailSubject, $EmailBody);

        if ($sendMailStatus) {

            $return_array = array("output" => "success", "msg" => "Event Request Submitted Successfully");
            echo json_encode($return_array);
            exit();
        } else {
            $return_array = array("output" => "success", "msg" => "Event Request Submitted Successfully But Email Sending Failed");
            echo json_encode($return_array);
            exit();
        }
    } else {
        if (DEBUG) {
            $err = "resultMerchant error: " . mysqli_error($con);
        } else {
            $err = "resultMerchant query failed";
        }
    }
} else {
    $return_array = array("output" => "error", "msg" => "Merchant Event Info not saved");
    echo json_encode($return_array);
    exit();
}
?>