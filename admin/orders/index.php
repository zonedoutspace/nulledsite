<?php
include '../../config/config.php';
if (!checkAdminLogin()) {
    $link = baseUrl() . 'admin/login.php?err=' . base64_encode("You need to login first.");
    redirect($link);
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


        <div id="content"><h3 class="bg-white content-heading border-bottom strong">Order List</h3>
            <div  style="margin-left: 10px;margin-right: 10px;margin-top: 5px;">
                <?php include basePath('admin/message.php'); ?>
            </div>
            <!-- Content Start Here -->
            <?php if (checkPermission('order', 'read', getSession('admin_type'))): ?>
                <div id="grid" style="margin-left: 10px;margin-right: 10px;"></div>
            <?php else : ?>
                <div style="margin-left: 10px;"><h5 class="text-center">You dont have permission to view the content</h5></div>
            <?php endif; ?>

            <script id="edit_category" type="text/x-kendo-template">
                <a class="k-button k-button-icontext k-grid-edit" href="<?php echo baseUrl('admin/orders/order_details.php'); ?>?order_id=#= order_id #"><span class="fa fa-search-plus"></span>&nbsp;Details</a>
            </script>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    var dataSource = new kendo.data.DataSource({
                        pageSize: 10,
                        transport: {
                            read: {
                                url: "../controller/order/order_list.php",
                                type: "GET"
                            },
                            destroy: {
                                url: "../controller/order/order_list.php",
                                type: "POST"
                            },
                            create: {
                                url: "../controller/order/order_list.php",
                                type: "PUT",
                                complete: function (e) {
                                    jQuery("#grid").data("kendoGrid").dataSource.read();
                                }
                            }
                        },
                        autoSync: false,
                        schema: {
                            data: "data",
                            total: "data.length",
                            model: {
                                id: "event_id",
                                fields: {
                                    order_number: {type: "string"},
                                    order_created: {type: "string"},
                                    name: {type: "string"},
                                    order_billing_phone: {type: "string"},
                                    order_payment_type: {type: "string"},
                                    order_status: {type: "string"},
                                    total: {type: "number"},
                                    order_read: {type: "string"},
                                    admin_full_name: {type: "string"},
                                    order_updated_on: {type: "string"}
                                }
                            }
                        }
                    });
                    jQuery("#grid").kendoGrid({
                        dataSource: dataSource,
                        filterable: true,
                        pageable: {
                            refresh: true,
                            input: true,
                            numeric: false,
                            pageSizes: true,
                            pageSizes: [10, 20, 50, 100, 200],
                        },
                        sortable: true,
                        groupable: true,
                        columns: [
                            {field: "order_number", title: "Order No.", width: "100px"},
                            {field: "name", title: "Customer Name", width: "130px"},
                            {field: "order_billing_phone", title: "Phone No", width: "130px"},
                            {field: "order_created", title: "Placed On", width: "100px"},
                            {field: "order_payment_type", title: "Payment<br/> Method", width: "100px", template: '# if(order_payment_type == "COD"){ # Cash # } else { # Online # } #'},
                            {field: "order_status", title: "Status", width: "100px"},
                            {field: "total", title: "Total Amount", width: "110px"},
                            {field: "order_read", title: "Order Read", width: "100px"},
                            {field: "admin_full_name", title: "Viewed By", width: "100px"},
                            {field: "order_updated_on", title: "Viewed <br/>Date & Time", width: "110px"},
                            {
                                title: "Action", width: "100px",
                                template: kendo.template($("#edit_category").html())
                            }
                        ],
                    });
                });

            </script>
        </div>

        <div class="clearfix"></div>
        <!-- // Sidebar menu & content wrapper END -->
        <?php include basePath('admin/footer.php'); ?>
        <!-- // Footer END -->

    </div><!-- // Main Container Fluid END -->
    <script type="text/javascript">
        $("#orderlist").addClass("active");
        $("#orderlist").parent().parent().addClass("active");
        $("#orderlist").parent().addClass("in");
    </script>

    <?php include basePath('admin/footer_script.php'); ?>
</body>
</html>
