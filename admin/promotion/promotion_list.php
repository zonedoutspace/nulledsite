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
            <h3 class="bg-white content-heading border-bottom strong">Promotion List</h3>
            <div style="margin-left: 10px;margin-right: 10px;padding-top: 5px;">
                <?php include basePath('admin/message.php'); ?>
            </div>
            <div class="k-grid  k-secondary" data-role="grid" style="margin-left: 10px;margin-right: 10px;">
                <?php if (checkPermission('promotion', 'create', getSession('admin_type'))): ?>
                <div class="k-toolbar k-grid-toolbar">
                    <a class="k-button k-button-icontext k-grid-add" href="<?php echo baseUrl('admin/promotion/add_promotion.php'); ?>">
                        <span class="k-icon k-add"></span>
                        Add Promotion
                    </a>
                </div>
                <?php endif; ?>
            </div>



            <!-- Content Start Here -->
            <?php if (checkPermission('promotion', 'read', getSession('admin_type'))): ?>
            <div id="grid" style="margin-left: 10px;margin-right: 10px;"></div>
            <?php else : ?>
            <div style="margin-left: 10px;"><h5 class="text-center">You dont have permission to view the content</h5></div>
            <?php endif; ?>

            <script id="edit_promotion" type="text/x-kendo-template">
                <a class="k-button k-button-icontext k-grid-edit" href="<?php echo baseUrl('admin/promotion/promotion_code_list.php'); ?>?promotion_id=#= promotion_id#"><span class="k-icon k-i-group"></span>Code</a>
<?php if (checkPermission('promotion', 'update', getSession('admin_type'))):   ?>
                <a class="k-button k-button-icontext k-grid-edit" href="<?php echo baseUrl('admin/promotion/edit_promotion.php'); ?>?promotion_id=#= promotion_id#"><span class="k-icon k-edit"></span>Edit</a>
<?php endif;   ?>
                
<?php if (checkPermission('promotion', 'delete', getSession('admin_type'))):   ?>
                <a class="k-button k-button-icontext k-grid-delete" onclick="javascript:deleteClick(#= promotion_id #);" ><span class="k-icon k-delete"></span>Delete</a>
<?php endif;   ?>
            </script>
            <script type="text/javascript">
                function deleteClick(promotionID) {
                    var c = confirm("Do you want to delete?");
                    if (c === true) {
                        $.ajax({
                            type: "POST",
                            dataType: "json",
                            url: "../controller/promotion/promotion_list.php",
                            data: {promotion_id: promotionID},
                            success: function (result) {

                                if (result === true) {
                                    $(".k-i-refresh").click();
                                }
                            }
                        });
                    }
                }

            </script>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    var dataSource = new kendo.data.DataSource({
                        pageSize: 5,
                        transport: {
                            read: {
                                url: "../controller/promotion/promotion_list.php",
                                type: "GET"
                            },
                            destroy: {
                                url: "../controller/promotion/promotion_list.php",
                                type: "POST"
                            }
                        },
                        autoSync: false,
                        schema: {
                            data: "data",
                            total: "data.length",
                            model: {
                                id: "promotion_id",
                                fields: {
                                    promotion_id: {nullable: true},
                                    promotion_title: {type: "string"},
                                    promotion_code_prefix: {type: "string"},
                                    promotion_code_suffix: {type: "string"},
                                    promotion_code_predefined_user: {type: "string"},
                                    promotion_code_use_type: {type: "string"},
                                    promotion_discount_type: {type: "string"},
                                    promotion_discount_amount: {type: "number"},
                                    promotion_expire: {type: "string"},
                                    promotion_status: {type: "string"}
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
                            pageSizes: [5, 10, 20, 50],
                        },
                        sortable: true,
                        groupable: true,
                        columns: [
                            {field: "promotion_title", title: "Title", width: "120px"},
                            {field: "promotion_code_prefix", title: "Prefix", width: "80px"},
                            {field: "promotion_code_suffix", title: "Suffix", width: "80px"},
                            {field: "promotion_code_predefined_user", title: "Predefined<br/> User", width: "100px"},
                            {field: "promotion_code_use_type", title: "Use Type", width: "90px"},
                            {field: "promotion_discount_type", title: "Discount<br/> Type", width: "90px"},
                            {field: "promotion_discount_amount", title: "Discount<br/> Amount", width: "90px"},
                            {field: "promotion_expire", title: "Expire<br/> Date", width: "90px"},
                            {field: "promotion_status", title: "Status", width: "80px"},
                            
                            {
                                title: "Action", width: "240px",
                                template: kendo.template($("#edit_promotion").html())
                            }
                        ],
                    });
                });

            </script>
            <script type="text/javascript">
                $(document).ready(function () {
                    $('.k-grid-delete').click(function () {
                        $.ajax({
                            type: 'POST',
                            url: "../controller/promotion/promotion_list.php",
                            data: "",
                            success: function () {

                            }
                        });
                    });
                });
            </script>
        </div>

        <div class="clearfix"></div>
        <?php include basePath('admin/footer.php'); ?>

    </div>
    <script type="text/javascript">
        $("#promotionlist").addClass("active");
        $("#promotionlist").parent().parent().addClass("active");
        $("#promotionlist").parent().addClass("in");
    </script>

    <?php include basePath('admin/footer_script.php'); ?>
</body>
</html>
