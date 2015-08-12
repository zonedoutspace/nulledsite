<?php
include '../../config/config.php';
if (!checkAdminLogin()) {
    $link = baseUrl() . 'admin/login.php?err=' . base64_encode("You need to login first.");
    redirect($link);
}
include '../../lib/Zebra_Image.php';


$event_title = "";
$event_category_id = "";
$event_description = "";
$event_terms_conditions = "";
$event_web_logo = "";
$event_web_banner = "";
$event_eticket_banner = "";
$event_is_featured = "";
$event_featured_priority = "";
$event_is_coming = "";
$event_coming_priority = "";
$event_is_free = "";
$event_is_private = "";
$event_is_eticket = "";
$event_is_pticket = "";
$event_is_home_delivery = "";
$event_is_collectable = "";
$event_is_pickable = "";
$event_pick_details = "";
$event_is_COD = "";
$event_is_online_payable = "";
$event_status = "";



if (isset($_POST['event_title'])) {
    extract($_POST);

    if (!$event_is_featured OR $event_is_featured != "yes") {
        $event_is_featured = 'no';
    }

    if (!$event_is_coming OR $event_is_coming != "yes") {
        $event_is_coming = 'no';
    }

    if (!$event_is_free OR $event_is_free != "yes") {
        $event_is_free = 'no';
    }

    if (!$event_is_private OR $event_is_private != "yes") {
        $event_is_private = 'no';
    }

    if (!$event_is_eticket OR $event_is_eticket != "yes") {
        $event_is_eticket = 'no';
    }

    if (!$event_is_pticket OR $event_is_pticket != "yes") {
        $event_is_pticket = 'no';
    }
    if (!$event_is_home_delivery OR $event_is_home_delivery != "yes") {
        $event_is_home_delivery = 'no';
    }

    if (!$event_is_collectable OR $event_is_collectable != "yes") {
        $event_is_collectable = 'no';
    }

    if (!$event_is_pickable OR $event_is_pickable != "yes") {
        $event_is_pickable = 'no';
    }

    if (!$event_is_COD OR $event_is_COD != "yes") {
        $event_is_COD = 'no';
    }
    if (!$event_is_online_payable OR $event_is_online_payable != "yes") {
        $event_is_online_payable = 'no';
    }
    /*     * *************** Event Web Logo Image Code start Here *********************** */
    $event_web_logo_name = "";
    if ($_FILES["event_web_logo"]["tmp_name"] != '') {

        /*         * *****Renaming the image file******** */
        $event_web_logo = basename($_FILES['event_web_logo']['name']);
        $info = pathinfo($event_web_logo, PATHINFO_EXTENSION); /* it will return me like jpeg, gif, pdf, png */
        $event_web_logo_name = 'ewl_' . date("Y-m-d-H-i-s") . '.' . $info; /* create custom image name color id will add  */
        $event_web_logo_source = $_FILES["event_web_logo"]["tmp_name"];
        /*         * *****Renaming the image file******** */


        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/event_web_logo/')) {
            mkdir($config['IMAGE_UPLOAD_PATH'] . '/event_web_logo/', 0777, TRUE);
        }
        $image_target_path = $config['IMAGE_UPLOAD_PATH'] . '/event_web_logo/' . $event_web_logo_name;


        $zebra = new Zebra_Image();
        $zebra->source_path = $_FILES["event_web_logo"]["tmp_name"]; /* original image path */
        $zebra->target_path = $config['IMAGE_UPLOAD_PATH'] . '/event_web_logo/' . $event_web_logo_name;

        if (!$zebra->resize(400)) {
            zebraImageErrorHandaling($zebra->error);
        }
    }

    /*     * *************** Event Web Logo Image Code End Here *********************** */

    /*     * *************** Event Web Banner Image Code start Here *********************** */
    $event_web_banner_name = "";
    if ($_FILES["event_web_banner"]["tmp_name"] != '') {

        /*         * *****Renaming the image file******** */
        $event_web_banner = basename($_FILES['event_web_banner']['name']);
        $info = pathinfo($event_web_banner, PATHINFO_EXTENSION); /* it will return me like jpeg, gif, pdf, png */
        $event_web_banner_name = 'ewb_' . date("Y-m-d-H-i-s") . '.' . $info; /* create custom image name color id will add  */
        $event_web_banner_source = $_FILES["event_web_banner"]["tmp_name"];
        /*         * *****Renaming the image file******** */


        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/event_web_banner/')) {
            mkdir($config['IMAGE_UPLOAD_PATH'] . '/event_web_banner/', 0777, TRUE);
        }
        $image_target_path = $config['IMAGE_UPLOAD_PATH'] . '/event_web_banner/' . $event_web_banner_name;


        $zebra = new Zebra_Image();
        $zebra->source_path = $_FILES["event_web_banner"]["tmp_name"]; /* original image path */
        $zebra->target_path = $config['IMAGE_UPLOAD_PATH'] . '/event_web_banner/' . $event_web_banner_name;

        if (!$zebra->resize(400)) {
            zebraImageErrorHandaling($zebra->error);
        }
    }

    /*     * *************** Event Web Banner Image Code End Here *********************** */
    /*     * *************** Event Ticket Image Code start Here *********************** */
    $event_eticket_banner_name = "";
    if ($_FILES["event_eticket_banner"]["tmp_name"] != '') {

        /*         * *****Renaming the image file******** */
        $event_eticket_banner = basename($_FILES['event_eticket_banner']['name']);
        $info = pathinfo($event_eticket_banner, PATHINFO_EXTENSION); /* it will return me like jpeg, gif, pdf, png */
        $event_eticket_banner_name = 'eetb_' . date("Y-m-d-H-i-s") . '.' . $info; /* create custom image name color id will add  */
        $event_eticket_banner_source = $_FILES["event_eticket_banner"]["tmp_name"];
        /*         * *****Renaming the image file******** */


        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/event_eticket_banner/')) {
            mkdir($config['IMAGE_UPLOAD_PATH'] . '/event_eticket_banner/', 0777, TRUE);
        }
        $image_target_path = $config['IMAGE_UPLOAD_PATH'] . '/event_eticket_banner/' . $event_eticket_banner_name;


        $zebra = new Zebra_Image();
        $zebra->source_path = $_FILES["event_eticket_banner"]["tmp_name"]; /* original image path */
        $zebra->target_path = $config['IMAGE_UPLOAD_PATH'] . '/event_eticket_banner/' . $event_eticket_banner_name;

        if (!$zebra->resize(400)) {
            zebraImageErrorHandaling($zebra->error);
        }
    }

    /*     * *************** Event Ticket Image Code End Here *********************** */

    $event_title = mysqli_real_escape_string($con, $event_title);
    $event_category_id = mysqli_real_escape_string($con, $event_category_id);
    $event_description = mysqli_real_escape_string($con, $event_description);
    $event_terms_conditions = mysqli_real_escape_string($con, $event_terms_conditions);
    $event_web_logo = mysqli_real_escape_string($con, $event_web_logo_name);
    $event_web_banner = mysqli_real_escape_string($con, $event_web_banner_name);
    $event_eticket_banner = mysqli_real_escape_string($con, $event_eticket_banner_name);
    $event_is_featured = mysqli_real_escape_string($con, $event_is_featured);
    $$event_featured_priority = mysqli_real_escape_string($con, $event_featured_priority);
    $event_is_coming = mysqli_real_escape_string($con, $event_is_coming);
    $event_coming_priority = mysqli_real_escape_string($con, $event_coming_priority);
    $event_is_private = mysqli_real_escape_string($con, $event_is_private);
    $event_is_free = mysqli_real_escape_string($con, $event_is_free);
    $event_is_eticket = mysqli_real_escape_string($con, $event_is_eticket);
    $event_is_pticket = mysqli_real_escape_string($con, $event_is_pticket);
    $event_is_home_delivery = mysqli_real_escape_string($con, $event_is_home_delivery);
    $event_is_collectable = mysqli_real_escape_string($con, $event_is_collectable);
    $event_is_pickable = mysqli_real_escape_string($con, $event_is_pickable);
    $event_pick_details = mysqli_real_escape_string($con, $event_pick_details);
    $event_is_COD = mysqli_real_escape_string($con, $event_is_COD);
    $event_is_online_payable = mysqli_real_escape_string($con, $event_is_online_payable);
    $event_created_by = getSession("admin_id");
    $event_created_on = date("Y-m-d H:i:s");
    $event_status = "inactive";


    $check_Event = "select * from events where event_title = '$event_title'";
    $check_EventRun = mysqli_query($con, $check_Event);
    $countEvent = mysqli_num_rows($check_EventRun);
    if ($countEvent >= 1) {
        $err = "Event title already exists";
    } else {

        $insert_EventArray = '';
        $insert_EventArray .= ' event_title = "' . $event_title . '"';
        $insert_EventArray .= ', event_category_id = "' . $event_category_id . '"';
        $insert_EventArray .= ', event_description = "' . $event_description . '"';
        $insert_EventArray .= ', event_terms_conditions = "' . $event_terms_conditions . '"';
        $insert_EventArray .= ', event_web_logo = "' . $event_web_logo . '"';
        $insert_EventArray .= ', event_web_banner = "' . $event_web_banner . '"';
        $insert_EventArray .= ', event_eticket_banner = "' . $event_eticket_banner . '"';
        $insert_EventArray .= ', event_is_featured = "' . $event_is_featured . '"';
        $insert_EventArray .= ', event_featured_priority = "' . $event_featured_priority . '"';
        $insert_EventArray .= ', event_is_coming = "' . $event_is_coming . '"';
        $insert_EventArray .= ', event_coming_priority = "' . $event_coming_priority . '"';
        $insert_EventArray .= ', event_is_private = "' . $event_is_private . '"';
        $insert_EventArray .= ', event_is_free = "' . $event_is_free . '"';
        $insert_EventArray .= ', event_is_eticket = "' . $event_is_eticket . '"';
        $insert_EventArray .= ', event_is_pticket = "' . $event_is_pticket . '"';
        $insert_EventArray .= ', event_is_home_delivery = "' . $event_is_home_delivery . '"';
        $insert_EventArray .= ', event_is_collectable = "' . $event_is_collectable . '"';
        $insert_EventArray .= ', event_is_pickable = "' . $event_is_pickable . '"';
        $insert_EventArray .= ', event_pick_details = "' . $event_pick_details . '"';
        $insert_EventArray .= ', event_is_COD = "' . $event_is_COD . '"';
        $insert_EventArray .= ', event_is_online_payable = "' . $event_is_online_payable . '"';
        $insert_EventArray .= ', event_created_by = "' . $event_created_by . '"';
        $insert_EventArray .= ', event_created_on = "' . $event_created_on . '"';
        $insert_EventArray .= ', event_status = "' . $event_status . '"';

        $run_insert_query = "INSERT INTO events SET $insert_EventArray";
        $result = mysqli_query($con, $run_insert_query);
        //debug($run_insert_query);

        if (!$result) {
            $err = mysqli_error($con);
        } else {
            $event_id = mysqli_insert_id($con);
            //debug($last_id);exit();
            $msg = "Event saved successfully";
            $link = "created_event_list.php?msg=" . base64_encode($msg) . "&event_id=" . $event_id;
            redirect($link);
        }
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
        <script type="text/javascript" charset="utf-8">
            $(document).ready(function () {
                jQuery.getJSON("<?php echo baseUrl('admin/category/get_category.php'); ?>", function (data) {
                    console.log(data);
                    if (data !== "[]") {
                        var inlineDefault = new kendo.data.HierarchicalDataSource({
                            data: data
                        });
                        $("#treeview").kendoTreeView({
                            dataSource: inlineDefault,
                            template: kendo.template(jQuery("#treeview-template").html())
                        });
                    } else {
                        $("#treeview").html("");
                    }
                });
            });
        </script>
        <script>
            $(document).ready(function () {
                $("#event_featured_priority").kendoNumericTextBox({
                    min: 0,
                    format: "#"
                });
                $("#event_coming_priority").kendoNumericTextBox({
                    min: 0,
                    format: "#"
                });
            });
        </script>
    </head>
    <body class="">

        <?php include basePath('admin/header.php'); ?>
        <script id="treeview-template" type="text/kendo-ui-template">
            <input type='radio' name='event_category_id' id='event_category_id'
            value='#= item.category_id #' />#= item.category_title #
        </script>
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

        <div id="content" style="padding-left: 0px;">
            <h3 class="bg-white content-heading border-bottom strong">Create Event</h3>
            <div class="innerAll spacing-x2">
                <?php include basePath('admin/message.php'); ?>
                <!-- Content Start Here -->
                <form class="form-horizontal margin-none" method="post" autocomplete="off">
                    <div class="widget widget-inverse">
                        <div class="widget-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="createEventKendoTab">
                                        <ul>
                                            <li class="k-state-active">General Info</li>
                                            <li>Event Details</li>
                                            <li>Terms and Conditions</li>
                                            <li>Ticket Type</li>
                                            <li>Delivery Method</li>
                                            <li>Payment Method</li>
                                        </ul>

                                        <div>
                                            <div style="height: 15px;"></div>

                                            <span  id="eventError" style="color: red;margin-left: 100px;"></span>
                                            <div style="height: 15px;"></div>
                                            <div>
                                                <label>Event Title</label> 
                                                <input id="event_title" name="event_title" type="text" style="width: 48%;height: 20px;border: 1px solid black;margin-left: 30px;"/>
                                            </div>
                                            <div style="height: 15px;"></div>
                                            <div>
                                                <label>Category</label>
                                                <ul style="list-style: none;margin-left: 100px;margin-top: -20px;">
                                                    <li><input type="radio" name="event_category_id" id="event_category_id" value="0"/>Root Category</li>
                                                </ul>
                                                <div id="treeview" style="margin-left: 100px;margin-top: -10px;">
                                                </div> 
                                                <span id="treeviewError"></span>
                                            </div>
                                            <div style="height: 15px;"></div>
                                            <div>
                                                <label>Web Logo</label>
                                                <div style="margin-top: -2%;margin-left: 11%;">
                                                    <input  type="file" id="event_web_logo" name="event_web_logo"/>
                                                </div>
                                            </div>
                                            <div style="height: 15px;"></div>
                                            <div>
                                                <label>Web Banner</label>
                                                <div style="margin-top: -2%;margin-left: 11%;">
                                                    <input  type="file" id="event_web_logo" name="event_web_logo"/>
                                                </div>
                                            </div>
                                            <div style="height: 15px;"></div>
                                            <div>
                                                <label>Ticket Banner</label>
                                                <div style="margin-top: -2%;margin-left: 11%;">
                                                    <input  type="file" id="event_web_logo" name="event_web_logo"/>
                                                </div>
                                            </div>
                                            <div style="height: 15px;"></div>
                                            <div>
                                                <label>Is Featured?</label>
                                                <div style="margin-left: 107px;margin-top: -22px;">
                                                    <input onchange="javascript:checkFeatured();" type="checkbox" name="event_is_featured" id="event_is_featured" value="yes" <?php
                                                    if ($event_is_featured == 'yes') {
                                                        echo 'checked="checked"';
                                                    }
                                                    ?>/>
                                                </div>
                                                <div style="display: none;margin-top: -20px;" id="featShow">
                                                    <input type="number" style="width: 15%;height: 20px;border: 1px solid black;margin-left: 134px;" />
                                                </div>
                                            </div>
                                            <div style="height: 15px;"></div>
                                            <div>
                                                <label>Is Upcoming?</label>
                                                <div style="margin-left: 107px;margin-top: -22px;">
                                                    <input onchange="javascript:checkUpcoming();" type="checkbox" name="event_is_coming" id="event_is_coming" value="yes" <?php
                                                    if ($event_is_coming == 'yes') {
                                                        echo 'checked="checked"';
                                                    }
                                                    ?>/>
                                                </div>
                                                <div style="display: none;margin-top: -20px;" id="upShow">
                                                    <input type="number" style="width: 15%;height: 20px;border: 1px solid black;margin-left: 134px;" />                                                </div>
                                            </div>
                                            <div style="height: 15px;"></div>
                                            <div>
                                                <label>Is Free?</label>
                                                <div style="margin-left: 107px;margin-top: -22px;">
                                                    <input type="checkbox" name="event_is_free" id="event_is_free" value="yes" <?php
                                                    if ($event_is_free == 'yes') {
                                                        echo 'checked="checked"';
                                                    }
                                                    ?>/>
                                                </div>
                                            </div>
                                            <div style="height: 15px;"></div>
                                            <div>
                                                <label>Is Private?</label>
                                                <div style="margin-left: 107px;margin-top: -22px;">
                                                    <input type="checkbox" name="event_is_private" id="event_is_private" value="yes" <?php
                                                    if ($event_is_private == 'yes') {
                                                        echo 'checked="checked"';
                                                    }
                                                    ?>/>
                                                </div>
                                            </div>
                                            <div style="height: 15px;"></div>
                                            <div class="form-actions">
                                                <button type="button" id="btnGeneralInfoNext" name="btnGeneralInfoNext" class="btn btn-primary" style="margin-left: -5px;"><i class="fa fa-check-circle"></i> Next</button>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="row">
                                                <div style="height: 20px"></div>
                                            </div>
                                            <div class="row">
                                                <span id="detailsError"></span>
                                                <div class="col-md-11.5">
                                                    <textarea id="event_description" name="event_description" rows="3" cols="30"><?php echo html_entity_decode($event_description, ENT_QUOTES | ENT_IGNORE, "UTF-8"); ?></textarea>
                                                </div>
                                            </div>
                                            <div style="height: 20px"></div>
                                            <div class="form-actions">
                                                <button  type="button" id="btnEventDescriptionNext" name="btnEventDescriptionNext" class="btn btn-primary"><i class="fa fa-check-circle"></i> Next</button>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="row">
                                                <div style="height: 20px"></div>
                                            </div>
                                            <div class="row">
                                                <span id="tcError"></span>
                                                <div class="col-md-11.5">
                                                    <textarea id="event_terms_conditions" name="event_terms_conditions" rows="3" cols="30"><?php echo html_entity_decode($event_terms_conditions, ENT_QUOTES | ENT_IGNORE, "UTF-8"); ?></textarea>
                                                </div>
                                            </div>
                                            <div style="height: 20px"></div>
                                            <div class="form-actions">
                                                <button type="button" id="btnEventTermsAndCondiotnNext" name="btnEventTermsAndCondiotnNext" class="btn btn-primary"><i class="fa fa-check-circle"></i> Next</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="clearfix"></div>

        <?php include basePath('admin/footer.php'); ?>
        <!-- // Footer END -->
    </div><!-- // Main Container Fluid END -->
    <script type="text/javascript">
        $(document).ready(function () {
            var createEventTab = $("#createEventKendoTab").kendoTabStrip({
                animation: {
                    open: {
                        effects: "fadeIn"
                    }
                }
            }).data("kendoTabStrip");
            createEventTab.enable(createEventTab.tabGroup.children("li:eq(1)"), false);
            createEventTab.enable(createEventTab.tabGroup.children("li:eq(2)"), false);
            createEventTab.enable(createEventTab.tabGroup.children("li:eq(3)"), false);
            createEventTab.enable(createEventTab.tabGroup.children("li:eq(4)"), false);
            createEventTab.enable(createEventTab.tabGroup.children("li:eq(5)"), false);
        });
    </script>

    <script>
        $(document).ready(function () {
            $("#event_description").kendoEditor({
                tools: [
                    "bold", "italic", "underline", "strikethrough", "justifyLeft", "justifyCenter", "justifyRight", "justifyFull",
                    "insertUnorderedList", "insertOrderedList", "indent", "outdent", "createLink", "unlink", "insertImage",
                    "insertFile", "subscript", "superscript", "createTable", "addRowAbove", "addRowBelow", "addColumnLeft",
                    "addColumnRight", "deleteRow", "deleteColumn", "viewHtml", "formatting", "cleanFormatting",
                    "fontName", "fontSize", "foreColor", "backColor"
                ]
            });
        });
    </script>
    <script>
        $(document).ready(function () {

            $("#event_terms_conditions").kendoEditor({
                tools: [
                    "bold", "italic", "underline", "strikethrough", "justifyLeft", "justifyCenter", "justifyRight", "justifyFull",
                    "insertUnorderedList", "insertOrderedList", "indent", "outdent", "createLink", "unlink", "insertImage",
                    "insertFile", "subscript", "superscript", "createTable", "addRowAbove", "addRowBelow", "addColumnLeft",
                    "addColumnRight", "deleteRow", "deleteColumn", "viewHtml", "formatting", "cleanFormatting",
                    "fontName", "fontSize", "foreColor", "backColor"
                ]
            });
        });
    </script>
    <script>
        $(document).ready(function () {

            $("#event_pick_details").kendoEditor({
                tools: [
                    "bold", "italic", "underline", "strikethrough", "justifyLeft", "justifyCenter", "justifyRight", "justifyFull",
                    "insertUnorderedList", "insertOrderedList", "indent", "outdent", "createLink", "unlink", "insertImage",
                    "insertFile", "subscript", "superscript", "createTable", "addRowAbove", "addRowBelow", "addColumnLeft",
                    "addColumnRight", "deleteRow", "deleteColumn", "viewHtml", "formatting", "cleanFormatting",
                    "fontName", "fontSize", "foreColor", "backColor"
                ]
            });
        });
    </script>


    <script type="text/javascript">


        function tabControl(id, totalTab) {
            var tabID = id;
            var createEventTab = $("#createEventKendoTab").kendoTabStrip({
                animation: {
                    open: {
                        effects: "fadeIn"
                    }
                }
            }).data("kendoTabStrip");
            createEventTab.select(tabID);

            for (var i = 0; i < totalTab; i++) {
                if (i <= tabID) {
                    createEventTab.enable(createEventTab.tabGroup.children("li:eq(" + i + ")"), true);
                } else {
                    createEventTab.enable(createEventTab.tabGroup.children("li:eq(" + i + ")"), false);
                }
            }
        }

        $(document).ready(function () {
            $("#btnGeneralInfoNext").click(function () {
                var event_title = $("#event_title").val();
                var event_category_id = $("input[name='event_category_id']:checked").val();

                if (event_title === "" || typeof event_category_id === "undefined") {
                    if (event_title === "") {

                        $("#event_title").css("background-color", "#FFF0F0");
                        $("#eventError").html('Please enter event title');
                    }

                    if (typeof event_category_id === "undefined") {

                        $("#treeviewError").html('<em style="color:red;" >Please select event category</em>');
                    }
                } else {
                    $("#treeviewError").html('');
                    tabControl(1, 5);
                }

            });
            $("#event_title").keyup(function () {
                var event_title = $("#event_title").val();
                if (event_title !== "") {
                    $("#event_title").css("background-color", "white");
                    $("#event_title_invalid").html('');
                } else {
                    $("#event_title_invalid").html('');
                    $("#event_title").css("background-color", "#FFF0F0");
                    $("#event_title").after('<em style="color:red;" id="event_title_invalid" for="event_title">Please enter event title</em>');
                }
            });
        });

    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#btnEventDescriptionNext").click(function () {
                var event_description = $("#event_description").val();

                if (event_description === "") {

                    $("#detailsError").html('<em style="color:red;margin-left:10px;" >Please enter event details</em>');
                } else {
                    $("#detailsError").html('');
                    tabControl(2, 5);
                }
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#btnEventTermsAndCondiotnNext").click(function () {
                var event_terms_conditions = $("#event_terms_conditions").val();

                if (event_terms_conditions === "") {

                    $("#tcError").html('<em style="color:red;margin-left:10px;" >Please enter event terms and conditions</em>');
                } else {
                    $("#tcError").html('');
                    tabControl(3, 5);
                }
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#btnTicketTypeNext").click(function () {
                var event_is_eticket = $("input[name='event_is_eticket']:checked").val();
                var event_is_pticket = $("input[name='event_is_pticket']:checked").val();

                if (typeof event_is_eticket === "undefined" && typeof event_is_pticket === "undefined") {

                    $("#ticketTypeError").html('<em style="color:red;margin-left: -10px;" >Please select atleast one ticket type</em>');
                } else {
                    $("#ticketTypeError").html('');
                    tabControl(4, 5);
                }
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#btnDeliveryMethodNext").click(function () {
                var event_is_home_delivery = $("input[name='event_is_home_delivery']:checked").val();
                var event_is_collectable = $("input[name='event_is_collectable']:checked").val();
                var event_is_pickable = $("input[name='event_is_pickable']:checked").val();
                var event_pick_details = $("#event_pick_details").val();

                if (typeof event_is_home_delivery === "undefined" && typeof event_is_collectable === "undefined" && typeof event_is_pickable === "undefined") {
                    $("#deliveryError").html('<em style="color:red;margin-left: -10px;" >Please select atleaset one delivery method</em>');
                }
                else {
                    if (typeof event_is_pickable !== "undefined") {
                        if (event_pick_details === "") {
                            $("#msgError").html('<em style="color:red;margin-left: 6px;" >Please enter event pick point details</em>');
                        } else {
                            $("#msgError").html('');
                            $("#deliveryError").html('');
                            tabControl(5, 5);
                        }
                    }
                    else {
                        $("#deliveryError").html('');
                        tabControl(5, 5);
                    }
                }

            });
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            $("#btnSaveEvent").click(function () {
                var event_is_COD = $("input[name='event_is_COD']:checked").val();
                var event_is_online_payable = $("input[name='event_is_online_payable']:checked").val();

                if (typeof event_is_COD === "undefined" && typeof event_is_online_payable === "undefined") {

                    $("#paymentEror").html('<em style="color:red;margin-left: -10px;" >Please select atleast one payment method</em>');
                } else {
                    $('#createEvent').submit();
                }
            });
        });
    </script>

    <script type="text/javascript">
        function checkFeatured() {
            if ($('input[name="event_is_featured"]:checked').length > 0) {
                $("#featShow").fadeIn();
            } else {
                $("#featShow").fadeOut();
            }
        }
        function checkUpcoming() {
            if ($('input[name="event_is_coming"]:checked').length > 0) {
                $("#upShow").fadeIn();
            } else {
                $("#upShow").fadeOut();
            }
        }

        function checkPickable() {
            if ($('input[name="event_is_pickable"]:checked').length > 0) {
                $("#pickDetailsShow").fadeIn();
            } else {
                $("#pickDetailsShow").fadeOut();
            }
        }
    </script>

    <script type="text/javascript">
        $("#eve").addClass("active");
        $("#eve").parent().parent().addClass("active");
        $("#eve").parent().addClass("in");
    </script>

    <?php include basePath('admin/footer_script.php'); ?>
</body>
</html>
