<?php
include '../config/config.php';
error_reporting(0);
if (isset($_GET['id'])) {
    $orderID = $_GET['id'];
}
$userID = 0;
$user_email = '';
//echo $orderID;


$orderDetails = array();
$sqlOrderDetails = "SELECT order_events.*, order_items.*, events .*,categories.category_title,event_venues.*,"
        . " CASE OI_item_type WHEN 'ticket' THEN ' Ticket'"
        . " ELSE CASE OI_item_type WHEN 'include' THEN 'Include' "
        . " ELSE 'Others' END END AS item_type "
        . " FROM order_items "
        . " LEFT JOIN order_events ON order_events.OE_id = order_items.OI_OE_id "
        . " LEFT JOIN events ON events .event_id = order_events.OE_event_id "
        . " LEFT JOIN categories ON categories .category_id = events.event_category_id "
        . " LEFT JOIN event_venues ON events.event_id = event_venues .venue_event_id "
        . " WHERE order_items.OI_id =$orderID";
$resultOrderDetails = mysqli_query($con, $sqlOrderDetails);
if ($resultOrderDetails) {
    $resultOrderDetailsObj = mysqli_fetch_object($resultOrderDetails);
} else {
    if (DEBUG) {
        $err = "resultOrderDetails error: " . mysqli_error($con);
    } else {
        $err = "resultOrderDetails query failed";
    }
}
//debug($resultOrderDetailsObj);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- For Barcode Generate -->
        <?php // include basePath('header_script.php'); ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script> 
        <script type="text/javascript" src="<?php echo baseUrl('js/barcode/jquery-barcode.js'); ?>"></script>
        <!-- For Barcode Generate -->
    </head>

    <body style="width: 100% !important;height: 100% !important;margin: 0 !important;padding: 0 !important;background-color: #FAFAFA !important;font: 12pt 'Tahoma' !important;">
        <div class="book">
            <div class="page" style="width: 210mm; min-height: 290mm;
                 margin: 10mm auto;
                 border: 1px #D3D3D3 solid;
                 border-radius: 5px;
                 background: white;
                 box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);">
                <table width="787px" style="border-bottom: #000000 2px dashed !important; padding-bottom: 50px;">
                    <tr style="width: 787px; border-bottom: 2px solid #000000;">
                        <td style="width: 787px;" colspan="3">
                            <h4 style="text-align: center;font-family: SutonnyMJ">
                                <strong><?php echo $resultOrderDetailsObj->event_title; ?></strong>
                            </h4>
                        </td>
                    </tr>
                    <tr style="width: 787px;">
                        <td style="width: 200px;">
                            <img style="width: 200px;" src="<?php echo $config['IMAGE_UPLOAD_URL'] . '/event_web_logo/' . $resultOrderDetailsObj->event_web_logo; ?>" alt="Event Logo"></td>
                        <td style="width: 425px; padding: 10px !important;"><script type="text/javascript">
                            $(document).ready(function () {
                                $("#ticketBarcode_<?php echo $resultOrderDetailsObj->OI_id; ?>").barcode(
                                        "<?php echo $resultOrderDetailsObj->OI_unique_id; ?>", // Value barcode (dependent on the type of barcode)
                                        "code39" // type (string)
                                        );
                            });

                            </script>

                            <table width="405px" style="margin: 10px;">
                                <tbody>
                                    <tr>
                                        <td style="vertical-align: top; padding: 0 !important; margin: 0 !important;"><h5>Event Title: <strong><?php echo $resultOrderDetailsObj->event_title; ?></strong></h5></td>
                                    </tr>
                                    <tr>
                                        <td><h5>Event Type: <strong><?php echo $resultOrderDetailsObj->category_title; ?></strong></h5></td>
                                    </tr>
                                    <tr>
                                        <td><h5>Event Date: <strong><?php echo date("l, d F, Y", strtotime($resultOrderDetailsObj->venue_start_date)); ?></strong></h5></td>
                                    </tr>
                                    <tr>
                                        <td><h5>Location: <strong><?php echo $resultOrderDetailsObj->venue_title; ?></strong></h5></td>
                                    </tr>
                                    <tr>
                                        <td><h5>Ticket Type: <strong><?php echo $resultOrderDetailsObj->item_type; ?></strong></h5></td>
                                    </tr>
                                    <tr>
                                        <td><h5>Applicable For: <strong><?php echo $resultOrderDetailsObj->OI_quantity; ?> Person</strong></h5></td>
                                    </tr>
                                    <tr>
                                        <td><h5>Price: <strong><?php echo $config['CURRENCY_SIGN']; ?> <?php echo $resultOrderDetailsObj->OI_unit_price; ?></strong></h5></td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td style="width: 160px;">
                            <img style="width: 160px;"  src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=<?php echo $resultOrderDetailsObj->OI_unique_id; ?>&choe=UTF-8" title="Link to Google.com">
                            <div class="clearfix"></div>
                            <br><br>
                            <h5><strong>Ticketing Partner</strong></h5>
                            <img style="width: 120px;" src="<?php echo baseUrl(); ?>images/ticketchai_logo.png">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="width: 787px;">
                            <br/>
                            <div style="margin: 0 auto !important;" class="barcodecell"><barcode code="<?php echo $resultOrderDetailsObj->OI_unique_id; ?>" type="C39" height="1" text="1"/></div>
                            <div style="margin: 0 auto;" id="ticketBarcode_<?php echo $resultOrderDetailsObj->OI_id; ?>"></div>
                            <br/><br/>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <?php // include basePath('footer_script.php'); ?>

    </body>

</html>
