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


        <div id="content">


            <h3 class="bg-white content-heading border-bottom strong">User List</h3>

            <div style="margin-left: 10px;margin-right: 10px;margin-top: 5px;">
                <?php include basePath('admin/message.php'); ?>
            </div>

            <!-- Content Start Here -->
            <?php if (checkPermission('customer', 'read', getSession('admin_type'))): ?>
                <div id="grid" style="margin-left: 10px;margin-right: 10px;"></div>
            <?php else : ?>
                <div style="margin-left: 10px;"><h5 class="text-center">You dont have permission to view the content</h5></div>
            <?php endif; ?>

            <script type="text/x-kendo-template" id="command-status">
<?php if (checkPermission('customer', 'status', getSession('admin_type'))): ?>
                    # if(user_status == "active") { #
                    <a style="font-size:12px;" class="k-button k-grid-even" href="change_status.php?user_id=#= user_id #&user_status=#= user_status #">Make Inactive</a>
                    # } else { #
                    <a style="font-size:12px;" class="k-button k-grid-odd" href="change_status.php?user_id=#= user_id #&user_status=#= user_status #">Make Active</a>
                    # } #
<?php endif; ?>
            </script>
            <script type="text/x-kendo-template" id="command-verify">
<?php if (checkPermission('customer', 'update', getSession('admin_type'))): ?>
                    # if(user_verification == "yes") { #
                    <a style="font-size:12px;" class="k-button k-grid-even" href="change_verification.php?user_id=#= user_id #&user_verification=#= user_verification #">Make No</a>
                    # } else { #
                    <a style="font-size:12px;" class="k-button k-grid-odd" href="change_verification.php?user_id=#= user_id #&user_verification=#= user_verification #">Make Yes</a>
                    # } #
<?php endif; ?>
            </script>

            <script type="text/javascript">
                jQuery(document).ready(function () {
                    var dataSource = new kendo.data.DataSource({
                        pageSize: 10,
                        transport: {
                            read: {
                                url: "../controller/customer/user_list.php",
                                type: "GET"
                            },
                            destroy: {
                                url: "../controller/customer/user_list.php",
                                type: "POST"
                            }
                        },
                        autoSync: false,
                        schema: {
                            data: "data",
                            total: "data.length",
                            model: {
                                id: "user_id",
                                fields: {
                                    user_id: {nullable: true},
                                    user_email: {type: "string"},
                                    user_first_name: {type: "string"},
                                    user_last_name: {type: "string"},
                                    user_status: {type: "string"},
                                    user_verification: {type: "string"}
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
                            pageSizes: [10, 20, 50],
                        },
                        sortable: true,
                        groupable: true,
                        columns: [
                            {field: "user_email", title: "User Email", width: "180px"},
                            {field: "user_first_name", title: "First Name", width: "150px"},
                            {field: "user_last_name", title: "Last Name", width: "150px"},
                            {
                                title: "Status", width: "120px",
                                template: kendo.template($("#command-status").html())
                            },
                            {
                                title: "Is verified?", width: "120px",
                                template: kendo.template($("#command-verify").html())
                            }
                        ],
                    });
                });

            </script>

        </div>
        <div class="clearfix"></div>
        <?php include basePath('admin/footer.php'); ?>
    </div>
    <script type="text/javascript">
        $("#customerlist").addClass("active");
        $("#customerlist").parent().parent().addClass("active");
        $("#customerlist").parent().addClass("in");
    </script>
    <?php include basePath('admin/footer_script.php'); ?>
</body>
</html>
