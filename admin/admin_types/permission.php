<?php
include '../../config/config.php';
$adminID = 0;
if (!checkAdminLogin()) {
    $link = baseUrl() . 'admin/login.php?err=' . base64_encode("You need to login first.");
    redirect($link);
} else {
    $adminID = getSession('admin_id');
}

$adminTypeID = 0;
$adminTypeName = '';
$arrNewPermission = array();
$arrCurrentPermission = array();
$event_access = '';
$sales = '';
$profit = '';
$event = '';
$user = '';

if (isset($_GET['type'])) {
    $adminTypeID = $_GET['type'];
}

//getting whole permission xml into array
$arrPermissionXml = array();
$arrPermissionXml = readPermissionXmlArray();


//getting existing permission for defined admin type from database
$arrExistPermission = array();
$sqlGetPermission = "SELECT * FROM admin_permission WHERE AP_AT_id=$adminTypeID";
$resultGetPermission = mysqli_query($con, $sqlGetPermission);
if ($resultGetPermission) {
    while ($resultGetPermissionObj = mysqli_fetch_object($resultGetPermission)) {
        $arrExistPermission[] = $resultGetPermissionObj->AP_module_name . "," . $resultGetPermissionObj->AP_action_name;
    }
} else {
    if (DEBUG) {
        $err = "resultGetPermission error: " . mysqli_error($con);
    } else {
        $err = "resultGetPermission query failed.";
    }
}


if (isset($_POST['submit'])) {
    extract($_POST);

    if (!isset($sales) OR $sales != "yes") {
        $sales = "no";
    }

    if (!isset($profit) OR $profit != "yes") {
        $profit = "no";
    }

    if (!isset($event) OR $event != "yes") {
        $event = "no";
    }

    if (!isset($user) OR $user != "yes") {
        $user = "no";
    }


    $updateAdminTypePermission = '';
    $updateAdminTypePermission .= ' AT_event_permission = "' . mysqli_real_escape_string($con, $event_access) . '"';
    $updateAdminTypePermission .= ', AT_sales_report = "' . mysqli_real_escape_string($con, $sales) . '"';
    $updateAdminTypePermission .= ', AT_profit_report = "' . mysqli_real_escape_string($con, $profit) . '"';
    $updateAdminTypePermission .= ', AT_event_report = "' . mysqli_real_escape_string($con, $event) . '"';
    $updateAdminTypePermission .= ', AT_user_report = "' . mysqli_real_escape_string($con, $user) . '"';


    $sqlUpdateAdminTypePermission = "UPDATE admin_types SET $updateAdminTypePermission WHERE AT_id=$adminTypeID";
    $resultUpdateAdminTypePermission = mysqli_query($con, $sqlUpdateAdminTypePermission);

    if (!$resultUpdateAdminTypePermission) {
        if (DEBUG) {
            $err = "resultUpdateAdminTypePermission error: " . mysqli_error($con);
        } else {
            $err = "resultUpdateAdminTypePermission query failed.";
        }
    }

    if (isset($perm) AND isset($arrExistPermission)) {
        $arrNewPermission = $perm;
        $processStatus = false;

        $arrItemsToDelete = array();
        $arrItemsToAdd = array();

        $arrItemsToAdd = array_diff($arrNewPermission, $arrExistPermission);
        $arrItemsToDelete = array_diff($arrExistPermission, $arrNewPermission);

        $arrItemsToAdd = array_values($arrItemsToAdd);
        $arrItemsToDelete = array_values($arrItemsToDelete);

        if (count($arrItemsToAdd) > 0) {
            foreach ($arrItemsToAdd AS $AddPerm) {
                $values = explode(',', $AddPerm);
                $moduleName = $values[0];
                $actionName = $values[1];

                $insertPermission = '';
                $insertPermission .= ' AP_AT_id = "' . intval($adminTypeID) . '"';
                $insertPermission .= ', AP_module_name = "' . mysqli_real_escape_string($con, $moduleName) . '"';
                $insertPermission .= ', AP_action_name = "' . mysqli_real_escape_string($con, $actionName) . '"';
                $insertPermission .= ', AP_created_on = "' . mysqli_real_escape_string($con, date("Y-m-d H:i:s")) . '"';
                $insertPermission .= ', AP_created_by = "' . intval($adminID) . '"';
                $insertPermission .= ', AP_updated_by = "' . intval($adminID) . '"';

                $sqlInsertPermission = "INSERT INTO admin_permission SET $insertPermission";
                $resultInsertPermission = mysqli_query($con, $sqlInsertPermission);

                if ($resultInsertPermission) {
                    $processStatus = true;
                } else {
                    if (DEBUG) {
                        $err = "resultInsertPermission error: " . mysqli_error($con);
                    }
                }
            }
        } else {
            $processStatus = true;
        }


        if (count($arrItemsToDelete) > 0) {
            foreach ($arrItemsToDelete AS $DeletePerm) {
                $values = explode(',', $DeletePerm);
                $moduleName = $values[0];
                $actionName = $values[1];

                $sqlDeletePermission = "DELETE FROM admin_permission "
                        . "WHERE AP_AT_id=$adminTypeID "
                        . "AND AP_module_name='$moduleName' "
                        . "AND AP_action_name='$actionName'";
                $resultDeletePermission = mysqli_query($con, $sqlDeletePermission);

                if ($resultDeletePermission) {
                    $processStatus = true;
                } else {
                    if (DEBUG) {
                        $err = "resultDeletePermission error: " . mysqli_error($con);
                    }
                }
            }
        } else {
            $processStatus = true;
        }


        if ($processStatus == true) {
            $msg = "Permission updated successfully.";
            $arrCurrentPermission = $arrNewPermission;
        } else {
            $msg = "Permission updated failed, revoked to old values.";
            $arrCurrentPermission = $arrExistPermission;
        }
    }
} else {
    $arrCurrentPermission = $arrExistPermission;
}



$sqlAdminType = "SELECT * FROM admin_types WHERE AT_id=$adminTypeID";
$resultAdminType = mysqli_query($con, $sqlAdminType);
if ($resultAdminType) {
    $resultAdminTypeObj = mysqli_fetch_object($resultAdminType);
    if (isset($resultAdminTypeObj->AT_id)) {
        $adminTypeName = $resultAdminTypeObj->AT_type;
        $event_access = $resultAdminTypeObj->AT_event_permission;
        $sales = $resultAdminTypeObj->AT_sales_report;
        $profit = $resultAdminTypeObj->AT_profit_report;
        $event = $resultAdminTypeObj->AT_event_report;
        $user = $resultAdminTypeObj->AT_user_report;
    }
} else {
    if (DEBUG) {
        $err = "resultAdminType error: " . mysqli_error($con);
    } else {
        $err = "resultAdminType query failed.";
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

        <div id="content" style="padding-left: 0px;">
            <h3 class="bg-white content-heading border-bottom">Define Module Permission for User Type <strong><?php echo $adminTypeName; ?></strong></h3>
            <div class="innerAll spacing-x2">
                <?php include basePath('admin/message.php'); ?>
                <!-- Content Start Here -->
                <form class="form-horizontal" method="POST" autocomplete="off">
                    <div class="row">
                        <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                            <a href="javascript:void(0);">
                                <div data-toggle="collapse" data-target="#widget0" class="widget-head" style="background-color: #006892;">
                                    <h4 class="heading" style="color: yellow;"><strong><i class="fa fa-plus-circle"></i>&nbsp;&nbsp; <span>General Permission</span></strong></h4>
                                    <span class="collapse-toggle"></span>
                                </div>
                            </a>

                            <div id="widget0" class="collapse" style="padding: 15px;">

                                <div class="col-md-4 pull-left">
                                    <h5><strong>Event ID-wise Permission</strong></h5>
                                    <input type="radio" name="event_access" value="all" <?php
                                    if ($event_access == "all") {
                                        echo "checked";
                                    }
                                    ?>>&nbsp;Access All Events & Their contents&nbsp;&nbsp;&nbsp;<br/>
                                    <input type="radio" name="event_access" value="selected" <?php
                                    if ($event_access == "selected") {
                                        echo "checked";
                                    }
                                    ?>>&nbsp;Access Selected Events & Their contents&nbsp;&nbsp;&nbsp;<br/>
                                    <input type="radio" name="event_access" value="created" <?php
                                    if ($event_access == "created") {
                                        echo "checked";
                                    }
                                    ?>>&nbsp;Access Only Self-created Events & Their contents&nbsp;&nbsp;&nbsp;<br/>
                                </div>

                                <div class="col-md-4 pull-left">
                                    <h5><strong>Dashboard Permission</strong></h5>
                                    <input type="checkbox" name="sales" value="yes" <?php
                                    if ($sales == "yes") {
                                        echo "checked";
                                    }
                                    ?>>&nbsp;View Sales Report&nbsp;&nbsp;&nbsp;<br/>
                                    <input type="checkbox" name="profit" value="yes" <?php
                                    if ($profit == "yes") {
                                        echo "checked";
                                    }
                                    ?>>&nbsp;View Profit Report&nbsp;&nbsp;&nbsp;<br/>
                                    <input type="checkbox" name="event" value="yes" <?php
                                    if ($event == "yes") {
                                        echo "checked";
                                    }
                                    ?>>&nbsp;View Live Events&nbsp;&nbsp;&nbsp;<br/>
                                    <input type="checkbox" name="user" value="yes" <?php
                                    if ($user == "yes") {
                                        echo "checked";
                                    }
                                    ?>>&nbsp;View Latest Users&nbsp;&nbsp;&nbsp;<br/>
                                </div>

                                <div class="col-md-4 pull-left">

                                </div>

                                <div class="clearfix"></div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div>
                            <button type="button" return="false" name="CheckAllOn" id="CheckAllOn" class="btn btn-primary"><i class="fa fa-check-circle"></i> Check All Below</button>
                            <button type="button" return="false" name="UnCheckAllOn" id="UnCheckAllOn" class="btn btn-primary"><i class="fa fa-crosshairs"></i> Uncheck All Below</button>
                        </div>
                        <br/>
                        <div class="clearfix"></div>


                        <?php if (count($arrPermissionXml) > 0): ?>
                            <?php $permissionID = 1; ?>
                            <?php foreach ($arrPermissionXml['resource'] AS $Permission): ?>
                                <div class="col-md-6 pull-left">
                                    <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                                        <a href="javascript:void(0);">
                                            <div data-toggle="collapse" data-target="#widget<?php echo $permissionID; ?>" class="widget-head">
                                                <h4 class="heading" style="color: oldlace;"><strong><i class="fa fa-plus-circle"></i>&nbsp;&nbsp;<?php echo $Permission['@attributes']['description']; ?></strong></h4>
                                                <span class="collapse-toggle"></span>
                                            </div>
                                        </a>
                                        <div id="widget<?php echo $permissionID; ?>" class="collapse" style="padding: 15px;">
                                            <ul class="list-regular">
                                                <?php foreach ($Permission['privilege'] AS $Privilage): ?>
                                                    <li>
                                                        <?php $strModuleAction = $Permission['@attributes']['module'] . "," . $Privilage['@attributes']['for']; ?>
                                                        <p><input class="check" type="checkbox" name="perm[]" value="<?php echo $Permission['@attributes']['module']; ?>,<?php echo $Privilage['@attributes']['for']; ?>" <?php
                                                            if (in_array($strModuleAction, $arrCurrentPermission)) {
                                                                echo 'checked="checked"';
                                                            }
                                                            ?>>
                                                            Permission For: <strong><?php echo $Privilage['@attributes']['description']; ?></strong></p>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>  
                                        </div>
                                    </div>
                                </div>
                                <?php $permissionID++; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <!-- Content End Here -->
                    <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-check-circle"></i> Set Permission</button>
                </form>
            </div>
        </div>

        <div class="clearfix"></div>

        <?php include basePath('admin/footer.php'); ?>
        <!-- // Footer END -->
    </div><!-- // Main Container Fluid END -->
    <script type="text/javascript">
        $(document).ready(function () {
            $("#CheckAllOn").click(function () {
                //alert("ALL");
                if (this.click) {
                    $('.check').each(function () {
                        this.checked = true;
                    });
                } else {
                    $('.check').each(function () {
                        this.checked = false;
                    });
                }
            });
        });
        $(document).ready(function () {
            $("#UnCheckAllOn").click(function () {
                //alert("UncheckALL");
                if (this.click) {
                    $('.check').each(function () {
                        this.checked = false;
                    });
                } else {
                    $('.check').each(function () {
                        this.checked = true;
                    });
                }
            });
        });
    </script>

    <?php include basePath('admin/footer_script.php'); ?>
</body>
</html>
