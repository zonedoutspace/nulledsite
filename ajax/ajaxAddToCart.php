<?php

include '../config/config.php';

$itemID = 0;
$type = '';
$quantity = 0;
$eventID = 0;
$venueID = 0;
$countRecord = 0;
$countItems = 0;
$sessionID = session_id();
$userID = 0;
$ETC_id = 0;
$error_return = array();
$return_array = array();
if ((getSession('user_id'))) {
    $userID = getSession('user_id');
}

extract($_POST);
$checkStatus = 0;

if ($itemID > 0 AND $type != "" AND $quantity > 0 AND $eventID > 0 AND $venueID > 0) {

    //checking if event already saved in temp cart
    $sqlSearchEvent = "SELECT ETC_id FROM event_temp_cart WHERE ETC_event_id=$eventID AND ETC_session_id='$sessionID'";
    $resultSearchEvent = mysqli_query($con, $sqlSearchEvent);
    if ($resultSearchEvent) {
        $countRecord = mysqli_num_rows($resultSearchEvent);

        if ($countRecord == 0) { //event not saved in temp cart
            $insertEventTmpCart = '';
            $insertEventTmpCart .=' ETC_event_id = "' . intval($eventID) . '"';
            $insertEventTmpCart .=', ETC_session_id = "' . mysqli_real_escape_string($con, $sessionID) . '"';
            $insertEventTmpCart .=', ETC_user_id = "' . intval($userID) . '"';

            $sqlInsertEventTmpCart = "INSERT INTO event_temp_cart SET $insertEventTmpCart";
            $resultInsertEventTmpCart = mysqli_query($con, $sqlInsertEventTmpCart);

            if ($resultInsertEventTmpCart) {
                $ETC_id = mysqli_insert_id($con);
            } else {
                $checkStatus++;
                if (DEBUG) {
                    $return_array = array("output" => "error", "msg" => "resultInsertEventTmpCart error: " . mysqli_error($con));
                    echo json_encode($return_array);
                    exit();
                } else {
                    $return_array = array("output" => "error", "msg" => "resultInsertEventTmpCart query failed.");
                    echo json_encode($return_array);
                    exit();
                }
            }
        } else { //event already exist in temp cart
            $resultSearchEventObj = mysqli_fetch_object($resultSearchEvent);
            if (isset($resultSearchEventObj->ETC_id)) {
                $ETC_id = $resultSearchEventObj->ETC_id;
            }
        }




        if ($type == "ticket") {

            //checking if event item already exist in table
            $sqlSearchEventItem = "SELECT EITC_id FROM event_item_temp_cart WHERE EITC_venue_id=$venueID AND EITC_item_id=$itemID AND EITC_session_id='$sessionID' AND EITC_item_type='$type'";
            $resultSearchEventItem = mysqli_query($con, $sqlSearchEventItem);

            if ($resultSearchEventItem) {
                $countItems = mysqli_num_rows($resultSearchEventItem);

                //getting ticket information from database
                $ticketPrice = 0;
                $ticketDiscount = 0;
                $totalTicketPrice = 0;
                $totalTicketDiscount = 0;
                $sqlGetItem = "SELECT * FROM event_ticket_types WHERE TT_venue_id=$venueID AND TT_id=$itemID";
                $resultGetItem = mysqli_query($con, $sqlGetItem);

                if ($resultGetItem) {
                    $resultGetItemObj = mysqli_fetch_object($resultGetItem);
                    if (isset($resultGetItemObj->TT_venue_id)) {
                        //getting item price and discount from databases
                        $ticketPrice = $resultGetItemObj->TT_current_price;
                        if ($resultGetItemObj->TT_old_price > 0) {
                            $ticketDiscount = ($resultGetItemObj->TT_old_price - $resultGetItemObj->TT_current_price);
                        }
                        $totalTicketPrice = $ticketPrice * $quantity;
                        $totalTicketDiscount = $ticketDiscount * $quantity;
                    }
                } else {
                    $checkStatus++;
                    if (DEBUG) {
                        $return_array = array("output" => "error", "msg" => "resultGetItem error: " . mysqli_error($con));
                        echo json_encode($return_array);
                        exit();
                    } else {
                        $return_array = array("output" => "error", "msg" => "resultGetItem query failed.");
                        echo json_encode($return_array);
                        exit();
                    }
                }

                if ($countItems > 0) {
                    //item already exist in database, need to update
                    $updateItemTmpCart = '';
                    $updateItemTmpCart .=' EITC_quantity = "' . intval($quantity) . '"';
                    $updateItemTmpCart .=', EITC_total_price = "' . floatval($totalTicketPrice) . '"';
                    $updateItemTmpCart .=', EITC_total_discount = "' . floatval($totalTicketDiscount) . '"';

                    $sqlUpdateItemTmpCart = "UPDATE event_item_temp_cart SET $updateItemTmpCart WHERE EITC_venue_id=$venueID AND EITC_item_id=$itemID AND EITC_session_id='$sessionID' AND EITC_item_type='$type'";
                    $resultUpdateItemTmpCart = mysqli_query($con, $sqlUpdateItemTmpCart);

                    if (!$resultUpdateItemTmpCart) {
                        $checkStatus++;
                        if (DEBUG) {
                            $return_array = array("output" => "error", "msg" => "resultUpdateItemTmpCart error: " . mysqli_error($con));
                            echo json_encode($return_array);
                            exit();
                        } else {
                            $return_array = array("output" => "error", "msg" => "resultUpdateItemTmpCart query failed.");
                            echo json_encode($return_array);
                            exit();
                        }
                    }
                } else {
                    //item does not exist in database, need to insert
                    $insertItemTmpCart = '';
                    $insertItemTmpCart .=' EITC_ETC_id = "' . intval($ETC_id) . '"';
                    $insertItemTmpCart .=', EITC_session_id = "' . mysqli_real_escape_string($con, $sessionID) . '"';
                    $insertItemTmpCart .=', EITC_item_type = "' . mysqli_real_escape_string($con, $type) . '"';
                    $insertItemTmpCart .=', EITC_venue_id = "' . intval($venueID) . '"';
                    $insertItemTmpCart .=', EITC_item_id = "' . intval($itemID) . '"';
                    $insertItemTmpCart .=', EITC_quantity = "' . intval($quantity) . '"';
                    $insertItemTmpCart .=', EITC_unit_price = "' . floatval($ticketPrice) . '"';
                    $insertItemTmpCart .=', EITC_unit_discount = "' . floatval($ticketDiscount) . '"';
                    $insertItemTmpCart .=', EITC_total_price = "' . floatval($totalTicketPrice) . '"';
                    $insertItemTmpCart .=', EITC_total_discount = "' . floatval($totalTicketDiscount) . '"';

                    $sqlInsertItemTmpCart = "INSERT INTO event_item_temp_cart SET $insertItemTmpCart";
                    $resultInsertItemTmpCart = mysqli_query($con, $sqlInsertItemTmpCart);

                    if (!$resultInsertItemTmpCart) {
                        $checkStatus++;
                        if (DEBUG) {
                            $return_array = array("output" => "error", "msg" => "resultInsertItemTmpCart error: " . mysqli_error($con));
                            echo json_encode($return_array);
                            exit();
                        } else {
                            $return_array = array("output" => "error", "msg" => "resultInsertItemTmpCart query failed.");
                            echo json_encode($return_array);
                            exit();
                        }
                    }
                }
            } else {
                $checkStatus++;
                if (DEBUG) {
                    $return_array = array("output" => "error", "msg" => "resultSearchEventItem error: " . mysqli_error($con));
                    echo json_encode($return_array);
                    exit();
                } else {
                    $return_array = array("output" => "error", "msg" => "resultSearchEventItem query failed.");
                    echo json_encode($return_array);
                    exit();
                }
            }
        } elseif ($type == "include") {


            //checking if event item already exist in table
            $sqlSearchEventItem = "SELECT EITC_id FROM event_item_temp_cart WHERE EITC_venue_id=$venueID AND EITC_item_id=$itemID AND EITC_session_id='$sessionID' AND EITC_item_type='$type'";
            $resultSearchEventItem = mysqli_query($con, $sqlSearchEventItem);

            if ($resultSearchEventItem) {
                $countItems = mysqli_num_rows($resultSearchEventItem);

                //getting ticket information from database
                $includePrice = 0;
                $includeDiscount = 0;
                $totalIncludePrice = 0;
                $totalIncludeDiscount = 0;
                $sqlGetItem = "SELECT * FROM event_includes WHERE EI_venue_id=$venueID AND EI_id=$itemID";
                $resultGetItem = mysqli_query($con, $sqlGetItem);

                if ($resultGetItem) {
                    $resultGetItemObj = mysqli_fetch_object($resultGetItem);
                    if (isset($resultGetItemObj->EI_venue_id)) {
                        //getting item price and discount from databases
                        $includePrice = $resultGetItemObj->EI_price;
                        $includeDiscount = 0;
                        $totalIncludePrice = $includePrice * $quantity;
                        $totalIncludeDiscount = $includeDiscount * $quantity;
                    }
                } else {
                    $checkStatus++;
                    if (DEBUG) {
                        $return_array = array("output" => "error", "msg" => "resultGetItem error: " . mysqli_error($con));
                        echo json_encode($return_array);
                        exit();
                    } else {
                        $return_array = array("output" => "error", "msg" => "resultGetItem query failed.");
                        echo json_encode($return_array);
                        exit();
                    }
                }

                if ($countItems > 0) {
                    //item already exist in database, need to update
                    $updateItemTmpCart = '';
                    $updateItemTmpCart .=' EITC_quantity = "' . intval($quantity) . '"';
                    $updateItemTmpCart .=', EITC_total_price = "' . floatval($totalIncludePrice) . '"';
                    $updateItemTmpCart .=', EITC_total_discount = "' . floatval($totalIncludeDiscount) . '"';

                    $sqlUpdateItemTmpCart = "UPDATE event_item_temp_cart SET $updateItemTmpCart WHERE EITC_venue_id=$venueID AND EITC_item_id=$itemID AND EITC_session_id='$sessionID' AND EITC_item_type='$type'";
                    $resultUpdateItemTmpCart = mysqli_query($con, $sqlUpdateItemTmpCart);

                    if (!$resultUpdateItemTmpCart) {
                        $checkStatus++;
                        if (DEBUG) {
                            $return_array = array("output" => "error", "msg" => "resultUpdateItemTmpCart error: " . mysqli_error($con));
                            echo json_encode($return_array);
                            exit();
                        } else {
                            $return_array = array("output" => "error", "msg" => "resultUpdateItemTmpCart query failed.");
                            echo json_encode($return_array);
                            exit();
                        }
                    }
                } else {
                    //item does not exist in database, need to insert
                    $insertItemTmpCart = '';
                    $insertItemTmpCart .=' EITC_ETC_id = "' . intval($ETC_id) . '"';
                    $insertItemTmpCart .=', EITC_session_id = "' . mysqli_real_escape_string($con, $sessionID) . '"';
                    $insertItemTmpCart .=', EITC_item_type = "' . mysqli_real_escape_string($con, $type) . '"';
                    $insertItemTmpCart .=', EITC_venue_id = "' . intval($venueID) . '"';
                    $insertItemTmpCart .=', EITC_item_id = "' . intval($itemID) . '"';
                    $insertItemTmpCart .=', EITC_quantity = "' . intval($quantity) . '"';
                    $insertItemTmpCart .=', EITC_unit_price = "' . floatval($includePrice) . '"';
                    $insertItemTmpCart .=', EITC_unit_discount = "' . floatval($includeDiscount) . '"';
                    $insertItemTmpCart .=', EITC_total_price = "' . floatval($totalIncludePrice) . '"';
                    $insertItemTmpCart .=', EITC_total_discount = "' . floatval($totalIncludeDiscount) . '"';

                    $sqlInsertItemTmpCart = "INSERT INTO event_item_temp_cart SET $insertItemTmpCart";
                    $resultInsertItemTmpCart = mysqli_query($con, $sqlInsertItemTmpCart);

                    if (!$resultInsertItemTmpCart) {
                        $checkStatus++;
                        if (DEBUG) {
                            $return_array = array("output" => "error", "msg" => "resultInsertItemTmpCart error: " . mysqli_error($con));
                            echo json_encode($return_array);
                            exit();
                        } else {
                            $return_array = array("output" => "error", "msg" => "resultInsertItemTmpCart query failed.");
                            echo json_encode($return_array);
                            exit();
                        }
                    }
                }
            } else {
                $checkStatus++;
                if (DEBUG) {
                    $return_array = array("output" => "error", "msg" => "resultSearchEventItem error: " . mysqli_error($con));
                    echo json_encode($return_array);
                    exit();
                } else {
                    $return_array = array("output" => "error", "msg" => "resultSearchEventItem query failed.");
                    echo json_encode($return_array);
                    exit();
                }
            }
        } elseif ($type == "donate") {
            
        }
    } else {
        $checkStatus++;
        if (DEBUG) {
            $return_array = array("output" => "error", "msg" => "resultSearchEvent error: " . mysqli_error($con));
            echo json_encode($return_array);
            exit();
        } else {
            $return_array = array("output" => "error", "msg" => "resultSearchEvent query failed.");
            echo json_encode($return_array);
            exit();
        }
    }






    
    /*     * ****************************************Temp Cart Generation********************************************* */

    $arrTmpCartEvent = array();
    $sqlGetTmpCartEvent = "SELECT event_title,event_web_logo,ETC_id FROM event_temp_cart "
            . "LEFT JOIN events ON event_id=ETC_event_id "
            . "WHERE ETC_session_id='$sessionID'";
    $resultGetTmpCartEvent = mysqli_query($con, $sqlGetTmpCartEvent);
    if ($resultGetTmpCartEvent) {
        while ($resultGetTmpCartEventObj = mysqli_fetch_object($resultGetTmpCartEvent)) {
            $arrTmpCartEvent[] = $resultGetTmpCartEventObj;
        }
    } else {
        $checkStatus++;
        if (DEBUG) {
            echo "resultGetTmpCartEventObj error: " . mysqli_error($con);
        } else {
            echo "resultGetTmpCartEventObj query failed.";
        }
    }

    $arrTmpCartItem = array();
    $strTicketID = '';
    $strIncludeID = '';
    $strSeatID = '';
    $totalCartAmount = 0;
    $sqlGetTmpCartItem = "SELECT * FROM event_item_temp_cart "
            . "WHERE EITC_session_id='$sessionID'";
    $resultGetTmpCartItem = mysqli_query($con, $sqlGetTmpCartItem);
    if ($resultGetTmpCartItem) {
        while ($resultGetTmpCartItemObj = mysqli_fetch_object($resultGetTmpCartItem)) {
            $arrTmpCartItem[$resultGetTmpCartItemObj->EITC_ETC_id][] = $resultGetTmpCartItemObj;
            $totalCartAmount += $resultGetTmpCartItemObj->EITC_total_price;
            if ($resultGetTmpCartItemObj->EITC_item_type == 'ticket') {
                $strTicketID .= $resultGetTmpCartItemObj->EITC_item_id . ',';
            } elseif ($resultGetTmpCartItemObj->EITC_item_type == 'include') {
                $strIncludeID .= $resultGetTmpCartItemObj->EITC_item_id . ',';
            } elseif ($resultGetTmpCartItemObj->EITC_item_type == 'seat') {
                $strSeatID .= $resultGetTmpCartItemObj->EITC_item_id . ',';
            }
        }
    } else {
        $checkStatus++;
        if (DEBUG) {
            echo "resultGetTmpCartItem error: " . mysqli_error($con);
        } else {
            echo "resultGetTmpCartItem query failed.";
        }
    }

    $strTicketID = trim($strTicketID, ',');
    $strIncludeID = trim($strIncludeID, ',');
    $strSeatID = trim($strSeatID, ',');

    $arrSelectedTicket = array();
    if ($strTicketID != "") {

        $sqlGetSelectedTicket = "SELECT * FROM event_ticket_types WHERE TT_id IN ($strTicketID)";
        $resultGetSelectedTicket = mysqli_query($con, $sqlGetSelectedTicket);
        if ($resultGetSelectedTicket) {
            while ($resultGetSelectedTicketObj = mysqli_fetch_object($resultGetSelectedTicket)) {
                $arrSelectedTicket[$resultGetSelectedTicketObj->TT_id] = $resultGetSelectedTicketObj;
            }
        } else {
            $checkStatus++;
            if (DEBUG) {
                echo "resultGetSelectedTicket error: " . mysqli_error($con);
            } else {
                echo "resultGetSelectedTicket query failed.";
            }
        }
    }

    $arrSelectedInclude = array();
    if ($strIncludeID != "") {

        $sqlGetSelectedInclude = "SELECT * FROM event_includes WHERE EI_id IN ($strIncludeID)";
        $resultGetSelectedInclude = mysqli_query($con, $sqlGetSelectedInclude);
        if ($resultGetSelectedInclude) {
            while ($resultGetSelectedIncludeObj = mysqli_fetch_object($resultGetSelectedInclude)) {
                $arrSelectedInclude[$resultGetSelectedIncludeObj->EI_id] = $resultGetSelectedIncludeObj;
            }
        } else {
            $checkStatus++;
            if (DEBUG) {
                echo "resultGetSelectedInclude error: " . mysqli_error($con);
            } else {
                echo "resultGetSelectedInclude query failed.";
            }
        }
    }
    
    $arrSelectedSeat = array();
    if ($strSeatID != "") {

        $sqlGetSelectedSeat = "SELECT * FROM seat_place_coordinate WHERE SPC_id IN ($strSeatID)";
        $resultGetSelectedSeat = mysqli_query($con, $sqlGetSelectedSeat);
        if ($resultGetSelectedSeat) {
            while ($resultGetSelectedSeatObj = mysqli_fetch_object($resultGetSelectedSeat)) {
                $arrSelectedSeat[$resultGetSelectedSeatObj->SPC_id] = $resultGetSelectedSeatObj;
            }
        } else {
            $checkStatus++;
            if (DEBUG) {
                echo "resultGetSelectedSeat error: " . mysqli_error($con);
            } else {
                echo "resultGetSelectedSeat query failed.";
            }
        }
    }
    /*     * ****************************************Temp Cart Generation********************************************* */

    if ($checkStatus == 0) {  
        $return_array = array("output" => "success",
            "msg" => "Cart successfully updated.",
            "arrTmpCartEvent" => $arrTmpCartEvent,
            "arrTmpCartItem" => $arrTmpCartItem,
            "arrSelectedTicket" => $arrSelectedTicket,
            "arrSelectedInclude" => $arrSelectedInclude,
            "arrSelectedSeat" => $arrSelectedSeat,
            "totalCartAmount" => number_format($totalCartAmount, 2));
    } else {
        $return_array = array("output" => "error", "msg" => "Add to cart process failed.");
    }


    echo json_encode($return_array);
}