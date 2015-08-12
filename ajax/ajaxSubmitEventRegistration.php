<?php

include '../config/config.php';

//debug($_POST);
//debug($_FILES);

if (count($_POST) > 0) {
    $sessionID = session_id();
    $eventID = 0;
    $checkStatus = 0;
    
    foreach ($_POST AS $key => $val) {
        if ($key == "eventID") {
            $eventID = $val;
        }
    }


    foreach ($_FILES AS $key => $val) {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            $arrGetID = explode('|', $key);
            $fieldName = $arrGetID[0];
            $fieldID = $arrGetID[1];
            $arrFileType = array('image/png', 'image/gif', 'image/jpeg', 'image/pjpeg', 'text/plain', 'application/pdf', 'application/msword', 'application/vnd.ms-excel', 'video/mp4');
//        debug($arrFileType);
            if (in_array($_FILES[$key]['type'], $arrFileType)) {
                if ($_FILES[$key]["size"] < 5242880) {
                    $uploadedFile = basename($_FILES[$key]['name']);
                    $info = pathinfo($uploadedFile, PATHINFO_EXTENSION); /* it will return me like jpeg, gif, pdf, png */
                    $newFileName = 'FieldName-' . $fieldName . '--FieldID-' . $fieldID . '--DateTime-' . date("Y-m-d-H-i-s") . '.' . $info; /* create custom image name color id will add  */
                    $uploadedFileSource = $_FILES[$key]["tmp_name"];
                    /*                     * *****Renaming the image file******** */

                    if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/dynamic_form_upload/')) {
                        mkdir($config['IMAGE_UPLOAD_PATH'] . '/dynamic_form_upload/', 0777, TRUE);
                    }
                    $uploadedFileTarget = $config['IMAGE_UPLOAD_PATH'] . '/dynamic_form_upload/' . $newFileName;

                    if (move_uploaded_file($uploadedFileSource, $uploadedFileTarget)) {
                        $insertFieldValue = '';
                        $insertFieldValue .=' EFV_event_id = "' . intval($eventID) . '"';
                        $insertFieldValue .=', EFV_session_id = "' . validateInput($sessionID) . '"';
                        $insertFieldValue .=', EFV_user_id = "' . intval(0) . '"';
                        $insertFieldValue .=', EFV_field_id = "' . intval($fieldID) . '"';
                        $insertFieldValue .=', EFV_field_value = "' . validateInput($newFileName) . '"';

                        $sqlInsertFieldValue = "INSERT INTO event_form_values SET $insertFieldValue";
                        $resultInsertFieldValue = mysqli_query($con, $sqlInsertFieldValue);

                        if (!$resultInsertFieldValue) {
                            $checkStatus++;
                        }
                    } else {
                        $return_array = array("output" => "error", "msg" => "File upload failed. Please try again.");
                        echo json_encode($return_array);
                        exit();
                    }
                }
            } else {
                $return_array = array("output" => "error", "msg" => "Invalid file type.");
                echo json_encode($return_array);
                exit();
            }
        } else {
            $return_array = array("output" => "error", "msg" => "Invalid request type.");
            echo json_encode($return_array);
            exit();
        }
//        debug($val);
    }

    
    foreach ($_POST AS $key => $val) {
        if ($key != "eventID") {
            $arrGetID = explode('|', $key);
            $fieldID = $arrGetID[1];
            $fieldValue = $val;

            $insertFieldValue = '';
            $insertFieldValue .=' EFV_event_id = "' . intval($eventID) . '"';
            $insertFieldValue .=', EFV_session_id = "' . validateInput($sessionID) . '"';
            $insertFieldValue .=', EFV_user_id = "' . intval(0) . '"';
            $insertFieldValue .=', EFV_field_id = "' . intval($fieldID) . '"';
            $insertFieldValue .=', EFV_field_value = "' . validateInput($fieldValue) . '"';

            $sqlInsertFieldValue = "INSERT INTO event_form_values SET $insertFieldValue";
            $resultInsertFieldValue = mysqli_query($con, $sqlInsertFieldValue);

            if (!$resultInsertFieldValue) {
                $checkStatus++;
            }
        }
    }

    if ($checkStatus > 0) {
        $return_array = array("output" => "error", "msg" => "Registration data save failed.");
        echo json_encode($return_array);
        exit();
    } else {
        $_SESSION['REGISTRATION-FORM-' . $eventID] = "yes";
        $return_array = array("output" => "success", "msg" => "Registration data saved successfully");
        echo json_encode($return_array);
        exit();
    }
}