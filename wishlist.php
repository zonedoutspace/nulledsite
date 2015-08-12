<?php
include './config/config.php';
if (!checkUserLogin()) {
    redirect('index.php');
}

$wlistUserID = 0;
$arrFavorite = array();
if (checkUserLogin()) {
    $wlistUserID = getSession('user_id');
    $sqlGetFavorite = "SELECT WL_id,event_id,event_title,event_web_logo,venue_start_date,venue_title,event_status, "
            . "(SELECT MIN(TT_current_price) FROM event_ticket_types WHERE TT_venue_id=venue_event_id) AS MinimumPrice, "
            . "(SELECT MAX(TT_current_price) FROM event_ticket_types WHERE TT_venue_id=venue_event_id) AS MaximumPrice "
            . "FROM wishlists "
            . "LEFT JOIN events ON event_id=WL_product_id "
            . "LEFT JOIN event_venues ON venue_event_id=event_id "
//            . "LEFT JOIN event_ticket_types ON TT_venue_id=venue_event_id "
            . "WHERE WL_user_id=$wlistUserID ";
    $resultGetFavorite = mysqli_query($con, $sqlGetFavorite);

    if ($resultGetFavorite) {
        while ($resultGetFavoriteObj = mysqli_fetch_object($resultGetFavorite)) {
            $arrFavorite[] = $resultGetFavoriteObj;
        }
    } else {
        if (DEBUG) {
            echo "resultGetFavorite error: " . mysqli_error($con);
        } else {
            echo "resultGetFavorite query failed.";
        }
    }
}

//debug($arrFavorite);
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
                        <li><a href="<?php echo baseUrl(); ?>address"><i class="fa fa-map-marker"></i> Default Address</a> </li>
                        <li class="active"><a href="<?php echo baseUrl(); ?>mywishlist"><i class="fa fa-heart"></i> My Wishlist</a> </li>
                        <li><a href="<?php echo baseUrl(); ?>myorderlist"><i class="fa fa-heart"></i> Order History</a> </li>
                    </ul>



                    <div class="inner-box">
                        <h2 class="title-2"><i class="icon-heart-1"></i> Favourite Events </h2>
                        <div class="table-responsive">
                            <div class="table-action">

                            </div>
                            <table id="addManageTable" class="table table-striped table-bordered add-manage-table table demo" data-filter="#filter" data-filter-text-only="true" >
                                <thead>
                                    <tr>
                                        <th> Photo </th>
                                        <th data-sort-ignore="true"> Events Details </th>
                                        <th data-type="numeric" > Price </th>
                                        <th > Status </th>
                                        <th> Option </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($arrFavorite) > 0): ?>
                                        <?php foreach ($arrFavorite AS $FavEvents): ?>
                                            <tr class="wishlist_row_<?php echo $FavEvents->WL_id; ?>">
                                                <td style="width:14%" class="add-img-td">
                                                    <a href="<?php echo baseUrl(); ?>details/<?php echo $FavEvents->event_id; ?>/<?php echo clean($FavEvents->event_title); ?>">
                                                        <img style="width: 80%;height: 80px;" class="thumbnail  img-responsive" src="<?php echo baseUrl(); ?>upload/event_web_logo/<?php echo $FavEvents->event_web_logo; ?>" alt="<?php echo $FavEvents->event_title; ?>">
                                                    </a>
                                                </td>
                                                <td style="width:58%" class="ads-details-td">
                                                    <div class="eventlist-details">
                                                        <h4 class="add-title"> 
                                                            <a href="<?php echo baseUrl(); ?>details/<?php echo $FavEvents->event_id; ?>/<?php echo clean($FavEvents->event_title); ?>"> <?php echo $FavEvents->event_title; ?> </a> 
                                                        </h4>
                                                        <span class="info-row"> 
                                                            <span class="date"><i class=" icon-clock"> </i> <?php echo date("d M, Y", strtotime($FavEvents->venue_start_date)); ?> </span> - <span class="item-location"><i class="fa fa-map-marker"></i> <?php echo $FavEvents->venue_title; ?> </span> 
                                                        </span> <a href="<?php echo baseUrl(); ?>details/<?php echo $FavEvents->event_id; ?>/<?php echo clean($FavEvents->event_title); ?>" class="mini-link">More info <i class="fa fa-caret-right"></i> </a> 
                                                    </div>
                                                </td>
                                                <td style="width:16%" class="price-td">
                                                    <div>
                                                        <?php if ($FavEvents->MinimumPrice == $FavEvents->MaximumPrice): ?>
                                                            <strong> <?php echo $config['CURRENCY_SIGN']; ?> <?php echo $FavEvents->MinimumPrice; ?></strong>
                                                        <?php else: ?>
                                                            <strong> <?php echo $config['CURRENCY_SIGN']; ?> <?php echo $FavEvents->MinimumPrice; ?> - <?php echo $config['CURRENCY_SIGN']; ?> <?php echo $FavEvents->MaximumPrice; ?></strong>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td style="width:16%" class="price-td">
                                                    <div>
                                                        <?php if ($FavEvents->event_status == 'active'): ?>
                                                            <span class="label label-success">
                                                                Active
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="label label-danger">
                                                                Inactive
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td style="width:10%" class="action-td"><div>
                                                        <p> <a href="<?php echo baseUrl(); ?>details/<?php echo $FavEvents->event_id; ?>/<?php echo clean($FavEvents->event_title); ?>" class="btn btn-info btn-xs"> <i class="fa fa-mail-forward"></i> Details </a></p>
                                                        <p> <a onclick="javascript:removeWishlist(<?php echo $FavEvents->WL_id; ?>);" class="btn btn-danger btn-xs"> <i class=" fa fa-trash"></i> Delete </a></p>
                                                    </div></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <!--/.row-box End--> 
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
