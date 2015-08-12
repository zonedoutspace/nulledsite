<?php
include '../../config/config.php';
if (!checkAdminLogin()) {
    $link = baseUrl() . 'admin/login.php?err=' . base64_encode("You need to login first.");
    redirect($link);
}
if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];
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


        <div id="content"><h3 class="bg-white content-heading border-bottom strong">Event List</h3>
            <div style="margin-left: 10px;margin-right: 10px;margin-top: 5px;">
                <?php include basePath('admin/message.php'); ?>
            </div>
            <div class="k-grid  k-secondary" data-role="grid" style="margin-left: 10px;margin-right: 10px;">
                <?php if (checkPermission('event', 'create', getSession('admin_type'))): ?>
                    <div class="k-toolbar k-grid-toolbar">
                        <a class="k-button k-button-icontext k-grid-add" href="<?php echo baseUrl('admin/event/index.php'); ?>">
                            <span class="k-icon k-add"></span>
                            Add New Event
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            <!-- Content Start Here -->

            <?php if (checkPermission('event', 'read', getSession('admin_type'))): ?>
                <div id="grid" style="margin-left: 10px;margin-right: 10px;"></div>
            <?php else : ?>
                <div style="margin-left: 10px;margin-right: 10px;"><h5 class="text-center">You dont have permission to view the content</h5></div>
            <?php endif; ?>


            <script id="options" type="text/x-kendo-template">
<?php if (checkPermission('venue', 'create', getSession('admin_type'))): ?>
                    <a class="k-button k-button-icontext k-grid-add" href="<?php echo baseUrl('admin/venue/event_venue_list.php'); ?>?event_id=#= event_id #"><span class="k-icon k-add"></span>Venue</a>
<?php endif; ?>
<?php if (checkPermission('gallery', 'create', getSession('admin_type'))): ?>   
                    <a class="k-button k-button-icontext k-grid-add" href="<?php echo baseUrl('admin/gallery/created_gallery_list.php'); ?>?event_id=#= event_id #"><span class="k-icon k-add"></span>Gallery</a>
<?php endif; ?>
<?php if (checkPermission('event_faq', 'create', getSession('admin_type'))): ?>     
                    <a class="k-button k-button-icontext k-grid-add" href="<?php echo baseUrl('admin/event_faq/created_faq_list.php'); ?>?event_id=#= event_id #"><span class="k-icon k-add"></span>FAQ</a>
<?php endif; ?>
<?php if (checkPermission('event_guest', 'create', getSession('admin_type'))): ?>  
                    <a class="k-button k-button-icontext k-grid-add" href="<?php echo baseUrl('admin/event_guest/created_guest_list.php'); ?>?event_id=#= event_id #"><span class="k-icon k-add"></span>Guest</a>
<?php endif; ?>
            </script>
            <script id="edit_event" type="text/x-kendo-template">
                <a class="k-button k-button-icontext k-grid-edit" href="<?php echo baseUrl('admin/event/edit_event.php'); ?>?event_id=#= event_id #"><span class="k-icon k-edit"></span>Edit</a>
                <a class="k-button k-button-icontext k-grid-delete" onclick="javascript:deleteClick(#= event_id #);" ><span class="k-icon k-delete"></span>Delete</a>
            </script>
            <script type="text/javascript">
                function deleteClick(eventID) {
                    var c = confirm("Do you want to delete?");
                    if (c === true) {
                        $.ajax({
                            type: "POST",
                            dataType: "json",
                            url: "../controller/event/created_event_list.php?event_id=<?php echo $event_id; ?>",
                            data: {event_id: eventID},
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
                                url: "../controller/event/created_event_list.php?event_id=<?php echo $event_id; ?>",
                                type: "GET"
                            },
                            destroy: {
                                url: "../controller/event/created_event_list.php?event_id=<?php echo $event_id; ?>",
                                type: "POST"
                            }
                        },
                        autoSync: false,
                        schema: {
                            data: "data",
                            total: "data.length",
                            model: {
                                id: "event_id",
                                fields: {
                                    event_id: {nullable: true},
                                    event_title: {type: "string", validation: {required: true}},
                                    event_category_id: {type: "number"},
                                    category_title: {type: "string"},
                                    event_web_logo: {type: "string"},
                                    event_is_featured: {type: "string"},
                                    event_is_coming: {type: "string"},
                                    event_status: {type: "string"}
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
                            {field: "event_title", title: "Event Title", width: "150px"},
                            {field: "category_title", title: "Category", width: "150px"},
                            {field: "event_web_logo",
                                title: "Web Logo",
                                width: "110px",
                                template: "<img src='<?php echo baseUrl("upload/event_web_logo/") ?>#=event_web_logo#' height='50' width='88'/>"

                            },
                            {field: "event_is_featured", title: "Featured ?", width: "100px"},
                            {field: "event_is_coming", title: "Upcoming ?", width: "100px"},
                            {field: "event_status", title: "Status", width: "100px"},
                            {
                                title: "Options", width: "325px",
                                template: kendo.template($("#options").html())
                            },
                            {
                                title: "Action", width: "180px",
                                template: kendo.template($("#edit_event").html())
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
                            url: "../controller/event/created_event_list.php?event_id=<?php echo $event_id; ?>",
                            data: "",
                            success: function () {

                            }
                        });
                    });
                });
            </script>
            <!-- Content End Here -->

        </div>

        <div class="clearfix"></div>
        <!-- // Sidebar menu & content wrapper END -->
        <?php include basePath('admin/footer.php'); ?>
        <!-- // Footer END -->
        <script type="text/javascript">
            $("#evelist").addClass("active");
            $("#evelist").parent().parent().addClass("active");
            $("#evelist").parent().addClass("in");
        </script>

    </div><!-- // Main Container Fluid END -->

    <?php include basePath('admin/footer_script.php'); ?>
</body>
</html>
