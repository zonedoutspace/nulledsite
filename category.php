<?php
include './config/config.php';
$catID = 0;
$catTitle = "";

if (isset($_GET['id'])) {
    $catID = validateInput($_GET['id']);
}
if (isset($_GET['title'])) {
    $catTitle = validateInput($_GET['title']);
    $catTitle = str_replace('-', ' ', $catTitle);
}
// Similar Category Name From Database
$AllCategoryForPage = array();
$getCategoryForPageSql = "SELECT category_color,category_id,category_title,category_parent_id,category_priority"
        . " FROM categories WHERE category_parent_id=0 ORDER BY category_priority DESC";
$resultGetCategoryForPage = mysqli_query($con, $getCategoryForPageSql);
if ($resultGetCategoryForPage) {
    while ($similarCategoryObj = mysqli_fetch_object($resultGetCategoryForPage)) {
        $AllCategoryForPage[] = $similarCategoryObj;
    }
} else {
    if (DEBUG) {
        $err = "resultGetCategoryForPage error: " . mysqli_error($con);
    } else {
        $err = "resultGetCategoryForPage query failed.";
    }
}

//debug($AllCategoryForPage);
// Category Related Event List From Database
$categoryEvent = array();
$categoryEventID = array();
$sqlCategoryEvent = "SELECT category_id,category_title,category_parent_id "
        . "FROM categories";
$resultCategoryEvent = mysqli_query($con, $sqlCategoryEvent);
if ($resultCategoryEvent) {
    while ($resultCategoryEventObj = mysqli_fetch_object($resultCategoryEvent)) {
        $categoryEvent[$resultCategoryEventObj->category_parent_id][$resultCategoryEventObj->category_id] = $resultCategoryEventObj;
    }
} else {
    if (DEBUG) {
        $err = "resultCategoryEvent error: " . mysqli_error($con);
    } else {
        $err = "resultCategoryEvent query failed.";
    }
}

//debug($categoryEvent);
//debug($categoryEvent);

function getAllChildArray($arrCat = array(), $parentID = 0) {
    $return = array();
    if (isset($arrCat[$parentID])) {
        foreach ($arrCat[$parentID] as $category) {
            $return[] = $category;
            $return = getGrandchildsFromTreeArray($category->category_id, $return, $arrCat);
        }
    }

    return $return;
}

function getGrandchildsFromTreeArray($parent_id, $return = array(), $arrCat = array()) {
    if (isset($arrCat[$parent_id])) {
        foreach ($arrCat[$parent_id] as $category) {
            $return[] = $category;
            $return = getGrandchildsFromTreeArray($category->category_id, $return, $arrCat);
        }
    }

    return $return;
}

$arrAllChildCatID = getAllChildArray($categoryEvent, $catID);
//debug($arrAllChildCatID);
//$strAllChildCatID = '';
//
//foreach ($arrAllChildCatID AS $key => $val) {
//    $strAllChildCatID .= $key . ',';
//}
// rtrim function uses to remove last comma from $srtAllChildCatID
//$strCatID = rtrim($strAllChildCatID, ',');
// Get Event Data Category Wise
$eventData = array();
$arrCat = array();
$getEventDataSql = "SELECT events.event_id,events.event_web_banner,events.event_category_id,events.event_title,"
        . "events.event_web_logo,events.event_is_featured,events.event_status,"
        . "event_venues.venue_id,event_venues.venue_event_id,event_venues.venue_title,"
        . "event_venues.venue_valid_from,event_venues.venue_valid_till,"
        . "event_venues.venue_start_date,event_venues.venue_end_date,event_venues.venue_status,"
        . "event_ticket_types.TT_id,event_ticket_types.TT_venue_id,event_ticket_types.TT_type_title"
        . " FROM events "
        . "LEFT JOIN event_venues ON events.event_id = event_venues.venue_event_id "
        . "LEFT JOIN event_ticket_types ON event_venues.venue_id = event_ticket_types.TT_venue_id "
        . "WHERE events.event_is_featured = 'yes' "
        . "AND events.event_status = 'active' "
        . "AND event_venues.venue_status = 'active' ";
//        . "AND ". $catID ." IN (event_category_id) ";
//foreach ($arrAllChildCatID AS $Category) {
//    $getEventDataSql .= "OR ". $Category->category_id ." IN (event_category_id) ";
//}
$getEventDataSql .= "GROUP BY venue_id "
        . "ORDER BY events.event_id DESC";

$getEventDataSql;
$resultEventData = mysqli_query($con, $getEventDataSql);
if ($resultEventData) {
    while ($resultEventDataObj = mysqli_fetch_array($resultEventData)) {
        $eventData[] = $resultEventDataObj;
        $arrCat = explode(',', $resultEventDataObj['event_category_id']);
        $eventData[(count($eventData) - 1)]['event_category_id_array'] = $arrCat;
    }
} else {
    if (DEBUG) {
        $err = "resultEventData error: " . mysqli_error($con);
    } else {
        $err = "resultEventData query failed.";
    }
}


$arrFilteredEvent = array();
if (count($arrAllChildCatID) > 0) {
    foreach ($arrAllChildCatID AS $AllCat) {
        if (count($eventData) > 0) {
            foreach ($eventData AS $Event) {
                if (in_array($AllCat->category_id, $Event['event_category_id_array'])) {
                    if (!array_key_exists($Event['event_id'], $arrFilteredEvent)) {
                        $arrFilteredEvent[$Event['event_id']] = $Event;
                    }
                }
            }
        }
    }
}
if (count($eventData) > 0) {
    foreach ($eventData AS $Event) {
        if (in_array($catID, $Event['event_category_id_array'])) {
            if (!array_key_exists($Event['event_id'], $arrFilteredEvent)) {
                $arrFilteredEvent[$Event['event_id']] = $Event;
            }
        }
    }
}

$arrVenue = array();


if (count($arrFilteredEvent) > 0) {
    foreach ($arrFilteredEvent AS $filteredEvents) {
        $arrVenue[] = $filteredEvents['venue_title'];
    }
}
//debug($arrFilteredEvent);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php include basePath('header_script.php'); ?>
    </head>
    <body class="home">
        <header>
            <div class="header-wrapper">
                <?php include basePath('menu_top.php'); ?>
                <?php include basePath('navigation.php'); ?>
            </div>
        </header>

        <div class="main-container">
            <div class="category-banner-holder">
                <div class="container">
                    <div class="row">
                        <!--                        <div class="col-md-8 col-sm-6">
                                                    <div class="category-banner-slider owl-carousel owl-theme">
                                                        <div class="category-banner-item" style="background-image: url('<?php echo baseUrl('images/category/1.jpg'); ?>');">
                                                            <div class="category-banner-item-in">
                                                                <div class="cate-eDate">
                                                                    <h3><span>Jan</span>24</h3>
                                                                </div>
                                                                <div class="cate-eTitle">
                                                                    <h3>Dhaka university development studies alumni association</h3>
                                                                </div>
                                                                <div class="btn-ebook"><a href="" class="btn btn-primary">Book Ticket <i class="fa fa-angle-right"></i> </a> </div>
                                                            </div>
                                                        </div>
                        
                                                    </div>
                                                    <div class="sliderNav"> <a class="prev"><i class="fa fa-angle-left"></i> </a> <a class="next"><i class="fa fa-angle-right"></i> </a> </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="ticket-booked">
                                                        <div class="ticket-booked-title">
                                                            <h2>Book <span>Ticket</span></h2>
                                                        </div>
                                                        <div class="ticket-booked-form">
                                                            <form>
                                                                <div class="form-group">
                                                                    <select class="form-control">
                                                                        <option>Select Category</option>
                                                                        <option></option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" placeholder="Enter your location">
                                                                </div>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" placeholder="Date of issue">
                                                                </div>
                                                                <a href="" class="btn btn-primary">Search</a>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>-->
                        <div class="col-md-8 col-sm-6">
                            <h2>Category - <?php echo $catTitle; ?></h2>
                        </div>

                    </div>
                </div>
            </div>
            <div class="container">
                <hr class="site">
                <div class="content-box">
                    <div class="content-row">
                        <div class="content-row row">
                            <div class="col-md-3 col-sm-3 left-siderbar-filter">
                                <div class="site-sitebar-hold">
                                    <div class="panel-group filter-panel-group" >
                                        <!--                                        <div class="panel panel-default">
                                                                                    <div class="panel-heading" role="tab" id="headingOne">
                                                                                        <h4 class="panel-title"> Date <a class="pull-right" data-toggle="collapse"  href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">+</a> </h4>
                                                                                    </div>
                                                                                    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                                                                        <div class="panel-body">
                                                                                            <ul class="list-unstyled bCate-list">
                                                                                                <li><a href="">Anytime</a> </li>
                                                                                                <li><a href="">This Weekend</a> </li>
                                                                                                <li><a href="">This Week</a> </li>
                                                                                                <li><a href="">This Month</a> </li>
                                                                                            </ul>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>-->
                                        <!-- Category From Database -->
                                        <div class="panel panel-default">
                                            <div class="panel-heading" role="tab" id="heading_02">
                                                <h4 class="panel-title"> Category <a class="pull-right" data-toggle="collapse"  href="#collapse_02" aria-expanded="true" aria-controls="collapse_o2">+</a> </h4>
                                            </div>
                                            <div id="collapse_02" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading_02">
                                                <div class="panel-body">
                                                    <ul class="list-unstyled bCate-list">
                                                        <?php if (count($arrAllChildCatID) > 0): ?>
                                                            <?php foreach ($arrAllChildCatID as $simCat): ?>
                                                                <li><a href=""><i class="fa fa-share"></i>&nbsp;<?php echo $simCat->category_title; ?></a> </li>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Category From Database -->
                                        <!--                                        <div class="panel panel-default">
                                                                                    <div class="panel-heading" role="tab" id="heading_03">
                                                                                        <h4 class="panel-title"> Location <a class="pull-right" data-toggle="collapse"  href="#collapse_03" aria-expanded="true" aria-controls="collapse_03">+</a> </h4>
                                                                                    </div>
                                                                                    <div id="collapse_03" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading_03">
                                                                                        <div class="panel-body">
                                                                                            <div class="checkbox">
                                                                                                <label>
                                                                                                    <input type="checkbox">
                                                                                                    Dhaka </label>
                                                                                            </div>
                                                                                            <div class="checkbox">
                                                                                                <label>
                                                                                                    <input type="checkbox">
                                                                                                    Khulna </label>
                                                                                            </div>
                                                                                            <div class="checkbox">
                                                                                                <label>
                                                                                                    <input type="checkbox">
                                                                                                    Barishal </label>
                                                                                            </div>
                                                                                            <div class="checkbox">
                                                                                                <label>
                                                                                                    <input type="checkbox">
                                                                                                    Shylet </label>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>-->
                                        <div class="panel panel-default">
                                            <div class="panel-heading" role="tab" id="heading_04">
                                                <h4 class="panel-title"> Venues <a class="pull-right" data-toggle="collapse"  href="#collapse_04" aria-expanded="true" aria-controls="collapse_04">+</a> </h4>
                                            </div>
                                            <div id="collapse_04" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading_04">
                                                <div class="panel-body">
                                                    <?php if (count($arrVenue) > 0): ?>
                                                        <?php foreach ($arrVenue AS $key => $val): ?>
                                                            <div class="checkbox">
                                                                <label>
                                                                    <!--<input type="checkbox">-->
                                                                    <?php echo $val; ?> </label>
                                                            </div>
                                                        <?php endforeach; ?>

                                                    <?php else: ?>
                                                        <h5>No venue found.</h5>
                                                    <?php endif; ?>

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="category-list">
                                    <div class="tab-box category-panel"> 
                                        <ul class="nav nav-tabs list-tabs" role="tablist">
                                            <li class=""><a> Events List </a></li>
                                        </ul>
                                    </div>
                                    <!-- Event Data Div Start -->
                                    <div class="item-list wow fadeInDown" data-wow-duration="1s" data-wow-delay="0.15s">
                                        <?php if (count($arrFilteredEvent) > 0): ?>
                                            <?php foreach ($arrFilteredEvent AS $EventData) : ?>
                                                <div class="item-list">
                                                    <div class="col-sm-2 no-padding photobox">
                                                        <div class="item-list-image"> <a href="<?php echo baseUrl(); ?>details/<?php echo $EventData['event_id']; ?>/<?php echo clean($EventData['event_title']); ?>"><img class="thumbnail no-margin" src="<?php echo $config['IMAGE_UPLOAD_URL'] . '/event_web_logo/' . $EventData['event_web_logo']; ?>" alt="<?php echo $EventData['event_title']; ?>"></a> </div>
                                                    </div>
                                                    <div class="col-sm-7 eventlist-desc-box">
                                                        <div class="eventlist-details">
                                                            <h4 class="add-title"> <a href="<?php echo baseUrl(); ?>details/<?php echo $EventData['event_id']; ?>/<?php echo clean($EventData['event_title']); ?>"><?php echo $EventData['event_title']; ?></a> </h4>
                                                            <span class="info-row">
                                                                <span class="date">
                                                                    <i class=" icon-clock"> </i>
                                                                    <?php
                                                                    $dayName = $EventData['venue_start_date'];
                                                                    echo $day = date("l, d F, Y", strtotime($dayName));
                                                                    ?>&nbsp;&nbsp;
                                                                </span>
                                                                <span class="item-location"><i class="fa fa-map-marker"></i> <?php echo $EventData['venue_title']; ?> </span>
                                                            </span> 
                                                            <a class="mini-link" href="<?php echo baseUrl(); ?>details/<?php echo $EventData['event_id']; ?>/<?php echo clean($EventData['event_title']); ?>">More info <i class="fa fa-caret-right"></i>
                                                            </a> 
                                                        </div>
                                                    </div>                                      
                                                    <div class="col-sm-3 text-right  price-box"> 
                                                        <a class="btn btn-primary  btn-sm" href="<?php echo baseUrl(); ?>details/<?php echo $EventData['event_id']; ?>/<?php echo clean($EventData['event_title']); ?>"> BUY TICKET </a>
                                                        <?php if (in_array($EventData['event_id'] . '-' . 'event', $arrWishlist)): ?>
                                                            <a id="wishlist_<?php echo $EventData['event_id']; ?>" class="btn btn-default  btn-sm make-favorite added" title="Added to Wishlist"><i class="fa fa-heart"></i></a>
                                                        <?php else: ?>
                                                            <a id="wishlist_<?php echo $EventData['event_id']; ?>" onclick="javascript:addToWishlist(<?php echo $EventData['event_id']; ?>, 'event');" class="btn btn-default  btn-sm make-favorite" title="Add to Wishlist"><i class="fa fa-heart"></i></a>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <h3 class="text-center" style="padding-top: 10px;">No recent category found in the record.</h3>
                                        <?php endif; ?>
                                    </div>

                                </div>
                            </div>
                            <!--left-siderbar-->

                            <div class="col-md-3 col-sm-3 right-siderbar">
                                <div class="site-sidebar">
                                    <div class="sidebar-title">
                                        <h4>Browse Events By Main Category </h4>
                                    </div>
                                    <div class="sidebar-body">
                                        <ul class="list-unstyled bCate-list">
                                            <?php if (count($AllCategoryForPage) > 0): ?>
                                                <?php foreach ($AllCategoryForPage AS $RootCategory): ?>
                                                    <li><a href="<?php echo baseUrl(); ?>category/<?php echo $RootCategory->category_id; ?>/<?php echo clean($RootCategory->category_title); ?>"><?php echo $RootCategory->category_title; ?></a> </li>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <h5>No main category found.</h5>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                    <!--<div class="sidebar-footer text-right"> <a href="">See all</a> </div>-->
                                </div>

                            </div>
                            <!--right-siderbar--> 
                        </div>
                        <!--/.featured-row--> 
                    </div>
                    <!--/.content-row--> 
                </div>
                <!--/.content-box--> 
            </div>
        </div>

        <?php include basePath('social_link.php'); ?>
        <?php include basePath('footer.php'); ?>
        <?php include basePath('login_modal.php'); ?>
        <?php include basePath('signup_modal.php'); ?>
        <?php include basePath('footer_script.php'); ?>

       
    </body>
</html>