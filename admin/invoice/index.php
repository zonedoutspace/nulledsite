<?php
include '../../config/config.php';
$orderItemId = 0;
$orderEventId = 0;
$typeName = "";
$typeId = 0;
$ticketName = "";
$eventTitle = "";
$eventWebLogo = "";
$eventVenue = "";
$eventTime = "";
$eventDate = "";

if (isset($_GET['item_id']) AND $_GET['item_id'] > 0) {
    $orderItemId = $_GET['item_id'];

    $sqlItem = "SELECT * "
            . "FROM order_items "
            . "WHERE OI_id=$orderItemId";
    $resultItem = mysqli_query($con, $sqlItem);
    if ($resultItem) {
        $resultItemObj = mysqli_fetch_object($resultItem);
        if (isset($resultItemObj->OI_id)) {
            $typeName = $resultItemObj->OI_item_id;
            $typeId = $resultItemObj->OI_id;
            $ticketNo = $resultItemObj->OI_unique_id;

            $sqlGetItem = "SELECT * FROM event_ticket_types WHERE TT_id=$typeId";
            $resultGetItem = mysqli_query($con, $sqlGetItem);
            if ($resultGetItem) {
                $resultGetItemObj = mysqli_fetch_object($resultGetItem);

                if (isset($resultGetItemObj->TT_id)) {
                    $ticketName = $resultGetItemObj->TT_type_title;
                }
            } else {
                
            }
        }
    } else {
        
    }
}


if (isset($_GET['event_id']) AND $_GET['event_id'] > 0) {
    $orderEventId = $_GET['event_id'];

    $sqlGetEvent = "SELECT * "
            . "FROM events "
            . "LEFT JOIN event_venues ON events.event_id = event_venues .venue_event_id "
            . "WHERE event_id=$orderEventId";
    $resultGetEvent = mysqli_query($con, $sqlGetEvent);
    if ($resultGetEvent) {
        $resultGetEventObj = mysqli_fetch_object($resultGetEvent);

        if (isset($resultGetEventObj->event_id)) {
            $eventTitle = $resultGetEventObj->event_title;
            $eventWebLogo = $resultGetEventObj->event_web_logo;
            $eventVenue = $resultGetEventObj->venue_title;
            $eventTime = date("h:i A", strtotime($resultGetEventObj->venue_start_time));
            $eventDate = date("l, d F, Y", strtotime($resultGetEventObj->venue_start_date));
        }
    } else {
        
    }
}
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Invoice</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> 
        <script type="text/javascript" src="<?php echo baseUrl('js/barcode/jquery-barcode.js'); ?>"></script>
        <link rel='stylesheet' href='css/style.css' media="screen">
        <link rel='stylesheet' href='css/style.css' media="print">
        <link href='http://fonts.googleapis.com/css?family=Titillium+Web' rel='stylesheet' type='text/css'>

    </head>
    <body>
        <div class="wrapper">
            <div style="width: 100%;">
                <div class="header">

                    <h3 style="float: left"> E-Ticket by</h3>
                    <img align="left" style="width: 115px;" src="http://ticketchai.com/images/ticketchai_logo.png">
                </div>
                <div class="container">
                    <div class="top">
                        <table class="topTable" width="80%" border="0">
                            <tr style="text-align: center;">
                                <td width="84%">
                                    <h1 class="middle" style="color: #312783;"><?php echo $eventTitle; ?></h1>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="invoiceToDetails">
                        <p style="color: #747afc; text-decoration: underline; padding-bottom: 15px; float: right;"><strong>Ticket No: <?php echo $ticketNo; ?></strong></p>
                        <div style="width: 30%; float: left">
                            <img src="<?php echo $config['IMAGE_UPLOAD_URL'] . '/event_web_logo/' . $eventWebLogo; ?>" style="width: 100%;">
                        </div>
                        <div style="width: 40%; float: left; padding: 0px 30px;">
                            <div class="itemTable">
                                <table width="100%" border="0">
                                    <tr>
                                        <td><strong>TICKET TYPE</strong></td>
                                        <td><?php echo $ticketName; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>EVENT DATE</strong></td>
                                        <td><?php echo $eventDate; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>EVENT TIME</strong></td>
                                        <td><?php echo $eventTime; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>EVENT PLACE</strong></td>
                                        <td><?php echo $eventVenue; ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><strong>ONLY ONE PERSON PER TICKET</strong></td>
                                    </tr>
                                </table>
                            </div>
                            <br><br>
                            <br><br>
                            <br><br>
                            <script type="text/javascript">
                                $(document).ready(function () {
                                    $("#ticketBarcode").barcode(
                                            "<?php echo $ticketNo; ?>", // Value barcode (dependent on the type of barcode)
                                            "code39" // type (string)
                                            );
                                });

                            </script>
                            <div style="padding: 0px; overflow: hidden !important; width: 422px;" id="ticketBarcode"></div>
                        </div>
                        <div style="width: 24%; float: left">
                            <img src="https://chart.googleapis.com/chart?chs=240x240&amp;cht=qr&amp;chl=<?php echo $ticketNo; ?>&amp;choe=UTF-8" title="Link to Google.com">
                            <div class="clearfix"></div>
                            <!--                            <br><br>
                                                        <div style="margin: 0 auto;">
                                                            <h5><strong>Ticketing Partner</strong></h5>
                                                            <img src="http://ticketchai.com/images/ticketchai_logo.png">
                                                        </div>-->
                        </div>
                    </div>  

                </div>

            </div>
        </div>
    </body>
</html>
