<?php
include '../../config/config.php';
if (!checkAdminLogin()) {
    $link = baseUrl() . 'admin/login.php?err=' . base64_encode("You need to login first.");
    redirect($link);
}
$orderNo = "";
$countOrderNo = 0;
$isVerified = "";
if (isset($_POST['orderNo'])) {
    extract($_POST);
    $orderNo = validateInput($orderNo);
    $sqlCheckOrderNo = "SELECT OI_unique_id,OI_is_verified FROM order_items WHERE OI_unique_id = '$orderNo'";
    $resultCheckOrderNo = mysqli_query($con, $sqlCheckOrderNo);
    $countOrderNo = mysqli_num_rows($resultCheckOrderNo);
    if ($countOrderNo > 0) {
        $resultCheckOrderNoObj = mysqli_fetch_object($resultCheckOrderNo);
        $isVerified = $resultCheckOrderNoObj->OI_is_verified;
        if ($isVerified === 'yes') {
            $err = "Order number already verified";
        } else {
            $sqlUpdateOrderVefification = "UPDATE order_items SET OI_is_verified='yes' WHERE OI_unique_id='$orderNo'";
            $resultUpdateOrderVerification = mysqli_query($con, $sqlUpdateOrderVefification);
            if ($resultUpdateOrderVerification) {
                $msg = "Order number is verified";
            } else {
                if (DEBUG) {
                    $err = "resultUpdateOrderVerification error: " . mysqli_error($con);
                } else {
                    $err = "resultUpdateOrderVerification query failed.";
                }
            }
        }
    } else {
        $err = "Please check your order number";
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Ticket Chai | Admin Panel</title>

        <!-- Meta -->
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />


        <?php include basePath('admin/header_script.php'); ?>	
    </head>
    <body class="">

        <?php include basePath('admin/header.php'); ?>

        <div id="menu" class="hidden-print hidden-xs">
            <div class="sidebar sidebar-inverse">
                <div class="user-profile media innerAll">
                    <div>
                        <a href="#" class="strong">Navigation</a>
                    </div>
                </div>
                <div class="sidebarMenuWrapper">
                    <ul class="list-unstyled">
                        <?php include basePath('admin/side_menu.php'); ?>
                    </ul>
                </div>
            </div>
        </div>


        <div id="content">
            <h3 class="bg-white content-heading border-bottom strong">Verify Order</h3>
            <div class="innerAll spacing-x2">
                <?php if (checkPermission('order', 'verify', getSession('admin_type'))): ?>
                    <?php include basePath('admin/message.php'); ?>
                    <form class="form-horizontal margin-none" method="post" autocomplete="off">

                        <div class="widget widget-inverse">
                            <div class="widget-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="col-md-8">
                                                <input class="form-control" autofocus="autofocus" id="orderNo" name="orderNo" type="text" placeholder="Enter order number"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php else : ?>
                    <div style="margin-left: 10px;"><h5 class="text-center">You dont have permission to view the content</h5></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="clearfix"></div>
        <?php include basePath('admin/footer.php'); ?>

        <script type="text/javascript">
            $("#verifyorder").addClass("active");
            $("#verifyorder").parent().parent().addClass("active");
            $("#verifyorder").parent().addClass("in");
        </script>

        <?php include basePath('admin/footer_script.php'); ?>
    </body>
</html>