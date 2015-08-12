<?php

include '../config/config.php';
$coupon_code = "";
$totalCost = 0;
$countCode = 0;
$couponStatus = "";
$promotionID = 0;
$promotionStatus = "";
$promotionUserDefined = "";
$userEmail = getSession('user_email');
$promotionUserEmail = "";
$promotionUserType = "";
$promotionDiscountAmount = 0;
$promotionDiscountType = "";
$calculateDiscountAmount = 0;
$totalPayAmount = 0;
$promotion_multiple_use_count = 0;
$promotion_multiple_count = 0;
extract($_POST);
debug($_POST);
exit();

$sqlCouponCodeCheck = "SELECT PC_code,PC_code_status,PC_code_used_email,PC_promotion_id FROM promotion_codes WHERE PC_code = '$coupon_code'";
$resultCouponCodeCheck = mysqli_query($con, $sqlCouponCodeCheck);
$countCode = mysqli_num_rows($resultCouponCodeCheck);
if ($countCode > 0) {
    $resultCouponCodeCheckObj = mysqli_fetch_object($resultCouponCodeCheck);
    $couponStatus = $resultCouponCodeCheckObj->PC_code_status;
    $promotionID = $resultCouponCodeCheckObj->PC_promotion_id;
    $promotionUserEmail = $resultCouponCodeCheckObj->PC_code_used_email;
    if ($couponStatus === 'active') {
        // check status of promotion table
        $sqlCheckPromoStatus = "SELECT promotion_status,promotion_code_predefined_user,promotion_code_use_type,"
                . " promotion_discount_type,promotion_discount_amount,promotion_expire,promotion_multiple_use_count,promotion_multiple_count"
                . " FROM promotions WHERE promotion_id = $promotionID";
        $resultCheckPromoStatus = mysqli_query($con, $sqlCheckPromoStatus);
        if ($resultCheckPromoStatus) {
            $resultCheckPromoStatusObj = mysqli_fetch_object($resultCheckPromoStatus);
            $promotionStatus = $resultCheckPromoStatusObj->promotion_status;
            $promotionUserDefined = $resultCheckPromoStatusObj->promotion_code_predefined_user;
            $promotionUserType = $resultCheckPromoStatusObj->promotion_code_use_type;
            $promotionDiscountType = $resultCheckPromoStatusObj->promotion_discount_type;
            $promotionDiscountAmount = $resultCheckPromoStatusObj->promotion_discount_amount;
            $promotion_multiple_use_count = $resultCheckPromoStatusObj->promotion_multiple_use_count;
            $promotion_multiple_count = $resultCheckPromoStatusObj->promotion_multiple_count;


            if ($promotionStatus === 'active') {
                // check user defined as yes or no
                if ($promotionUserDefined === 'yes') {
                    // check if email address matched or not
                    if ($userEmail === $promotionUserEmail) {
                        if ($promotionUserType === 'single') {
                            // update code as used
                            $sqlUpdateCodeStatus = "UPDATE promotion_codes SET PC_code_status='used' WHERE PC_code = '$coupon_code'";
                            $resultUpdateCodeStatus = mysqli_query($con, $sqlUpdateCodeStatus);
                            if ($resultUpdateCodeStatus) {
                                // getting discount type
                                if ($promotionDiscountType === 'percentage') {
                                    $calculateDiscountAmount = (($promotionDiscountAmount / 100) * $totalCost);
//                                    $totalPayAmount = $totalCost - $calculateDiscountAmount;
                                    if (!getSession('COUPON_CODE')) {
                                        setSession('COUPON_CODE', TRUE);
                                    }
                                    if (!getSession('COUPON_CODE_NO')) {
                                        setSession('COUPON_CODE_NO', $coupon_code);
                                    }
                                    if (!getSession('COUPON_CODE_AMOUNT')) {
                                        setSession('COUPON_CODE_AMOUNT', $calculateDiscountAmount);
                                    }
                                    $return_array = array("output" => "success",
                                        "msg" => "Coupon code applied successfully",
                                        "amount" => $calculateDiscountAmount);
                                    echo json_encode($return_array);
                                    exit();
                                } else {
//                                    $totalPayAmount = $totalCost - $promotionDiscountAmount;
                                    if (!getSession('COUPON_CODE')) {
                                        setSession('COUPON_CODE', TRUE);
                                    }
                                    if (!getSession('COUPON_CODE_NO')) {
                                        setSession('COUPON_CODE_NO', $coupon_code);
                                    }
                                    if (!getSession('COUPON_CODE_AMOUNT')) {
                                        setSession('COUPON_CODE_AMOUNT', $promotionDiscountAmount);
                                    }
                                    $return_array = array("output" => "success",
                                        "msg" => "Coupon code applied successfully",
                                        "amount" => $promotionDiscountAmount);
                                    echo json_encode($return_array);
                                    exit();
                                }
                            } else {
                                $return_array = array("output" => "error", "msg" => "resultUpdateCodeStatus query failed.");
                                echo json_encode($return_array);
                                exit();
                            }
                        } else {
                            if ($promotion_multiple_count > 0) {//$promotion_multiple_count == 0 means unlimited usage
                                
                                if ($promotion_multiple_count > $promotion_multiple_use_count) {


                                    //updating promotion code usage count by 1
                                    $sqlUpdateUsageQnty = "UPDATE promotions SET promotion_multiple_use_count = promotion_multiple_use_count + 1 WHERE  promotion_id = $promotionID";
                                    $resultUpdateUsageQnty = mysqli_query($con, $sqlUpdateUsageQnty);

                                    if (!$resultUpdateUsageQnty) {
                                        $return_array = array("output" => "error", "msg" => "Promotion code usage count update failed.");
                                        echo json_encode($return_array);
                                        exit();
                                    }

                                    // multiple type code start here
                                    if ($promotionDiscountType === 'percentage') {
                                        $calculateDiscountAmount = (($promotionDiscountAmount / 100) * $totalCost);
//                                $totalPayAmount = $totalCost - $calculateDiscountAmount;
                                        if (!getSession('COUPON_CODE')) {
                                            setSession('COUPON_CODE', TRUE);
                                        }
                                        if (!getSession('COUPON_CODE_NO')) {
                                            setSession('COUPON_CODE_NO', $coupon_code);
                                        }
                                        if (!getSession('COUPON_CODE_AMOUNT')) {
                                            setSession('COUPON_CODE_AMOUNT', $calculateDiscountAmount);
                                        }
                                        $return_array = array("output" => "success",
                                            "msg" => "Coupon code applied successfully",
                                            "amount" => $calculateDiscountAmount);
                                        echo json_encode($return_array);
                                        exit();
                                    } else {
//                                $totalPayAmount = $totalCost - $promotionDiscountAmount;
                                        if (!getSession('COUPON_CODE')) {
                                            setSession('COUPON_CODE', TRUE);
                                        }
                                        if (!getSession('COUPON_CODE_NO')) {
                                            setSession('COUPON_CODE_NO', $coupon_code);
                                        }
                                        if (!getSession('COUPON_CODE_AMOUNT')) {
                                            setSession('COUPON_CODE_AMOUNT', $promotionDiscountAmount);
                                        }
                                        $return_array = array("output" => "success",
                                            "msg" => "Coupon code applied successfully",
                                            "amount" => $promotionDiscountAmount);
                                        echo json_encode($return_array);
                                        exit();
                                    }
                                } else {
                                    $return_array = array("output" => "error", "msg" => "Sorry!! User limit reached for this code.");
                                    echo json_encode($return_array);
                                    exit();
                                }
                            }
                        }
                    } else {
                        $return_array = array("output" => "error", "msg" => "Email address not matched with record");
                        echo json_encode($return_array);
                        exit();
                    }
                } else {

                    $sqlUpdateCodeStatus = "UPDATE promotion_codes SET PC_code_status='used' WHERE PC_code = '$coupon_code'";
                    $resultUpdateCodeStatus = mysqli_query($con, $sqlUpdateCodeStatus);

                    if ($resultUpdateCodeStatus) {
                        // code if no user defined
                        if ($promotionUserType === 'single') {
                            if ($promotionDiscountType === 'percentage') {
                                $calculateDiscountAmount = (($promotionDiscountAmount / 100) * $totalCost);
//                            $totalPayAmount = $totalCost - $calculateDiscountAmount;
                                if (!getSession('COUPON_CODE')) {
                                    setSession('COUPON_CODE', TRUE);
                                }
                                if (!getSession('COUPON_CODE_NO')) {
                                    setSession('COUPON_CODE_NO', $coupon_code);
                                }
                                if (!getSession('COUPON_CODE_AMOUNT')) {
                                    setSession('COUPON_CODE_AMOUNT', $calculateDiscountAmount);
                                }
                                $return_array = array("output" => "success",
                                    "msg" => "Coupon code applied successfully",
                                    "amount" => $calculateDiscountAmount);
                                echo json_encode($return_array);
                                exit();
                            } else {
//                            $totalPayAmount = $totalCost - $promotionDiscountAmount;
                                if (!getSession('COUPON_CODE')) {
                                    setSession('COUPON_CODE', TRUE);
                                }
                                if (!getSession('COUPON_CODE_NO')) {
                                    setSession('COUPON_CODE_NO', $coupon_code);
                                }
                                if (!getSession('COUPON_CODE_AMOUNT')) {
                                    setSession('COUPON_CODE_AMOUNT', $promotionDiscountAmount);
                                }
                                $return_array = array("output" => "success",
                                    "msg" => "Coupon code applied successfully",
                                    "amount" => $promotionDiscountAmount);
                                echo json_encode($return_array);
                                exit();
                            }
                        }
                    } else {
                        if ($promotion_multiple_count > 0) { //$promotion_multiple_count == 0 means unlimited usage
                            if ($promotion_multiple_count > $promotion_multiple_use_count) {

                                //updating promotion code usage count by 1
                                $sqlUpdateUsageQnty = "UPDATE promotions SET promotion_multiple_use_count = promotion_multiple_use_count + 1 WHERE  promotion_id = $promotionID";
                                $resultUpdateUsageQnty = mysqli_query($con, $sqlUpdateUsageQnty);

                                if (!$resultUpdateUsageQnty) {
                                    $return_array = array("output" => "error", "msg" => "Promotion code usage count update failed.");
                                    echo json_encode($return_array);
                                    exit();
                                }


                                if ($promotionDiscountType === 'percentage') {
                                    $calculateDiscountAmount = (($promotionDiscountAmount / 100) * $totalCost);
//                            $totalPayAmount = $totalCost - $calculateDiscountAmount;
                                    if (!getSession('COUPON_CODE')) {
                                        setSession('COUPON_CODE', TRUE);
                                    }
                                    if (!getSession('COUPON_CODE_NO')) {
                                        setSession('COUPON_CODE_NO', $coupon_code);
                                    }
                                    if (!getSession('COUPON_CODE_AMOUNT')) {
                                        setSession('COUPON_CODE_AMOUNT', $calculateDiscountAmount);
                                    }
                                    $return_array = array("output" => "success",
                                        "msg" => "Coupon code applied successfully",
                                        "amount" => $calculateDiscountAmount);
                                    echo json_encode($return_array);
                                    exit();
                                } else {
//                            $totalPayAmount = $totalCost - $promotionDiscountAmount;
                                    if (!getSession('COUPON_CODE')) {
                                        setSession('COUPON_CODE', TRUE);
                                    }
                                    if (!getSession('COUPON_CODE_NO')) {
                                        setSession('COUPON_CODE_NO', $coupon_code);
                                    }
                                    if (!getSession('COUPON_CODE_AMOUNT')) {
                                        setSession('COUPON_CODE_AMOUNT', $promotionDiscountAmount);
                                    }
                                    $return_array = array("output" => "success",
                                        "msg" => "Coupon code applied successfully",
                                        "amount" => $promotionDiscountAmount);
                                    echo json_encode($return_array);
                                    exit();
                                }
                            }
                        } else {

                            $return_array = array("output" => "error", "msg" => "Sorry!! User limit reached for this code.");
                            echo json_encode($return_array);
                            exit();
                        }
                    }
                }
            } else if ($promotionStatus === 'archive') {
                $return_array = array("output" => "error", "msg" => "Coupon code not available");
                echo json_encode($return_array);
                exit();
            } else if ($promotionStatus === 'used') {
                $return_array = array("output" => "error", "msg" => "Coupon code already used");
                echo json_encode($return_array);
                exit();
            }
        } else {
            $return_array = array("output" => "error", "msg" => "resultCheckPromoStatus query failed.");
            echo json_encode($return_array);
            exit();
        }
    } else if ($promotionStatus === 'used') {
        $return_array = array("output" => "error", "msg" => "Coupon code already used");
        echo json_encode($return_array);
        exit();
    } else {
        $return_array = array("output" => "error", "msg" => "Coupon code not activated yet");
        echo json_encode($return_array);
        exit();
    }
} else {
    $return_array = array("output" => "error", "msg" => "Coupon code not valid");
    echo json_encode($return_array);
    exit();
}
?>

