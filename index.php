<?php
include "config/config.php";

$strFeaturedID = '';
$strUpcomingID = '';
//Banner Start
$arrayBanner = array();
$sqlBanner = "SELECT banner_id,banner_title,banner_image,banner_link,banner_details,banner_link_type"
        . " FROM banner ORDER BY banner_priority DESC";
$resultBanner = mysqli_query($con, $sqlBanner);
if ($resultBanner) {
    while ($resultBannerObj = mysqli_fetch_object($resultBanner)) {
        $arrayBanner[] = $resultBannerObj;
    }
} else {
    if (DEBUG) {
        $err = "resultBanner error: " . mysqli_error($con);
    } else {
        $err = "resultBanner query failed.";
    }
}
// Banner End
// Featured Event Start
$arrayFeatured = array();
$arrayFeaturedID = array();
$sqlFeatured = "SELECT events.event_id,events.event_title,events.event_category_id,"
        . "events.event_web_logo,events.event_is_featured,events.event_status "
//        . "categories.category_id,categories.category_title "
        . "FROM events "
//        . "LEFT JOIN categories ON category_id=event_category_id "
        . "WHERE events.event_is_featured='yes' "
        . "AND events.event_status='active' "
        . "ORDER BY events.event_featured_priority DESC ";
$resultFeatured = mysqli_query($con, $sqlFeatured);
if ($resultFeatured) {
    while ($resultFeaturedObj = mysqli_fetch_object($resultFeatured)) {
        $arrayFeatured[] = $resultFeaturedObj;
        $arrayFeaturedID[] = $resultFeaturedObj->event_id;
    }
} else {
    if (DEBUG) {
        $err = "resultFeatured error: " . mysqli_error($con);
    } else {
        $err = "resultFeatured query failed.";
    }
}

//converting array of featured event's id into string
if (count($arrayFeaturedID) > 0) {
    $strFeaturedID = implode(',', $arrayFeaturedID);
}



//getting featured event's venue from database
$arrFeatVenue = array();
if ($strFeaturedID != '') {
    $sqlGetFeatVenue = "SELECT * FROM event_venues WHERE venue_event_id IN ($strFeaturedID) AND venue_status='active'";
    $resultGetFeatVenue = mysqli_query($con, $sqlGetFeatVenue);
    if ($resultGetFeatVenue) {
        while ($resultGetFeatVenueObj = mysqli_fetch_object($resultGetFeatVenue)) {
            $arrFeatVenue[$resultGetFeatVenueObj->venue_event_id]['venue_start_date'][] = $resultGetFeatVenueObj->venue_start_date;
            $arrFeatVenue[$resultGetFeatVenueObj->venue_event_id]['venue_end_date'][] = $resultGetFeatVenueObj->venue_end_date;
        }
    } else {
        if (DEBUG) {
            $err = "resultGetFeatVenue error: " . mysqli_error($con);
        } else {
            $err = "resultGetFeatVenue query failed.";
        }
    }
}
//debug($resultGetFeatVenue);
//Getting upcoming event data from database

$arrayUpcoming = array();
$arrayUpcomingID = array();
$sqlUpcoming = "SELECT events.event_id,events.event_title,events.event_category_id,"
        . "events.event_web_logo,events.event_is_coming,events.event_status "
        . "FROM events "
        . "WHERE events.event_is_coming='yes' "
        . "AND events.event_status='active' "
        . "ORDER BY events.event_coming_priority DESC ";
$resultUpcoming = mysqli_query($con, $sqlUpcoming);
if ($resultUpcoming) {
    while ($resultUpcomingObj = mysqli_fetch_object($resultUpcoming)) {
        $arrayUpcoming[] = $resultUpcomingObj;
        $arrayUpcomingID[] = $resultUpcomingObj->event_id;
    }
} else {
    if (DEBUG) {
        $err = "resultUpcoming error: " . mysqli_error($con);
    } else {
        $err = "resultUpcoming query failed.";
    }
}


//Getting upcoming event data from database
//converting array of featured event's id into string
if (count($arrayUpcomingID) > 0) {
    $strUpcomingID = implode(',', $arrayUpcomingID);
}

//getting upcoming event's venue from database
$arrUpcomingVenue = array();
if ($strUpcomingID != '') {
    $sqlGetUpcomingVenue = "SELECT * FROM event_venues WHERE venue_event_id IN ($strUpcomingID) AND venue_status='active'";
    $resultGetUpcomingVenue = mysqli_query($con, $sqlGetUpcomingVenue);
    if ($resultGetUpcomingVenue) {
        while ($resultGetUpcomingVenueObj = mysqli_fetch_object($resultGetUpcomingVenue)) {
            $arrUpcomingVenue[$resultGetUpcomingVenueObj->venue_event_id]['venue_start_date'][] = $resultGetUpcomingVenueObj->venue_start_date;
            $arrUpcomingVenue[$resultGetUpcomingVenueObj->venue_event_id]['venue_end_date'][] = $resultGetUpcomingVenueObj->venue_end_date;
        }
    } else {
        if (DEBUG) {
            $err = "resultGetUpcomingVenue error: " . mysqli_error($con);
        } else {
            $err = "resultGetUpcomingVenue query failed.";
        }
    }
}
//debug($resultGetUpcomingVenue);
//Getting root category from database
$arrRootCat = array();
$arrRootCatID = array();
$strRootCatID = '';
$sqlRootCat = "SELECT category_color,category_id,category_title,category_parent_id,category_priority"
        . " FROM categories WHERE category_parent_id=0 ORDER BY category_priority DESC";
$resultRootCat = mysqli_query($con, $sqlRootCat);
if ($resultRootCat) {
    while ($resultRootCatObj = mysqli_fetch_object($resultRootCat)) {
        $arrRootCat[] = $resultRootCatObj;
        $arrRootCatID[] = $resultRootCatObj->category_id;
    }
} else {
    if (DEBUG) {
        $err = "resultRootCat error: " . mysqli_error($con);
    } else {
        $err = "resultRootCat query failed.";
    }
}
//debug($arrRootCat);
//converting array of featured event's id into string
if (count($arrRootCatID) > 0) {
    $strRootCatID = implode(',', $arrRootCatID);
}

// Latest Event 
$latestEventArray = array();
$arrCat = array();
$sqlLatestEvent = "SELECT events.event_id,events.event_category_id,events.event_title,"
        . "events.event_web_logo,events.event_is_featured,events.event_status,"
        . "event_venues.venue_id,event_venues.venue_event_id,event_venues.venue_title,"
        . "event_venues.venue_valid_from,event_venues.venue_valid_till,"
        . "event_venues.venue_start_date,event_venues.venue_end_date,event_venues.venue_status,"
        . "event_ticket_types.TT_id,event_ticket_types.TT_venue_id,event_ticket_types.TT_type_title"
        . " FROM events "
        . "LEFT JOIN event_venues ON events.event_id = event_venues.venue_event_id "
        . "LEFT JOIN event_ticket_types ON event_venues.venue_id = event_ticket_types.TT_venue_id "
//        . "WHERE events.event_is_featured = 'yes' "
        . "WHERE events.event_status = 'active' "
        . "AND event_venues.venue_status = 'active' "
        . "AND events.event_category_id IN ($strRootCatID) "
        . "GROUP BY event_id "
        . "ORDER BY event_venues.venue_start_date ASC";
$resultLatestEvent = mysqli_query($con, $sqlLatestEvent);
if ($resultLatestEvent) {
    while ($resultLatestEventObj = mysqli_fetch_array($resultLatestEvent)) {
        $latestEventArray[] = $resultLatestEventObj;
        $arrCat = explode(',', $resultLatestEventObj['event_category_id']);
        $latestEventArray[(count($latestEventArray) - 1)]['event_category_id_array'] = $arrCat;
    }
} else {
    if (DEBUG) {
        $err = "resultLatestEvent error: " . mysqli_error($con);
    } else {
        $err = "resultLatestEvent query failed.";
    }
}
//debug($latestEventArray);
//getting announcements from database
$arrAnnounce = array();
$sqlGetAnnounce = "SELECT * FROM announcements WHERE announcement_status='active' ORDER BY announcement_id ASC";
$resultGetAnnounce = mysqli_query($con, $sqlGetAnnounce);
if ($resultGetAnnounce) {
    while ($resultGetAnnounceObj = mysqli_fetch_object($resultGetAnnounce)) {
        $arrAnnounce[] = $resultGetAnnounceObj;
    }
} else {
    if (DEBUG) {
        $err = "resultGetAnnounce error: " . mysqli_error($con);
    } else {
        $err = "resultGetAnnounce query failed.";
    }
}

//debug($arrAnnounce);
// Getting Offers and Promotion
$onEventIDArray = array();
$offerPromotionArray = array();
$sqlOfferPromotion = "SELECT event_special_offer.* "
        . " FROM event_special_offer"
        . " WHERE SO_status='active'";
$resultOfferPromotion = mysqli_query($con, $sqlOfferPromotion);
if ($resultOfferPromotion) {
    while ($OfferPromotionObj = mysqli_fetch_object($resultOfferPromotion)) {
        $offerPromotionArray[] = $OfferPromotionObj;
        if ($OfferPromotionObj->SO_on_event_id > 0) {
            $onEventIDArray[] = $OfferPromotionObj->SO_on_event_id;
        }
    }
} else {
    if (DEBUG) {
        $err = "resultOfferPromotion error: " . mysqli_error($con);
    } else {
        $err = "resultOfferPromotion query failed.";
    }
}

// Getting Offers and Promotion
//debug($onEventIDArray);
//debug($offerPromotionArray);
$offerEventID = "";
if (count($onEventIDArray) > 0) {
    $offerEventID = implode(',', $onEventIDArray);
}
$eventDataArray = array();
$sqlEventData = "SELECT event_id,event_title FROM events WHERE event_id IN ($offerEventID)";
$resultEventData = mysqli_query($con, $sqlEventData);
if ($resultEventData) {
    while ($EventDataObj = mysqli_fetch_object($resultEventData)) {
        $eventDataArray[$EventDataObj->event_id] = $EventDataObj;
    }
} else {
    if (DEBUG) {
        $err = "resultEventData error: " . mysqli_error($con);
    } else {
        $err = "resultEventData query failed.";
    }
}
//debug($eventDataArray);
// Getting archived events from databse
$arrayArchived = array();
$sqlArchived = "SELECT event_id,event_title,event_web_logo FROM events WHERE event_status='archived' ORDER BY event_created_on DESC";
$resultArchived = mysqli_query($con, $sqlArchived);
if ($resultArchived) {
    while ($resultArchivedObj = mysqli_fetch_object($resultArchived)) {
        $arrayArchived[] = $resultArchivedObj;
    }
} else {
    if (DEBUG) {
        $err = "resultArchived error: " . mysqli_error($con);
    } else {
        $err = "resultArchived query failed.";
    }
}
//debug($arrayArchived);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="<?php echo getConfig("HOMEPAGE_META_DESCRIPTION"); ?>" >
        <meta name="keywords" content="<?php echo getConfig("HOMEPAGE_META_KEYWORD"); ?>" >

        <?php include basePath('header_script.php'); ?>

        <script>
            var mobUrl = "";
            if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                mobUrl = baseUrl + "m.home";
                window.location = mobUrl;
            }
        </script>

    </head>

    <body class="home">

        <!--        <div id="loader" style="margin: 0 auto; width: 100%; height: auto; position: fixed; left: 43%; top: 40%;">
                    <img src="<?php echo baseUrl(); ?>images/loading.gif"><br/>
                    <img style="margin: 0 auto; position: fixed; left: 48%;" src="<?php echo baseUrl(); ?>images/Loading-Text.gif">
                </div>-->

        <div id="content">
            <header>
                <div class="header-wrapper">
                    <?php include basePath('menu_top.php'); ?>
                    <?php include basePath('navigation.php'); ?>
                </div>
            </header>




            <div class="container">


                <div class="banner">
                    <!-- Main component call to action -->
                    <div class="row">
                        <div class="image-show-case-wrapper center-block relative ">
                            <div id="imageShowCase" class="owl-carousel owl-theme">


                                <?php if (count($arrayBanner) > 0): ?>
                                    <?php foreach ($arrayBanner AS $Banner): ?>

                                        <div class="product-slide">
                                            <a href="#"><img class="img-responsive" src="<?php echo $config['IMAGE_UPLOAD_URL'] . '/banner_image/' . $Banner->banner_image; ?>" alt="<?php echo $Banner->banner_title; ?>"></a>
                                        </div>

                                        <!-- /.product-slide  -->
                                    <?php endforeach; ?>
                                <?php endif; ?>

                            </div>

                            <div style="clear:both;"></div>

                            <a id="ps-next" class="ps-nav"> <img src="images/site/arrow-right.png" alt="N E X T"> </a> 
                            <a id="ps-prev" class="ps-nav"> <img src="images/site/arrow-left.png" alt="P R E V"></a>

                        </div>
                        <!--/.image-show-case-wrapper -->


                        <div class="search-box col-xs-12">
                            <div class="search-box-inner">
                                <div class="container">
                                    <h3 class="pull-left col-md-3 col-sm-4 hidden-xs">Looking to buy tickets?</h3>
                                    <div class="search-field   col-md-8 col-sm-8 col-xs-12">
                                        <form class="form-inline" action="<?php echo baseUrl(); ?>search.php" method="GET">
                                            <div class="form-group col-lg-6 col-xs-6">
                                                <input type="text" class="form-control" name="key" required="true" placeholder="Search..." >
                                            </div>
                                            <button type="submit" class="btn btn-success col-sm-3 col-xs-4"> <i class="icon-search"></i> Search events</button>
                                            <!--                                <button type="button" class="btn btn-blank col-sm-3  col-xs-2 " data-toggle="collapse" data-target="#advSearch" aria-expanded="false" aria-controls="advSearch">
                                                                                <span class="hidden-xs">Advance Saerch</span> <i class="icon-menu-1"></i>
                                                                            </button>-->
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/.search-box--> 

                    </div>


                    <!--/.search-box--> 
                </div>
                <!--/.banner-->

            </div>



        </div>
        <!-- Banner Div End -->

        <div class="collapse" id="advSearch">
            <div class="search-inner">
                <div class="container">
                    <div class="adv-search-container">
                        <form class="form-horizontal form-advsearch">
                            <div class="form-group">
                                <div class="col-sm-12"><input type="text" class="form-control" placeholder="Keyword"></div>
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <div class="col-sm-3">
                                    <select name="date_range" class="form-control">
                                        <option value="Select Time Range" selected="selected">Select Time Range..</option>

                                        <option value="CURRYR">Current Calendar Year</option>

                                        <option value="DIVIDER" disabled="">---------------------------</option>
                                        <option value="CURRMO">Current Month</option>

                                        <option value="NXTMO">Next Month</option>

                                        <option value="NXTMO3">Next Three Months</option>

                                        <option value="FUTURE">All Future Events</option>

                                        <option value="DIVIDER" disabled="">---------------------------</option>
                                        <option value="PRVMO">Last Month</option>

                                        <option value="PRVMO3">Last Three Months</option>

                                        <option value="PRVMO12">Last Twelve Months</option>

                                        <option value="PRVMO24">Last Twenty-Four Months</option>

                                        <option value="PRVMO48">Last Forty-Eight Months</option>

                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <select class="form-control">
                                        <option value="Select Location" selected="selected">Select Location</option>
                                        <option>Dhaka</option>
                                        <option>Khulna</option>
                                        <option>Barisal</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <select class="form-control">
                                        <option value="Select Location" selected="selected">Category</option>
                                        <option>Movie</option>
                                        <option>Events</option>
                                        <option>..</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-search"></i> Search</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        <div class="main-container">
            <div class="container">
                <!-- Featured Event Div Start Here -->
                <?php if (count($arrayFeatured) > 0) : ?>
                    <div class="content-box">
                        <div class="titlebar">
                            <h3>Featured events</h3>
                        </div>
                        <div class="content-row">
                            <div class="featured-row row">

                                <?php foreach ($arrayFeatured AS $Featured): ?>
                                    <?php
                                    $startDate = '';
                                    $endDate = '';
                                    if (count($arrFeatVenue) > 0) {
                                        foreach ($arrFeatVenue AS $key => $val) {
                                            if ($key == $Featured->event_id) {
                                                $startDate = min($val['venue_start_date']);
                                                $endDate = max($val['venue_end_date']);

                                                if ($endDate == 0000 - 00 - 00) {
                                                    $endDate = '';
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                    <div class="event-item col-sm-4  col-md-3  col-xs-6 col-xxs-6">
                                        <div class="inner">
                                            <div class="item-img"> 
                                                <div class="date text-left">
                                                    <?php if ($startDate != 0000 - 00 - 00 OR $startDate != ""): ?><sub><?php echo date("d", strtotime($startDate)); ?></sub><sup><?php echo date("M", strtotime($startDate)); ?></sup><?php endif; ?> 
                                                    <?php if ($endDate != 0000 - 00 - 00 OR $endDate != ""): ?>- <sub><?php echo date("d", strtotime($endDate)); ?></sub><sup><?php echo date("M", strtotime($endDate)); ?></sup><?php endif; ?>
                                                </div>
                                                <!--<span class="category-label events"><?php // echo $Featured->category_title;          ?></span>-->
                                                <div class="overly">
                                                    <div class="dtable hw100">
                                                        <div class="dtable-cell hw100"> <a class="btn btn-primary"  href="<?php echo baseUrl(); ?>details/<?php echo $Featured->event_id; ?>/<?php echo clean($Featured->event_title); ?>"> Buy Ticket </a> </div>
                                                    </div>
                                                </div>
                                                <img src="<?php echo $config['IMAGE_UPLOAD_URL'] . '/event_web_logo/' . $Featured->event_web_logo; ?>" alt="img"> 
                                            </div>
                                            <div class="item-content">
                                                <h5><?php echo $Featured->event_title; ?></h5>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                            </div>
                            <!--/.featured-row--> 
                        </div>
                        <!--/.content-row--> 

                    </div>
                <?php endif; ?>
                <!-- Featured Event Div End Here -->

                <!-- Upcoming Event Div Start Here -->
                <?php if (count($arrayUpcoming) > 0) : ?>
                    <div class="content-box">
                        <div class="titlebar">
                            <h3>Upcoming events</h3>
                        </div>
                        <div class="content-row">
                            <div class="featured-row row">

                                <?php foreach ($arrayUpcoming AS $Upcoming): ?>
                                    <?php
                                    $startDate = '';
                                    $endDate = '';
                                    if (count($arrUpcomingVenue) > 0) {
                                        foreach ($arrUpcomingVenue AS $key => $val) {
                                            if ($key == $Upcoming->event_id) {
                                                $startDate = min($val['venue_start_date']);
                                                $endDate = max($val['venue_end_date']);

                                                if ($endDate == 0000 - 00 - 00) {
                                                    $endDate = '';
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                    <div class="event-item col-sm-4  col-md-3  col-xs-6 col-xxs-6">
                                        <div class="inner">
                                            <div class="item-img"> 
                                                <div class="date text-left">
                                                    <?php if ($startDate != 0000 - 00 - 00 OR $startDate != ""): ?><sub><?php echo date("d", strtotime($startDate)); ?></sub><sup><?php echo date("M", strtotime($startDate)); ?></sup><?php endif; ?> 
                                                    <?php if ($endDate != 0000 - 00 - 00 OR $endDate != ""): ?>- <sub><?php echo date("d", strtotime($endDate)); ?></sub><sup><?php echo date("M", strtotime($endDate)); ?></sup><?php endif; ?>
                                                </div>
                                                <!--<span class="category-label events"><?php echo $Featured->category_title; ?></span>-->

                                                <img src="<?php echo $config['IMAGE_UPLOAD_URL'] . '/event_web_logo/' . $Upcoming->event_web_logo; ?>" alt="img"> 
                                            </div>
                                            <div class="item-content">
                                                <h5><?php echo $Upcoming->event_title; ?></h5>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                            </div>
                            <!--/.featured-row--> 
                        </div>

                    </div>
                <?php endif; ?>
                <!-- Upcoming Event Div End Here -->




                <hr class="site">
                <div class="content-box">
                    <div class="content-row">
                        <div class="content-row row">
                            <div class="col-md-8 left-siderbar">
                                <div class="category-list">
                                    <div class="tab-box "> 
                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs list-tabs" role="tablist">
                                            <?php if (count($arrRootCat) > 0): ?>
                                                <?php $countTabHead = 0; ?>
                                                <?php foreach ($arrRootCat AS $RootCat): ?>
                                                    <li class="<?php
                                                    if ($countTabHead == 0) {
                                                        echo 'active';
                                                    }
                                                    ?>" style="background-color:<?php // if($RootCat->category_color != ""){ echo $RootCat->category_color; }  ?>"><a  href="#<?php echo clean($RootCat->category_title); ?>" role="tab" data-toggle="tab"><?php echo $RootCat->category_title; ?> </a>
                                                    </li>
                                                    <?php $countTabHead++; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                    <!--/.tab-box-->
                                    <div class="tab-content">
                                        <?php if (count($arrRootCat) > 0): ?>
                                            <?php $countTabHead = 0; ?>
                                            <?php foreach ($arrRootCat AS $RootCat): ?>    

                                                <div role="tabpanel" class="tab-pane <?php
                                                if ($countTabHead == 0) {
                                                    echo 'active';
                                                }
                                                ?>" id="<?php echo clean($RootCat->category_title); ?>"><!-- Latest Event Tab End Here -->
                                                    <div class="adds-wrapper">
                                                        <?php if (count($latestEventArray) > 0): ?>
                                                            <?php foreach ($latestEventArray AS $LatestEvent): ?>
                                                                <?php if (in_array($RootCat->category_id, $LatestEvent['event_category_id_array'])): ?>
                                                                    <div class="item-list">
                                                                        <div class="col-sm-2 no-padding photobox">
                                                                            <div class="item-list-image">  <a href="<?php echo baseUrl(); ?>details/<?php echo $LatestEvent['event_id']; ?>/<?php echo clean($LatestEvent['event_title']); ?>"><img class="thumbnail no-margin" src="<?php echo $config['IMAGE_UPLOAD_URL'] . '/event_web_logo/' . $LatestEvent['event_web_logo']; ?>" alt="<?php echo $LatestEvent['event_title']; ?>"></a> </div>
                                                                        </div>
                                                                        <!--/.photobox-->
                                                                        <div class="col-sm-7 eventlist-desc-box">
                                                                            <div class="eventlist-details">
                                                                                <h4 class="add-title">
                                                                                    <a href="<?php echo baseUrl(); ?>details/<?php echo $LatestEvent['event_id']; ?>/<?php echo clean($LatestEvent['event_title']); ?>"><?php echo $LatestEvent['event_title']; ?></a> 
                                                                                </h4>
                                                                                <span class="info-row">
                                                                                    <span class="date"><i class=" icon-clock"></i>
                                                                                        <?php
                                                                                        $dayName = $LatestEvent['venue_start_date'];
                                                                                        echo $day = date("l, d F, Y", strtotime($dayName));
                                                                                        ?>&nbsp;&nbsp;
                                                                                    </span>
                                                                                    <span class="item-location">&nbsp;&nbsp;
                                                                                        <i class="fa fa-map-marker"></i>&nbsp;&nbsp;
                                                                                        <?php echo $LatestEvent['venue_title']; ?>
                                                                                    </span> 
                                                                                </span> 
                                                                                <a class="mini-link" href="<?php echo baseUrl(); ?>details/<?php echo $LatestEvent['event_id']; ?>/<?php echo clean($LatestEvent['event_title']); ?>">More info <i class="fa fa-caret-right"></i> </a>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-3 text-right  price-box">
                                                                            <a href="<?php echo baseUrl(); ?>details/<?php echo $LatestEvent['event_id']; ?>/<?php echo clean($LatestEvent['event_title']); ?>" class="btn btn-primary  btn-sm"> 
                                                                                BUY TICKET <i class="fa fa-ticket"></i> 
                                                                            </a>
                                                                            <?php if (in_array($LatestEvent['event_id'] . '-' . 'event', $arrWishlist)): ?>
                                                                                <a id="wishlist_<?php echo $LatestEvent['event_id']; ?>" class="btn btn-default  btn-sm make-favorite added" title="Added to Wishlist"><i class="fa fa-heart"></i></a>
                                                                            <?php else: ?>
                                                                                <a id="wishlist_<?php echo $LatestEvent['event_id']; ?>" onclick="javascript:addToWishlist(<?php echo $LatestEvent['event_id']; ?>, 'event');" class="btn btn-default  btn-sm make-favorite" title="Add to Wishlist"><i class="fa fa-heart"></i></a>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <h3 class="text-center">No recent event found in the record.</h3>
                                                        <?php endif; ?>
                                                    </div>
                                                    <!--/.adds-wrapper-->
                                                    <div class="tab-box  all-bar text-center"> <a href="<?php echo baseUrl(); ?>category/<?php echo $RootCat->category_id; ?>/<?php echo clean($RootCat->category_title); ?>"> <i class=" icon-star-empty"></i> View All </a> </div>
                                                </div><!--tab-pane--><!-- Latest Event Tab End Here -->
                                                <?php $countTabHead++; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div> <!--/.tab-content-->
                                </div>
                            </div>
                            <!--left-siderbar-->

                            <div class="col-md-4 right-siderbar">
                                <div class="sidebar-events">
                                    <div class="title-with-bar">
                                        <h3>Announcement</h3>
                                    </div>
                                    <div class="tickerWrapper">
                                        <div class="sidebar-body">
                                            <?php if (count($arrAnnounce) > 0): ?>
                                                <?php foreach ($arrAnnounce AS $Announce): ?>


                                                    <div class="sidebar-eItem">
                                                        <div class="sidebar-eItem-inner"> <h5 class="eItem-title"><a href="<?php echo baseUrl(); ?>announcement/<?php echo $Announce->announcement_id; ?>/<?php echo clean($Announce->announcement_title); ?>"><?php echo $Announce->announcement_title; ?> </a></h5>
                                                            <div class="eItem-location"> <?php echo $Announce->announcement_short_desc; ?>  </div>
                                                        </div>
                                                    </div>


                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <h5 class="text-center">No new announcement found.</h5>
                                    <?php endif; ?>
                                </div>

                                <!-- Offer Div Start Here -->
                                <div class="offer-promotion">
                                    <div class="offer-promo-title">
                                        <h4>Offers &amp; Promotion</h4>
                                    </div>

                                    <div id="offer-promo-slider" class="owl-carousel owl-theme">
                                        <?php if (count($offerPromotionArray) > 0): ?>
                                            <?php foreach ($offerPromotionArray AS $offerpromotion): ?>
                                                <div class="op-item"> 
                                                    <img class="img-responsive" src="<?php echo $config['IMAGE_UPLOAD_URL'] . '/SO_image/' . $offerpromotion->SO_image; ?>" alt="OfferImage">
                                                    <div class="offer-promo-footer">
                                                        <div>
                                                            <?php if ($offerpromotion->SO_on_event_id > 0): ?>
                                                                <h4><a href="<?php echo baseUrl(); ?>details/<?php echo $offerpromotion->SO_on_event_id; ?>/<?php echo clean($eventDataArray[$offerpromotion->SO_on_event_id]->event_title); ?>">Buy ticket for <?php echo $eventDataArray[$offerpromotion->SO_on_event_id]->event_title; ?></a></h4>
                                                            <?php else: ?>
                                                                <h4><a href="<?php echo baseUrl(); ?>category/0/Root">Buy any ticket</a></h4>
                                                            <?php endif; ?>

                                                            <?php if ($offerpromotion->SO_to_type == "Quantity"): ?>    
                                                                <h6>Get <?php echo $offerpromotion->SO_to_amount; ?> tickets free</h6>
                                                            <?php else: ?>
                                                                <h6>Get <?php echo $offerpromotion->SO_to_amount; ?> taka off</h6>
                                                            <?php endif; ?>
                                                        </div>
                                                        <!--                                                        <div class="text-right">
                                                                                                                    <h6>Starting @</h6>
                                                                                                                    <h5>Tk. 800</h5>
                                                                                                                </div>-->
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>

                                        </div>
                                    <?php else: ?>
                                        <h4 class="text-center">No offers and promotion found.</h4>
                                    <?php endif; ?>

                                </div>
                                <!-- Offer Div End Here -->
                            </div>
                            <!--right-siderbar--> 
                        </div>
                        <!--/.featured-row--> 
                    </div>
                    <!--/.content-row--> 
                </div>

                <div class="clearfix" style="margin-top: 15px;"></div>


                <!--/.content-box--> 
                <?php if (count($arrayArchived) > 0): ?>
                    <div class="content-box">
                        <div class="titlebar clearfix">
                            <h3 class="pull-left wow fadeIn" data-wow-duration="2s" data-wow-delay="0.03s" >Covered Events</h3>
                            <div class="featured-navi pull-right">
                                <a class="prev"><i class="icon-left-open-big"></i></a>
                                <a class="next"><i class=" icon-right-open-big"></i></a>
                            </div>
                        </div>
                        <div class="content-row wow fadeInDown" data-wow-duration="1s" data-wow-delay="0.05s" style="margin-left: -15px; margin-right: -15px;">
                            <div id="archived-event" class="hasmovie featured-row-movie owl-carousel owl-theme">
                                <?php foreach ($arrayArchived AS $archived): ?>
                                    <div class="event-item ">
                                        <div class="inner covered-event-div" style="min-height: 280px;">
                                            <div class="item-img">
                                                <img src="<?php echo $config['IMAGE_UPLOAD_URL'] . '/event_web_logo/' . $archived->event_web_logo; ?>" alt="<?php echo $archived->event_title; ?>"> </div>
                                            <div class="item-content">
                                                <h4><a href="javascript:void(0);"><?php echo substr($archived->event_title, 0, 30) . ".."; ?></a></h4>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div> 
                        </div>
                    </div>
                <?php endif; ?>

            </div>
            <div class="section-padd">
                <?php include basePath('partner.php'); ?>
                <?php include basePath('our_featured.php'); ?>
            </div>

        </div>
    </div>


    <?php // include basePath('testimonial.php');      ?>
    <?php include basePath('social_link.php'); ?>
    <?php include basePath('footer.php'); ?>
    <?php include basePath('login_modal.php'); ?>
    <?php include basePath('signup_modal.php'); ?>
    <?php include basePath('footer_script.php'); ?>

    <!-- Include slider plugins || Only for homepage needed --> 

    <script src="<?php echo baseUrl('js/home.js'); ?>"></script> 


    <script src="<?php echo baseUrl('js/script.js'); ?>"></script>
    <script src="<?php echo baseUrl('js/wow.min.js'); ?>"></script>
    <script src="<?php echo baseUrl('js/jquery.easy-ticker.min.js'); ?>"></script>
    <script>
                                                                                    $(document).ready(function () {
                                                                                        $('.tickerWrapper').easyTicker({
                                                                                            visible: 5,
                                                                                            //easing: 'easeInOutCubic',
                                                                                            speed: 'slow',
                                                                                            interval: 3000,
                                                                                            direction: 'down',
                                                                                            height: 'auto'
                                                                                        });// list of properties
                                                                                    });
    </script>
    <script>
        new WOW().init();


        // imageShowCase  carousel
        var imageShowCase = $("#imageShowCase");

        imageShowCase.owlCarousel({
            autoPlay: 4000,
            stopOnHover: true,
            navigation: false,
            pagination: true,
            paginationSpeed: 1000,
            goToFirstSpeed: 2000,
            singleItem: true,
            autoHeight: true


        });

        // Custom Navigation Events
        $("#ps-next").click(function () {
            imageShowCase.trigger('owl.next');
        })
        $("#ps-prev").click(function () {
            imageShowCase.trigger('owl.prev');
        })






        var owlae = $("#archived-event");

        owlae.owlCarousel({
            navigation: false,
            pagination: false,
            items: 5, //10 items above 1000px browser width
            itemsDesktop: [1000, 3], //5 items between 1000px and 901px
            itemsDesktopSmall: [900, 3], // betweem 900px and 601px
            itemsTablet: [600, 2], //2 items between 600 and 0
            itemsMobile: false // itemsMobile disabled - inherit from itemsTablet option
        });

        // Custom Navigation Events
        $(".featured-navi .next").click(function () {
            owlae.trigger('owl.next');
        })
        $(".featured-navi .prev").click(function () {
            owlae.trigger('owl.prev');
        })

    </script>


</body>
</html>