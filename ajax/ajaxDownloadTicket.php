<?php

include '../config/config.php';
include '../lib/mpdf/mpdf.php';
error_reporting(0);
$orderID = 0;
extract($_POST);
if ($orderID > 0) {

//    $mpdf = new mPDF('c', 'A4', '', '', 32, 25, 27, 25, 16, 13);
//
//    $mpdf->SetDisplayMode('fullpage');
//
//    $mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
//// LOAD a stylesheet
//    //$stylesheet = file_get_contents('mpdfstyletables.css');
//    //$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
////        foreach ($_SESSION["pQ_label_references"] as $ref) {
////            $label_references .= "'" . $ref . "'" . ",";
////        }
//    $label_references = join(',', $_SESSION["pQ_label_references"]);
//
//    $url = $con->baseUrl() . "/view/price_quotaion/price_quotation_with_image.php?QQrbo_name=" . $_SESSION["pQ_rbo_id"] . "&QQvendor_name=" . $_SESSION["pQ_Vendor_id"] . "&lab_ref=" . $label_references;
//    $html = file_get_contents($url);
//    $mpdf->WriteHTML($html, 2);
//
//    $mpdf->Output('Price_quotation_with_price.pdf', 'D');
//    exit;

    $mpdf = new mPDF('c', 'A4', '', '', 15, 15, 15, 15, 16, 13);
    $mpdf->SetDisplayMode('fullpage');
    $stylesheet = file_get_contents(baseUrl() . 'pdfticket/style.css');
    $mpdf->WriteHTML($stylesheet, 1);
    $mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
    $url = baseUrl() . "pdfticket/e-ticket-mini.php?id=$orderID";
    $html = file_get_contents($url);
    $mpdf->WriteHTML($html, 2);
    $mpdf->Output('e-ticket.pdf', 'D');
    exit();
}
?>