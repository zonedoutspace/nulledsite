<?php
include './config/config.php';

$UA_first_name = "";
$UA_last_name = "";
$UA_phone = "";
$UA_title = "";
$UA_address = "";
$UA_zip = "";
$UA_country_id = "";
$UA_city_id = "";
$UA_user_id = "";
$defaultShipping = 0;
$defaultBilling = 0;

if (!checkUserLogin()) {
    redirect('index.php');
} else {
    $userID = getSession('user_id');
    $user_email = getSession('user_email');
}
$userAddress = array();
$userAddressSql = "SELECT user_addresses.*, countries.country_name, cities.city_name FROM user_addresses LEFT JOIN countries ON user_addresses.UA_country_id = countries.country_id LEFT JOIN cities ON user_addresses.UA_city_id = cities.city_id WHERE UA_user_id = $userID ORDER BY user_addresses.UA_id DESC";
$userAddressResult = mysqli_query($con, $userAddressSql);
$countUserAddress = mysqli_num_rows($userAddressResult);
if ($countUserAddress > 0) {
    while ($rowAddress = mysqli_fetch_object($userAddressResult)) {
        $userAddress[] = $rowAddress;
    }
}
//debug($userAddress);
// Get All Countries For User Address

$countryArray = array();
$countrySql = "SELECT * FROM countries WHERE country_status = 'allow'";
$resultCountry = mysqli_query($con, $countrySql);
if ($resultCountry) {
    while ($rowCountry = mysqli_fetch_object($resultCountry)) {
        $countryArray[] = $rowCountry;
    }
} else {
    if (DEBUG) {
        $err = "resultCountry error: " . mysqli_error($con);
    } else {
        $err = "resultCountry query failed";
    }
}
//debug($countryArray);
// Get All Cities For User Address
$cityArray = array();
$citySql = "SELECT * FROM cities WHERE city_status = 'allow'";
$resultCity = mysqli_query($con, $citySql);
if ($resultCity) {
    while ($rowCity = mysqli_fetch_object($resultCity)) {
        $cityArray[] = $rowCity;
    }
} else {
    if (DEBUG) {
        $err = "resultCity error: " . mysqli_error($con);
    } else {
        $err = "resultCity query failed";
    }
}



//get default address from user table
$sqlDefaultAdd = "SELECT user_default_shipping,user_default_billing FROM users WHERE user_id=$userID";
$resultDefaultAdd = mysqli_query($con, $sqlDefaultAdd);
if ($resultDefaultAdd) {
    $resultDefaultAddObj = mysqli_fetch_object($resultDefaultAdd);
    if (isset($resultDefaultAddObj->user_default_shipping)) {
        $defaultShipping = $resultDefaultAddObj->user_default_shipping;
        $defaultBilling = $resultDefaultAddObj->user_default_billing;
    }
} else {
    if (DEBUG) {
        $err = "getDefaultAdd error: " . mysqli_error($con);
    } else {
        $err = "getDefaultAdd query failed";
    }
}

//debug($cityArray);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php include basePath('header_script.php'); ?>
    </head>
    <body>
        <div id="wrapper">
            <header>
                <div class="header-wrapper">
                    <?php include basePath('menu_top.php'); ?>
                    <?php include basePath('navigation.php'); ?>
                </div>
            </header>
            <!-- /.header -->
            <div class="main-container">
                <div class="container">
                    <ul class="nav nav-pills nav-justified  nav-tab-bar">
                        <li><a href="<?php echo baseUrl(); ?>account"><i class="fa fa-dashboard"></i> User Settings</a> </li>
                        <li class="active"><a href="<?php echo baseUrl(); ?>address"><i class="fa fa-map-marker"></i> Default Address</a> </li>
                        <li><a href="<?php echo baseUrl(); ?>mywishlist"><i class="fa fa-heart"></i> My Wishlist</a> </li>
                        <li><a href="<?php echo baseUrl(); ?>myorderlist"><i class="icon-doc-text"></i> Order History</a> </li>
                    </ul>
                    <div class="nav-tab-content address-content">
                        <!-- User Address Div Start -->
                        <?php if (count($userAddress) > 0): ?>
                            <?php foreach ($userAddress as $address): ?>
                                <div class="addAddress">
                                    <div class="col-md-6 col-sm-6 col-xs-6 text-left">
                                        <h4 class="clearfix"><?php echo $address->UA_title; ?></h4>
                                        <address style="margin-top: 20px;">
                                            <?php echo $address->UA_address; ?><br>
                                            <?php echo $address->city_name; ?>-<?php echo $address->UA_zip; ?>, <?php echo $address->country_name; ?>.<br>
                                            phone: <?php echo $address->UA_phone; ?>
                                        </address>
                                        <div>
                                            <div style="margin-bottom:10px;margin-left: 0px;">
                                                <a href="<?php echo baseUrl(); ?>editaddress/<?php echo $address->UA_id; ?>" class="btn btn-warning btn-xs">
                                                    <i class="fa fa-edit"></i> Edit 
                                                </a> 
                                                <a href="javascript:void(0);" onclick="javascript:deleteUserAddress(<?php echo $address->UA_id; ?>);" class="btn btn-danger btn-xs"><i class="fa fa-remove"></i> Delete </a>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6 text-right" style="padding-top: 40px !important;">
                                        <div class="radio-hold">
                                            <label class="radio-inline">
                                                <input <?php
                                                if ($defaultShipping == $address->UA_id) {
                                                    echo "checked";
                                                }
                                                ?> class="ship" type="radio" name="shipping" value="<?php echo $address->UA_id; ?>"> Default Delivery
                                            </label>
                                            <label class="radio-inline">
                                                <input <?php
                                                if ($defaultBilling == $address->UA_id) {
                                                    echo "checked";
                                                }
                                                ?> class="bill" type="radio" name="billing" value="<?php echo $address->UA_id; ?>"> Default Billing
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <hr style=" height: 12px; border: 0; box-shadow: inset 0 12px 12px -12px rgba(0,0,0,0.5);" />
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <div class="clearfix"></div>
                        <!-- User Address Div End -->

                        <!-- Add New Address Div Start -->
                        <div class="addAddress-btn-holder">
                            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#addAddress" aria-expanded="false" aria-controls="addAddress"> <i class="fa fa-plus"></i> Add New Address </button>
                        </div>
                        <div class="collapse" id="addAddress">
                            <div class="add-address-in">
                                <div class="add-address-head">
                                    <h4><i class="fa fa-plus"></i> Add New Address</h4>
                                </div>
                                <div class="add-address-body">
                                    <form class="form-horizontal">
                                        <input type="hidden" id="UA_user_id" name="UA_user_id" value="<?php echo $userID; ?>"/>
                                        <div class="form-group">
                                            <div class="col-sm-2">
                                                <label for="">Address</label>
                                            </div>
                                            <div class="col-sm-10">
                                                <textarea rows="3" class="form-control" id="UA_address" name="UA_address"><?php echo $UA_address; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-2">
                                                <label for="">Zip/Postal Code</label>
                                            </div>
                                            <div class="col-sm-10">
                                                <input type="text" id="UA_zip" name="UA_zip" value="<?php echo $UA_zip; ?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-2">
                                                <label for="">City</label>
                                            </div>

                                            <div class="col-sm-10">
                                                <select id="UA_city_id" class="form-control" name="UA_city_id">
                                                    <option value="0">Select City</option>
                                                    <?php if (count($cityArray) >= 1): ?>
                                                        <?php foreach ($cityArray as $city): ?>
                                                            <option 
                                                                value="<?php echo $city->city_id; ?>"
                                                                <?php
                                                                if ($city->city_id == $UA_city_id) {
                                                                    echo ' selected="selected"';
                                                                }
                                                                ?>>
                                                                    <?php echo $city->city_name; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-2">
                                                <label for="">Country</label>
                                            </div>

                                            <div class="col-sm-10">
                                                <select id="UA_country_id" class="form-control" name="UA_country_id">
                                                    <option value="0">Select Country</option>
                                                    <?php if (count($countryArray) >= 1): ?>
                                                        <?php foreach ($countryArray as $country): ?>
                                                            <option 
                                                                value="<?php echo $country->country_id; ?>"
                                                                <?php
                                                                if ($country->country_id == $UA_country_id) {
                                                                    echo ' selected="selected"';
                                                                }
                                                                ?>>
                                                                    <?php echo $country->country_name; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-2">
                                                <label for="">Phone</label>
                                            </div>
                                            <div class="col-sm-10">
                                                <input type="text" id="UA_phone" name="UA_phone" value="<?php echo $UA_phone; ?>" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-10 col-sm-offset-2">
                                                <button type="submit" onclick="return false;" id="addNewUserAddress" class="btn btn-primary"><i class="fa fa-plus"></i> Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Add New Address Div End -->
                    </div>
                </div>
            </div>
            <?php include basePath('social_link.php'); ?>
            <?php include basePath('footer.php'); ?>
            <?php include basePath('footer_script.php'); ?>
            
            <!-- From Customer Dashboard -->
        <script src="<?php echo baseUrl('js/jquery.matchHeight-min.js'); ?>"></script>
        <script src="<?php echo baseUrl('js/hideMaxListItem.js'); ?>"></script>

        <script src="<?php echo baseUrl('js/footable.js?v=2-0-1'); ?>" type="text/javascript"></script> 
        <script src="<?php echo baseUrl('js/footable.filter.js?v=2-0-1'); ?>" type="text/javascript"></script>
        <script type="text/javascript">
                    $(function () {
                        $('#addManageTable').footable().bind('footable_filtering', function (e) {
                            var selected = $('.filter-status').find(':selected').text();
                            if (selected && selected.length > 0) {
                                e.filter += (e.filter && e.filter.length > 0) ? ' ' + selected : selected;
                                e.clear = !e.filter;
                            }
                        });

                        $('.clear-filter').click(function (e) {
                            e.preventDefault();
                            $('.filter-status').val('');
                            $('table.demo').trigger('footable_clear_filter');
                        });

                    });
        </script> 
        <script>
            function checkAll(bx) {
                var chkinput = document.getElementsByTagName('input');
                for (var i = 0; i < chkinput.length; i++) {
                    if (chkinput[i].type == 'checkbox') {
                        chkinput[i].checked = bx.checked;
                    }
                }
            }
        </script> 
        <script src="<?php echo baseUrl('js/plugins/jquery.fs.scroller/jquery.fs.scroller.js'); ?>"></script>
        <script src="<?php echo baseUrl('js/plugins/jquery.fs.selecter/jquery.fs.selecter.js'); ?>"></script>

    </body>
</html>
