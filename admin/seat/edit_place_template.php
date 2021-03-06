<?php
include '../../config/config.php';
$adminID = 0;
if (!checkAdminLogin()) {
    $link = baseUrl() . 'admin/login.php?err=' . base64_encode("You need to login first.");
    redirect($link);
} else {
    $adminID = getSession('admin_id');
}
$ST_id = 0;
$ST_SPC_id = 0;
$ST_SP_id = 0;
$ST_user_limit = "";
$template_column = 'Define Total Column';
$template_row = 'Define Total Row';
$generatedHtml = '';
$seats = array();
$count = 0;

if (isset($_GET['ST_id'])) {
    $ST_id = $_GET['ST_id'];
}

if (isset($_POST['btnCreatePlan'])) {
    extract($_POST);
    if ($ST_SP_id == "") {
        $err = "Template Place Title is required.";
    } elseif ($ST_SPC_id == "") {
        $err = "Template Title is required.";
    } elseif ($ST_user_limit == "") {
        $err = "Template User Limit Required.";
    } elseif ($template_column == 0) {
        $err = "Column number is required.";
    } elseif ($template_row == 0) {
        $err = "Row number is required.";
    } else {
        $serSeatPlan = serialize($seats);
        $addSeatPlan = '';
        $addSeatPlan .=' ST_SP_id = "' . validateInput($ST_SP_id) . '"';
        $addSeatPlan .=', ST_SPC_id = "' . validateInput($ST_SPC_id) . '"';
        $addSeatPlan .=', ST_user_limit = "' . validateInput($ST_user_limit) . '"';
        $addSeatPlan .=', ST_column_count = "' . validateInput($template_column) . '"';
        $addSeatPlan .=', ST_row_count = "' . validateInput($template_row) . '"';
        $addSeatPlan .=', ST_template_array = "' . validateInput($serSeatPlan) . '"';
        $addSeatPlan .=', ST_updated_by = "' . validateInput($adminID) . '"';

        $sqlUpdateTemplate = "UPDATE seat_template SET $addSeatPlan WHERE ST_id=$ST_id";
        $resultUpdateTemplate = mysqli_query($con, $sqlUpdateTemplate);
        if ($resultUpdateTemplate) {
            $msg = "Seat Template Updated Successfully";
            $link = "place_template_list.php?msg=" . base64_encode($msg);
            redirect($link);
        } else {
            if (DEBUG) {
                $err = "resultUpdateTemplate error: " . mysqli_error($con);
            } else {
                $err = "resultUpdateTemplate query failed.";
            }
        }
    }
}

// getting seat place data
$arrPlaces = array();
$sqlGetPlace = "SELECT SP_title,SP_id FROM seat_place";
$resultGetPlace = mysqli_query($con, $sqlGetPlace);
if ($resultGetPlace) {
    while ($resultGetPlaceObj = mysqli_fetch_object($resultGetPlace)) {
        $arrPlaces[] = $resultGetPlaceObj;
    }
} else {
    if (DEBUG) {
        $err = "resultGetPlace error: " . mysqli_error($con);
    } else {
        $err = "resultGetPlace query failed.";
    }
}
// getting seat place data end
// Getting data from seat template
$sqlGetTemplateData = "SELECT * FROM seat_template WHERE ST_id=$ST_id";
$resultGetTemplateData = mysqli_query($con, $sqlGetTemplateData);
if ($resultGetTemplateData) {
    $resultGetTemplateDataObj = mysqli_fetch_object($resultGetTemplateData);
    $ST_SP_id = $resultGetTemplateDataObj->ST_SP_id;
    $ST_SPC_id = $resultGetTemplateDataObj->ST_SPC_id;
    $ST_user_limit = $resultGetTemplateDataObj->ST_user_limit;
    $template_column = $resultGetTemplateDataObj->ST_column_count;
    $template_row = $resultGetTemplateDataObj->ST_row_count;
    $seats = $resultGetTemplateDataObj->ST_template_array;
} else {
    if (DEBUG) {
        $err = "resultGetTemplateData error: " . mysqli_error($con);
    } else {
        $err = "resultGetTemplateData query failed.";
    }
}
$seats = unserialize($seats);

// getting coordinate title data
$arrayCoordinate = array();
$sqlCoordinateTitle = "SELECT * FROM seat_place_coordinate WHERE SPC_SP_id =$ST_SP_id";
$resultCoordinateTitle = mysqli_query($con, $sqlCoordinateTitle);
if ($resultCoordinateTitle) {
    while ($resultCoordinateTitleObj = mysqli_fetch_object($resultCoordinateTitle)) {
        $arrayCoordinate[] = $resultCoordinateTitleObj;
    }
} else {
    if (DEBUG) {
        $err = "resultCoordinateTitle error: " . mysqli_error($con);
    } else {
        $err = "resultCoordinateTitle query failed.";
    }
}

if ($template_column > 0 AND $template_row > 0) {
    $count = 1;
    $generatedHtml .= '<table style="margin: 0px auto;" border="1">';
    for ($i = 0; $i < $template_row; $i++) {
        $generatedHtml .= '<tr>';
        for ($j = 0; $j < $template_column; $j++) {
            $checked = '';
            $generatedHtml .= '<td style="padding: 8px;" class="text-center">';
            if (array_key_exists($count, $seats)) {
                $generatedHtml .= '<input type="checkbox" value="Checked" name="seats[' . $count . ']" ' . $checked . ' checked="checked">&nbsp;' . $count . '';
            } else {
                $generatedHtml .= '<input type="checkbox" value="Checked" name="seats[' . $count . ']" ' . $checked . '>&nbsp;' . $count . '';
            }
            $generatedHtml .= '</td>';
            $count++;
        }
        $generatedHtml .= '</tr>';
    }
    $generatedHtml .= '</table>';
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
        <script src="http://www.datejs.com/build/date.js" type="text/javascript"></script>
    </head>
    <body>
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
        <div id="content"><h3 class="bg-white content-heading border-bottom strong">Edit Seat Template</h3>

            <div class="innerAll spacing-x2">
                <?php include basePath('admin/message.php'); ?>
                <form class="form-horizontal margin-none" method="post" autocomplete="off" id="faqCreate">

                    <div class="widget widget-inverse">
                        <div class="widget-body">
                            <div class="row">
                                <div class="col-md-9">

                                    <div class="form-group">
                                        <label  class="col-md-4 control-label"></label>
                                        <div class="col-md-8" id="faqError"></div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="venueTitle">Select Place Title</label>
                                        <div class="col-md-8">
                                            <select class="form-control" name="ST_SP_id" onchange="javascript:getTemplateObj(this.value);">
                                                <option value="">Select Place Title</option>
                                                <?php if (count($arrPlaces) > 0): ?>
                                                    <?php foreach ($arrPlaces AS $Place): ?>
                                                        <option value="<?php echo $Place->SP_id; ?>"<?php
                                                        if ($Place->SP_id == $ST_SP_id) {
                                                            echo ' selected="selected"';
                                                        }
                                                        ?>><?php echo $Place->SP_title; ?></option>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Select Template Title</label>
                                        <div class="col-md-8">
                                            <span id="showTemplate">
                                                <select class="form-control" name="ST_SPC_id">
                                                    <option value="">Select Place Title First</option>
                                                    <?php if (count($arrayCoordinate) > 0): ?>
                                                        <?php foreach ($arrayCoordinate AS $coordinate): ?>
                                                            <option value="<?php echo $coordinate->SPC_id; ?>"<?php
                                                            if ($coordinate->SPC_id == $ST_SPC_id) {
                                                                echo ' selected="selected"';
                                                            }
                                                            ?>><?php echo $coordinate->SPC_title; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Set Per User Limit</label>
                                        <div class="col-md-8">
                                            <input class="form-control" id="ST_user_limit" value="<?php echo $ST_user_limit; ?>" name="ST_user_limit" type="number" min="1" />
                                        </div>

                                    </div> 


                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Define Template</label>
                                        <div class="col-md-8">
                                            <div class="col-md-5">
                                                <input class="form-control" id="seatColumn" value="<?php echo $template_column; ?>" name="template_column" type="number" placeholder="Define Total Column" />
                                            </div>
                                            <div class="col-md-5">
                                                <input class="form-control" id="seatRow" value="<?php echo $template_row; ?>" name="template_row" type="number" placeholder="Define Total Row" />
                                            </div>
                                            <div class="col-md-2">
                                                <button onclick="javascript:generateSeats();" type="button"  class="btn btn-primary" ><i class="fa fa-check-circle"></i> Generate</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="generateTable" class="col-md-12">
                                <?php echo $generatedHtml; ?>
                            </div>


                            <hr class="separator" />
                            <div class="form-actions">
                                <button type="submit"  id="btnCreatePlan" name="btnCreatePlan" class="btn btn-primary" ><i class="fa fa-check-circle"></i> Update Template</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>



        <div class="clearfix"></div>
        <!-- // Sidebar menu & content wrapper END -->
        <?php include basePath('admin/footer.php'); ?>
        <script>
            function generateSeats() {
                var column = $("#seatColumn").val();
                var row = $("#seatRow").val();
                var count = 1;

                if (column > 0 && row > 0) {
//                alert(column);
                    var seatHtml = '<table style="margin: 0px auto;" border="1">';
                    for (var a = 0; a < row; a++) {
                        seatHtml += '<tr>';
                        for (var b = 0; b < column; b++) {
                            seatHtml += '<td style="padding: 8px;" class="text-center">';
                            seatHtml += '<input type="checkbox" value="Checked" name="seats[' + count + ']" checked="checked">&nbsp;' + count + '</td>';
                            count++;
                        }
                        seatHtml += '</tr>';
                    }
                    seatHtml += '</table>';
                } else {

                }
                $("#generateTable").html(seatHtml);

            }
        </script>

        <script type="text/javascript">
            function getTemplateObj(placeID) {
                var newHtml = '';

                if (placeID > 0) {
                    $.ajax({
                        type: "POST",
                        url: baseUrl + "admin/ajax/ajaxGetSeatTemplate.php",
                        dataType: "json",
                        data: {placeID: placeID},
                        success: function (response) {
                            var obj = response;

                            if (obj.output === "success") {
                                newHtml += '<select class="form-control" name="ST_SPC_id">';
                                newHtml += '<option value="">Select Template Title</option>';
                                if (obj.arrGetPlaceTemplate.length > 0) {
                                    $.each(obj.arrGetPlaceTemplate, function (key, Place) {
                                        newHtml += '<option value="' + Place.SPC_id + '">' + Place.SPC_title + '</option>';
                                    });
                                }
                                newHtml += '</select>';

                                $("#showTemplate").html(newHtml);
                            } else {
                                error(obj.msg);
                            }
                        }
                    });
                }
            }

        </script>
        <script type="text/javascript">
            $("#placeTemplateList").addClass("active");
            $("#placeTemplateList").parent().parent().addClass("active");
            $("#placeTemplateList").parent().addClass("in");
        </script>
        <?php include basePath('admin/footer_script.php'); ?>
    </body>
</html>