<?php
include "config/config.php";

$eventID = 0;

$eventTitle = '';
$eventWebLogo = '';
$eventWebBanner = '';
$eventTags = '';
$todayDate = date("Y-m-d");
$geoLocation = '';
$eventTermsandCon = '';
$eventDetails = '';
$isPrivate = '';

$arrEventVenues = array();
$arrEventVenuesID = array();
$arrEventVenueLatLong = array();
$arrayEventTickets = array();
$arrEventIncludes = array();
$arrTags = array();
$arrAllImage = array();
$arrAllVideo = array();
$arrGuests = array();
$arrFAQ = array();
$arrSimilarEvents = array();

if (isset($_GET['id'])) {
    $eventID = validateInput($_GET['id']);
}


if ($eventID > 0) {

    //getting event details from database
    $sqlGetEvent = "SELECT * FROM events "
            . "WHERE event_id=$eventID";
    $resultGetEvent = mysqli_query($con, $sqlGetEvent);
    if ($resultGetEvent) {
        $resultGetEventObj = mysqli_fetch_object($resultGetEvent);
        if (isset($resultGetEventObj->event_id)) {
            $eventTitle = $resultGetEventObj->event_title;
            $eventDetails = $resultGetEventObj->event_description;
            $eventWebLogo = $resultGetEventObj->event_web_logo;
            $eventWebBanner = $resultGetEventObj->event_web_banner;
            $eventTermsandCon = $resultGetEventObj->event_terms_conditions;
            $eventTags = $resultGetEventObj->event_tag;
            $isPrivate = $resultGetEventObj->event_is_private;
            $isSeatPlan = $resultGetEventObj->event_is_seat_plan;
        }
    } else {
        if (DEBUG) {
            echo "resultGetEvent error: " . mysqli_error($con);
        } else {
            echo "resultGetEvent query failed.";
        }
    }


    //getting event venunes from database
    $sqlEventVenues = "SELECT * FROM event_venues "
            . "WHERE venue_event_id=$eventID";
    $resultEventVenues = mysqli_query($con, $sqlEventVenues);
    if ($resultEventVenues) {
        while ($resultEventVenuesObj = mysqli_fetch_object($resultEventVenues)) {
            $arrEventVenues[] = $resultEventVenuesObj;
            $arrEventVenuesID[] = $resultEventVenuesObj->venue_id;
        }
    } else {
        if (DEBUG) {
            echo "resultEventVenues error: " . mysqli_error($con);
        } else {
            echo "resultEventVenues query failed.";
        }
    }

    //getting event tickets from database
    $strEventVenue = implode(',', $arrEventVenuesID);
    if ($strEventVenue != "") {
        $sqlEventTickets = "SELECT * FROM event_ticket_types "
                . "WHERE TT_venue_id IN ($strEventVenue) "
                . "AND TT_status='active' "
                . "OR TT_status='inactive'";
        $resultEventTickets = mysqli_query($con, $sqlEventTickets);
        if ($resultEventTickets) {
            while ($resultEventTicketsObj = mysqli_fetch_object($resultEventTickets)) {
                $arrayEventTickets[$resultEventTicketsObj->TT_venue_id][] = $resultEventTicketsObj;
            }
        } else {
            if (DEBUG) {
                echo "resultEventTickets error: " . mysqli_error($con);
            } else {
                echo "resultEventTickets query failed.";
            }
        }
    }


    //getting event includes from database
    $sqlEventIncludes = "SELECT * FROM event_includes "
            . "WHERE EI_event_id=$eventID";
    $resultEventIncludes = mysqli_query($con, $sqlEventIncludes);
    if ($resultEventIncludes) {
        while ($resultEventIncludesObj = mysqli_fetch_object($resultEventIncludes)) {
            $arrEventIncludes[$resultEventIncludesObj->EI_venue_id][] = $resultEventIncludesObj;
        }
    } else {
        if (DEBUG) {
            echo "resultEventIncludes error: " . mysqli_error($con);
        } else {
            echo "resultEventIncludes query failed.";
        }
    }



    //getting tag information from db
    $sqlAllTag = "SELECT * FROM tags";
    $resultAllTag = mysqli_query($con, $sqlAllTag);
    if ($resultAllTag) {
        while ($resultAllTagObj = mysqli_fetch_object($resultAllTag)) {
            $arrTags[$resultAllTagObj->tag_title] = $resultAllTagObj->tag_color;
        }
    } else {
        if (DEBUG) {
            echo "resultAllTag error: " . mysqli_error($con);
        } else {
            echo "resultAllTag query failed.";
        }
    }


    //getting image gallery information from db
    $sqlImageGallery = "SELECT * FROM event_image_gallery WHERE IG_event_id=$eventID";
    $resultImageGallery = mysqli_query($con, $sqlImageGallery);
    if ($resultImageGallery) {
        while ($resultImageGalleryObj = mysqli_fetch_object($resultImageGallery)) {
            $arrAllImage[] = $resultImageGalleryObj;
        }
    } else {
        if (DEBUG) {
            echo "resultImageGallery error: " . mysqli_error($con);
        } else {
            echo "resultImageGallery query failed.";
        }
    }


    //getting image gallery information from db
    $sqlVideoGallery = "SELECT * FROM event_video_gallery WHERE VG_event_id=$eventID";
    $resultVideoGallery = mysqli_query($con, $sqlVideoGallery);
    if ($resultVideoGallery) {
        while ($resultVideoGalleryObj = mysqli_fetch_object($resultVideoGallery)) {
            $arrAllVideo[] = $resultVideoGalleryObj;
            $arrVidID = explode('=', $resultVideoGalleryObj->VG_video_link);
            $arrAllVideo[(count($arrAllVideo) - 1)]->video_id = $arrVidID[1];
        }
    } else {
        if (DEBUG) {
            echo "resultVideoGallery error: " . mysqli_error($con);
        } else {
            echo "resultVideoGallery query failed.";
        }
    }



    //getting image guest information from db
    $sqlGuests = "SELECT * FROM event_participants WHERE EP_event_id=$eventID";
    $resultGuests = mysqli_query($con, $sqlGuests);
    if ($resultGuests) {
        while ($resultGuestsObj = mysqli_fetch_object($resultGuests)) {
            $arrGuests[] = $resultGuestsObj;
        }
    } else {
        if (DEBUG) {
            echo "resultGuests error: " . mysqli_error($con);
        } else {
            echo "resultGuests query failed.";
        }
    }

//    debug($arrGuests);
    //getting image guest information from db
    $sqlFAQ = "SELECT * FROM event_faqs WHERE EF_event_id=$eventID";
    $resultFAQ = mysqli_query($con, $sqlFAQ);
    if ($resultFAQ) {
        while ($resultFAQObj = mysqli_fetch_object($resultFAQ)) {
            $arrFAQ[] = $resultFAQObj;
        }
    } else {
        if (DEBUG) {
            echo "resultFAQ error: " . mysqli_error($con);
        } else {
            echo "resultFAQ query failed.";
        }
    }



    //getting image guest information from db
    $sqlSimilarEvent = "SELECT * FROM event_similar "
            . "LEFT JOIN events ON events.event_id=event_similar.ES_similar_event_id "
            . "WHERE ES_event_id=$eventID";
    $resultSimilarEvent = mysqli_query($con, $sqlSimilarEvent);
    if ($resultSimilarEvent) {
        while ($resultSimilarEventObj = mysqli_fetch_object($resultSimilarEvent)) {
            $arrSimilarEvents[] = $resultSimilarEventObj;
        }
    } else {
        if (DEBUG) {
            echo "resultSimilarEvent error: " . mysqli_error($con);
        } else {
            echo "resultSimilarEvent query failed.";
        }
    }
}

//debug($arrSimilarEvents);
// Getting Offer and Promotion based on eventId

$relatedOffer = "";
$sqlOfferPromotion = "SELECT event_special_offer.SO_image "
        . " FROM event_special_offer"
        . " WHERE SO_status='active' AND SO_on_event_id=$eventID ORDER BY event_special_offer.SO_created_by DESC LIMIT 1";
$resultOfferPromotion = mysqli_query($con, $sqlOfferPromotion);
if ($resultOfferPromotion) {
    while ($OfferPromotionObj = mysqli_fetch_object($resultOfferPromotion)) {
        $relatedOffer = $OfferPromotionObj->SO_image;
    }
} else {
    if (DEBUG) {
        echo "resultOfferPromotion error: " . mysqli_error($con);
    } else {
        echo "resultOfferPromotion query failed.";
    }
}


$arrVenueStartDate = array();
if (count($arrEventVenues) > 0) {
    foreach ($arrEventVenues AS $VenueDate) {
        $arrVenueStartDate[] = $VenueDate->venue_start_date . " " . $VenueDate->venue_start_time;
    }
}
//echo $relatedOffer;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="<?php echo strip_tags(html_entity_decode($eventDetails)); ?>" >
        <meta name="keywords" content="<?php echo $eventTitle; ?>" >
        <?php include basePath('header_script.php'); ?>
        <!-- From Event Details Page -->
        <link rel="stylesheet" href="<?php echo baseUrl('css/magnific-popup.css'); ?>">


        <!-- Open Graph url property -->
        <meta property="og:url" content="<?php echo baseUrl(); ?>details/<?php echo $eventID; ?>/<?php echo clean($eventTitle); ?>" />
        <!-- Open Graph title property -->
        <meta property="og:title" content="Ticket Chai | <?php echo $eventTitle; ?>" />
        <!-- Open Graph description property -->
        <meta property="og:description" content="<?php echo strip_tags(html_entity_decode($eventDetails)); ?>" />
        <!--- Open Graph image property -->
        <meta property="og:image" content="<?php echo $config['IMAGE_UPLOAD_URL'] . '/event_web_logo/' . $eventWebLogo; ?>"/>
        <!-- Open Graph type property -->
        <meta property="og:type" content="website" />
        <!-- Open Graph site_name property -->
        <meta property="og:site_name" content="Ticket Chai" />
        <meta property="og:locale" content="en_GB" />


        <!-- Go to www.addthis.com/dashboard to customize your tools -->
        <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5557392b55676617" async="async"></script>

        <!-- Go to www.addthis.com/dashboard to customize your tools -->
        <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5557392b55676617" async="async"></script>

    </head>
    <body class="home">

        <div class="popup_box">    <!-- OUR PopupBox DIV-->
            <h3 class="popup_box_h3" align="center"></h3>
        </div>

        <header>
            <div class="header-wrapper">
                <?php include basePath('menu_top.php'); ?>
                <?php include basePath('navigation.php'); ?>
            </div>
        </header>

        <div class="main-container">
            <!-- Silder Div Start Here -->
            <div class="category-banner-holder">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="category-banner-slider owl-carousel owl-theme">
                                <?php if (file_exists($config['IMAGE_UPLOAD_PATH'] . '/event_web_banner/' . $eventWebBanner) AND $eventWebBanner != ''): ?>
                                    <div class="category-banner-item">
                                        <img class="img-responsive" src="<?php echo $config['IMAGE_UPLOAD_URL'] . '/event_web_banner/' . $eventWebBanner; ?>" style="width:100%;" alt="">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Silder Div End Here -->
            <div style="clear:both;"></div>
            <div class="container">
                <div class="content-box">
                    <div class="content-row">
                        <div class="content-row row">
                            <div class="col-md-8 left-siderbar">
                                <div class="category-list">
                                    <div role="tabpanel">
                                        <ul class="nav nav-pills nav-justified list-tabs-event" role="tablist">

                                            <!--Checking if event venue exist-->
                                            <?php if (count($arrEventVenues) > 0): ?>
                                                <li role="presentation" class="active"><a href="#ticket" aria-controls="ticket" role="tab" data-toggle="tab"><i class="fa fa-ticket"></i> Tickets </a></li>
                                            <?php endif; ?>
                                            <!--End of Checking if event venue exist-->

                                            <!--Checking if event details exist-->
                                            <?php if ($eventDetails != ""): ?>
                                                <li role="presentation"><a href="#aboutEvent" aria-controls="aboutEvent" role="tab" data-toggle="tab"><i class="fa fa-info-circle"></i> About Event</a></li>
                                            <?php endif; ?>
                                            <!--Checking if event details exist-->

                                            <!--Checking if event venue exist-->
                                            <?php if (count($arrEventVenues) > 0): ?>
                                                <li role="presentation"><a id="venue" href="#Venue" aria-controls="Venue" role="tab" data-toggle="tab"><i class="icon-location"></i> Venue</a></li>
                                            <?php endif; ?>
                                            <!--Checking if event venue exist-->

                                            <!--Checking if event gallery exist-->
                                            <?php if (count($arrAllImage) > 0 OR count($arrAllVideo) > 0): ?>
                                                <li role="presentation"><a href="#gallery" aria-controls="gallery" role="tab" data-toggle="tab"><i class="fa fa-camera"></i> Gallery</a></li>
                                            <?php endif; ?>
                                            <!--Checking if event gallery exist-->

                                            <!--Checking if event guest exist-->
                                            <?php if (count($arrGuests) > 0): ?>
                                                <li role="presentation"><a href="#guest" aria-controls="guest" role="tab" data-toggle="tab"><i class="fa fa-users"></i> guest</a></li>
                                            <?php endif; ?>
                                            <!--Checking if event guest exist-->


                                            <!--Checking if T & C exist-->
                                            <?php if ($eventTermsandCon != ''): ?>
                                                <li role="presentation"><a href="#tc" aria-controls="tc" role="tab" data-toggle="tab"><i class="fa fa-user-secret"></i> T &amp; C</a></li>
                                            <?php endif; ?>
                                            <!--Checking if T & C exist-->

                                            <!--Checking if FAQs exist-->
                                            <?php if (count($arrFAQ) > 0): ?>
                                                <li role="presentation"><a href="#faq" aria-controls="faq" role="tab" data-toggle="tab"><i class="fa fa-comment"></i> FAQ</a></li>
                                            <?php endif; ?>
                                            <!--Checking if FAQs exist-->

                                        </ul>
                                        <div class="tab-content tab-content-event adds-wrapper">
                                            <div role="tabpanel" class="tab-pane active" id="ticket">
                                                <div class="tabpanel-head">
                                                    <div class="row">
                                                        <div class="col-md-9 col-sm-8">
                                                            <h3 class="event-name wow fadeIn" data-wow-duration="1s" data-wow-delay="0.15s"><?php echo $eventTitle; ?>&nbsp;&nbsp;&nbsp;
                                                                <?php if (in_array($eventID . '-' . 'event', $arrWishlist)): ?>
                                                                    <a id="wishlist_<?php echo $eventID; ?>" class="btn btn-default  btn-sm make-favorite added" title="Added to Wishlist"><i class="fa fa-heart"></i></a>
                                                                <?php else: ?>
                                                                    <a id="wishlist_<?php echo $eventID; ?>" onclick="javascript:addToWishlist(<?php echo $eventID; ?>, 'event');" class="btn btn-default  btn-sm make-favorite" title="Add to Wishlist"><i class="fa fa-heart"></i></a>
                                                                <?php endif; ?>
                                                            </h3>

                                                            <div class="event-info  wow fadeIn" data-wow-duration="1s" data-wow-delay="0.25s" style="margin-top: 30px !important;"> 
                                                                <?php if (count($arrVenueStartDate) > 1): ?>
                                                                    <h5><strong><i class="fa fa-calendar"></i>&nbsp;&nbsp;From <?php echo date("l, jS F Y h:i A", strtotime(min($arrVenueStartDate))); ?> - <?php echo date("l, jS F Y h:i A", strtotime(max($arrVenueStartDate))); ?></strong></h5><br/>
                                                                <?php else: ?>
                                                                    <h5><strong><i class="fa fa-calendar"></i>&nbsp;&nbsp;On <?php echo date("l, jS F Y h:i A", strtotime($arrVenueStartDate[0])); ?></strong></h5><br/>
                                                                <?php endif; ?>    
                                                                <div class="tagline wow fadeIn animated clearfix" data-wow-duration="1s" data-wow-delay="0.35s">
                                                                    <?php
                                                                    $arrEventTag = array();
                                                                    if ($eventTags != '') {
                                                                        $arrEventTag = explode(',', $eventTags);
                                                                        foreach ($arrEventTag as $key => $val) {
                                                                            if (isset($arrTags[$val])) {
                                                                                ?>
                                                                                <a class="itemtag" style="border-color: <?php echo $arrTags[$val]; ?>; background-color: <?php echo $arrTags[$val]; ?>;"><span ><?php echo $val; ?></span></a>
                                                                                <?php
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-3 col-sm-4">
                                                            <?php if (file_exists($config['IMAGE_UPLOAD_PATH'] . '/event_web_logo/' . $eventWebLogo) AND $eventWebLogo != ''): ?>
                                                                <img class="img-responsive" src="<?php echo $config['IMAGE_UPLOAD_URL'] . '/event_web_logo/' . $eventWebLogo; ?>" alt="<?php echo $eventTitle; ?>">
                                                            <?php endif; ?>    
                                                        </div>
                                                       
                                                    </div>
                                                </div>


                                                <!--Start of Event Details Tab-->
                                                <span id="Registration-Form" <?php
                                                if ($isPrivate == "yes" AND ( !isset($_SESSION['REGISTRATION-FORM-' . $eventID]) OR $_SESSION['REGISTRATION-FORM-' . $eventID] != "yes")) {
                                                    echo 'style="margin-bottom: 20px; display: block;"';
                                                } else {
                                                    echo 'style="margin-bottom: 20px; display: none;"';
                                                }
                                                ?>>
                                                    <div class="tabpanel-body">
                                                        <div class="ticket-info-holder">
                                                            <div class="ticket-info-header">
                                                                <h4><?php echo $eventTitle; ?> Registration Form</h4>
                                                            </div>
                                                            <br/>

                                                            <form method="post" enctype="multipart/form-data" id="registration_form" action="<?php echo baseUrl(); ?>ajax/ajaxSubmitEventRegistration.php">
                                                                <!--Generating dynamic form-->
                                                                <?php echo generateRegistrationForm($eventID); ?>


                                                            </form>
                                                            <div class="clearfix"></div><br/>
                                                        </div>
                                                    </div>
                                                </span>
                                                <?php $count = 0; ?>
                                                <?php if (count($arrEventVenues) > 0): ?>
                                                    <span id="All-Ticket-Types" 
                                                    <?php
                                                    if ($isPrivate == "yes" AND ( !isset($_SESSION['REGISTRATION-FORM-' . $eventID]) OR $_SESSION['REGISTRATION-FORM-' . $eventID] != "yes")) {
                                                        echo 'style="margin-bottom: 20px; display: none !important;"';
                                                    } else {
                                                        echo 'style="margin-bottom: 20px; display: block !important;"';
                                                    }
                                                    ?>>
                                                              <?php foreach ($arrEventVenues AS $Venues): ?>
                                                                  <?php if ($todayDate >= $Venues->venue_valid_from AND $todayDate <= $Venues->venue_valid_till): ?>
                                                                <div class="tabpanel-body">
                                                                    <div class="ticket-info-holder">
                                                                        <div class="ticket-info-header" style="background-color: #A9CA4F;">
                                                                            <h4><?php echo date("jS F Y h:i A", strtotime(($Venues->venue_start_date . " " . $Venues->venue_start_time))); ?> @ <?php echo $Venues->venue_title; ?></h4>
                                                                        </div>
                                                                        <div class="ticket-info-body" style="border:solid 2px #A9CA4F !important;">
                                                                            

                                                                            <div class=" titcket-cart-row-wrapper">
                                                                                <?php if (!empty($arrayEventTickets[$Venues->venue_id])): ?>
                                                                                    <?php if (count($arrayEventTickets[$Venues->venue_id]) > 0): ?>
                                                                                        <?php $count++; ?>
                                                                                        <div class="row">
                                                                                            <h3 class="text-center">Event Tickets</h3>
                                                                                        </div>
                                                                                        <div class="clearfix"></div>

                                                                                        <div class="row">
                                                                                            <div class="col-md-4 hidden-sm hidden-xs text-center"><strong>Ticket Type</strong></div>
                                                                                            <div class="col-md-2 hidden-sm hidden-xs text-center"><strong>Quantity</strong></div>
                                                                                            <div class="col-md-3 hidden-sm hidden-xs text-center"><strong>Price</strong></div>
                                                                                            <div class="col-md-3 hidden-sm hidden-xs text-center"><strong>Action</strong></div>
                                                                                        </div>
                                                                                        <div class="clearfix" style="height: 10px;"></div>

                                                                                        <div class="row titcket-cart-row" style="border: #0daf65 solid 1px; padding: 8px; margin: 10px;">
                                                                                            <?php $countTickt = 0; ?>
                                                                                            <?php foreach ($arrayEventTickets[$Venues->venue_id] AS $Tickets): ?>
                                                                                                <?php $countTickt++; ?>
                                                                                                <div class="col-md-4" style=""> 
                                                                                                    <h4 class="price text-center"><?php echo $Tickets->TT_type_title; ?></h4> 
                                                                                                    <br> 
                                                                                                    <p class="small"><?php echo html_entity_decode($Tickets->TT_type_description); ?></p>
                                                                                                </div>
                                                                                                <div class="text-center col-md-2" style="vertical-align: middle !important; padding: 8px;">
                                                                                                    <?php if ($isSeatPlan == "yes"): ?>
                                                                                                        <h5 class="text-center">N/A</h5>
                                                                                                    <?php else: ?>
                                                                                                        <select class="form-control" id="ticketQuantity_<?php echo $Tickets->TT_id; ?>">
                                                                                                            <?php if ($Tickets->TT_per_user_limit > 0): ?>
                                                                                                                <?php for ($i = 1; $i <= $Tickets->TT_per_user_limit; $i++): ?>
                                                                                                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                                                                                <?php endfor; ?>
                                                                                                            <?php endif; ?>
                                                                                                        </select>
                                                                                                    <?php endif; ?>
                                                                                                </div>
                                                                                                <div class="text-center col-md-3" style=""> 
                                                                                                    <h4 class="price text-center" style="vertical-align: middle !important; padding: 8px;"> 
                                                                                                        <?php if ($Tickets->TT_old_price > 0): ?>
                                                                                                            <strike><span style="font-size: x-small; color: rgb(236, 0, 0);"><?php echo $config['CURRENCY_SIGN']; ?> <?php echo $Tickets->TT_old_price; ?></span></strike> 
                                                                                                        <?php endif; ?>    
                                                                                                        <?php echo $config['CURRENCY_SIGN']; ?> <?php echo $Tickets->TT_current_price; ?>
                                                                                                    </h4>
                                                                                                </div>
                                                                                                <div class="text-center col-md-3" style="">
                                                                                                    <div style="border-top: none !important;" class="ticket-info-footer"> 
                                                                                                        <div class="price-box text-center"> 
                                                                                                            <?php if ($isSeatPlan == "no"): ?>
                                                                                                                <?php if ($Tickets->TT_ticket_quantity > 0): ?>
                                                                                                                    <a class="btn btn-primary" onclick="javascript:addToCart('ticket',<?php echo $eventID; ?>,<?php echo $Venues->venue_id; ?>,<?php echo $Tickets->TT_id; ?>);">
                                                                                                                        <i class="fa fa-cart-plus"></i> ADD TO CART 
                                                                                                                    </a>
                                                                                                                <?php else: ?>
                                                                                                                    <a class="btn btn-danger">
                                                                                                                        <i class="fa fa-exclamation-triangle"></i> SOLD OUT 
                                                                                                                    </a>
                                                                                                                <?php endif; ?>
                                                                                                            <?php else: ?>
                                                                                                                <button type="button" class="btn btn-primary" onclick="javascript:getPlaceMap(<?php echo $eventID; ?>,<?php echo $Venues->venue_id; ?>);">
                                                                                                                    <i class="fa fa-cart-plus"></i> Buy Tickets
                                                                                                                </button>
                                                                                                            <?php endif; ?>
                                                                                                        </div> 
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="clearfix"></div>
                                                                                                <?php if ($countTickt < count($arrayEventTickets[$Venues->venue_id])): ?>
                                                                                                    <hr>
                                                                                                <?php endif; ?>
                                                                                            <?php endforeach; ?>

                                                                                        </div>
                                                                                    <?php endif; ?>
                                                                                <?php else: ?>
                                                                                    <?php
                                                                                    $today = new DateTime("now");
                                                                                    $venueStartDay = new DateTime($Venues->venue_start_date);
                                                                                    ?>
                                                                                    <?php if ($venueStartDay > $today): ?>
                                                                                        <h4 class="text-center">Stay tuned!! Tickets will be available soon.</h4>
                                                                                    <?php else: ?>    
                                                                                        <h4 class="text-center"><?php echo $Venues->venue_closing_message; ?></h4>
                                                                                    <?php endif; ?>    
                                                                                <?php endif; ?>



                                                                                <br/>

                                                                                <?php if (!empty($arrEventIncludes[$Venues->venue_id])): ?>
                                                                                    <?php if (count($arrEventIncludes[$Venues->venue_id]) > 0): ?>
                                                                                        <div class="row">
                                                                                            <h3 class="text-center">Event Includes</h3>
                                                                                        </div>
                                                                                        <div class="clearfix"></div>

                                                                                        <div class="row">
                                                                                            <div class="col-md-4 hidden-sm hidden-xs text-center"><strong>Includes Type</strong></div>
                                                                                            <div class="col-md-2 hidden-sm hidden-xs text-center"><strong>Quantity</strong></div>
                                                                                            <div class="col-md-3 hidden-sm hidden-xs text-center"><strong>Price</strong></div>
                                                                                            <div class="col-md-3 hidden-sm hidden-xs text-center"><strong>Action</strong></div>
                                                                                        </div>
                                                                                        <div class="clearfix" style="height: 10px;"></div>

                                                                                        <?php $countIncld = 0; ?>
                                                                                        <div class="row titcket-cart-row" style="border: #0daf65 solid 1px; padding: 8px; margin: 10px;">
                                                                                            <?php foreach ($arrEventIncludes[$Venues->venue_id] AS $Include): ?>
                                                                                                <?php $countIncld++; ?>
                                                                                                <div class="col-md-4" style=""> 
                                                                                                    <h4 class="price text-center"><?php echo $Include->EI_name; ?></h4> 
                                                                                                    <br> 
                                                                                                    <p class="small"><?php echo html_entity_decode($Include->EI_description); ?></p>
                                                                                                </div>
                                                                                                <div class="text-center col-md-2" style="vertical-align: middle !important; padding: 8px;">
                                                                                                    <select class="form-control" id="includeQuantity_<?php echo $Include->EI_id; ?>">
                                                                                                        <?php if ($Include->EI_limit > 0): ?>
                                                                                                            <?php for ($i = 1; $i <= $Include->EI_limit; $i++): ?>
                                                                                                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                                                                            <?php endfor; ?>
                                                                                                        <?php endif; ?>
                                                                                                    </select>
                                                                                                </div>
                                                                                                <div class="text-center col-md-3" style=""> 
                                                                                                    <h4 class="price text-center" style="vertical-align: middle !important; padding: 8px;"> 
                                                                                                        <?php echo $config['CURRENCY_SIGN']; ?> <?php echo $Include->EI_price; ?>
                                                                                                    </h4>
                                                                                                </div>
                                                                                                <div class="text-center col-md-3" style="">
                                                                                                    <div style="border-top: none !important;" class="ticket-info-footer"> 
                                                                                                        <div class="price-box text-center"> 
                                                                                                            <?php if ($Include->EI_total_quantity > 0): ?>
                                                                                                                <a class="btn btn-primary" onclick="javascript:addToCart('include',<?php echo $eventID; ?>,<?php echo $Venues->venue_id; ?>,<?php echo $Include->EI_id; ?>);">
                                                                                                                    <i class="fa fa-cart-plus"></i> ADD TO CART 
                                                                                                                </a>
                                                                                                            <?php else: ?>
                                                                                                                <a class="btn btn-danger">
                                                                                                                    <i class="fa fa-ticket"></i> SOLD OUT 
                                                                                                                </a>
                                                                                                            <?php endif; ?>
                                                                                                        </div> 
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="clearfix"></div>
                                                                                                <?php if ($countIncld < count($arrEventIncludes[$Venues->venue_id])): ?>
                                                                                                    <hr>
                                                                                                <?php endif; ?>
                                                                                            <?php endforeach; ?>
                                                                                        </div>
                                                                                    <?php endif; ?>
                                                                                <?php endif; ?>
                                                                            </div>













                                                                            <?php if ($count == 0): ?>

                                                                                <!--<h3>Sorry, no tickets for sale right now.</h3>-->

                                                                            <?php endif; ?>


                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            <?php else: ?>
                                                                <?php if ($Venues->venue_closing_message != ""): ?>
                                                                    <h4 class="text-center" style="padding: 25px;"><?php echo $Venues->venue_closing_message; ?></h4>
                                                                <?php else: ?>
                                                                    <h4 class="text-center" style="padding: 25px;">Tickets are not available now. Thank you staying with us.</h4>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </span>
                                                <?php endif; ?>

                                                <?php // if ($count == 0): ?>

                                                <!--<h3 class="text-center" style="margin-bottom: 15px;">Sorry, no tickets for sale right now.</h3>-->

                                                <?php // endif; ?>


                                            </div>
                                            <!--End of Event Details Tab-->



                                            <!--Event Details Tab-->
                                            <?php if ($eventDetails != ""): ?>
                                                <div role="tabpanel" class="tab-pane" id="aboutEvent">
                                                    <div class="tabpanel-body">
                                                        <?php echo html_entity_decode($eventDetails); ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <!--End of Event Details Tab-->



                                            <div role="tabpanel" class="tab-pane" id="Venue">
                                                <div class="tabpanel-body">
                                                    <div id="bang">
                                                        <?php foreach ($arrEventVenues AS $venueDetails): ?>
                                                            <?php echo html_entity_decode($venueDetails->venue_description); ?>
                                                        <?php endforeach; ?>
                                                    </div>

                                                    <div class="map-fram">
                                                        <!--Div for google map-->
                                                        <div id="custom-map"></div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div role="tabpanel" class="tab-pane" id="gallery">
                                                <div class="tabpanel-head">
                                                    <h2>Photo Gallery</h2>
                                                </div>
                                                <div class="tabpanel-body">
                                                    <div class="galleryContainer row row-gall">

                                                        <?php if (count($arrAllImage) > 0): ?>
                                                            <?php foreach ($arrAllImage AS $Image): ?>
                                                                <div class="col-md-3 col-sm-6 col-xs-6 col-xxs-12 col-gall">
                                                                    <div class="col-gall-inner">
                                                                        <?php if (file_exists($config['IMAGE_UPLOAD_PATH'] . '/image_file/original/' . $Image->IG_image_name) AND $Image->IG_image_name != ''): ?>
                                                                            <a href="<?php echo $config['IMAGE_UPLOAD_URL'] . '/image_file/original/' . $Image->IG_image_name; ?>" class="gall-item" title="gallery">
                                                                                <img class="img-responsive" src="<?php echo $config['IMAGE_UPLOAD_URL'] . '/image_file/original/' . $Image->IG_image_name; ?>" alt="image">
                                                                                <div class="col-gallery-caption"></div>
                                                                            </a>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>        
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <br/><br/>
                                                <div class="tabpanel-head">
                                                    <h2>Video Gallery</h2>
                                                </div>
                                                <div class="tabpanel-body">
                                                    <div class="galleryContainer row row-gall">
                                                        <?php if (count($arrAllVideo) > 0): ?>
                                                            <?php foreach ($arrAllVideo AS $Video): ?>
                                                                <div class="col-md-6 pull-left nopadding text-center">
                                                                    <div class="col-gall-inner">
                                                                        <?php if ($Video->video_id != ''): ?>
                                                                            <embed src="http://www.youtube.com/v/<?php echo $Video->video_id; ?>">
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?> 


                                                    </div>
                                                </div>
                                            </div>
                                            <!--Gallery tab ends-->


                                            <!--Guest tab starts-->
                                            <?php if (count($arrGuests) > 0): ?>
                                                <div role="tabpanel" class="tab-pane" id="guest">
                                                    <div class="tabpanel-head">
                                                        <h2>Guest</h2>
                                                    </div>
                                                    <div class="tabpanel-body">

                                                        <?php foreach ($arrGuests as $Guest): ?>
                                                            <div class="guest-item">
                                                                <div class="row">
                                                                    <div class="col-md-3 col-sm-4 col-guest">
                                                                        <div class="guest-thumb">
                                                                            <?php if (file_exists($config['IMAGE_UPLOAD_PATH'] . '/guest/' . $Guest->EP_image) AND $Guest->EP_participant_name != ''): ?>
                                                                                <img class="img-responsive" src="<?php echo $config['IMAGE_UPLOAD_URL'] . '/guest/' . $Guest->EP_image; ?>" alt="<?php echo $Guest->EP_participant_name; ?>">
                                                                            <?php else: ?>
                                                                                <img class="img-responsive" src="<?php echo baseUrl() . 'upload/defaults/profile_default.jpg'; ?>" alt="<?php echo $Guest->EP_participant_name; ?>">
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-9 col-sm-8 col-guest">
                                                                        <h4><?php echo $Guest->EP_participant_name; ?></h4>
                                                                        <h5><?php echo $Guest->EP_current_position; ?></h5>
                                                                        <p><?php echo html_entity_decode($Guest->EP_details); ?></p>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>


                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <!--Guest tab ends-->


                                            <!--Terms & Condition tab-->
                                            <?php if ($eventTermsandCon != ''): ?>
                                                <div role="tabpanel" class="tab-pane" id="tc">
                                                    <div class="tabpanel-head">
                                                        <h2>Terms &amp; Conditions</h2>
                                                    </div>
                                                    <div class="tabpanel-body">
                                                        <?php echo html_entity_decode($eventTermsandCon); ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <!--Terms & Condition tab-->



                                            <!--FAQ tab-->
                                            <?php if (count($arrFAQ) > 0): ?>
                                                <div role="tabpanel" class="tab-pane" id="faq">
                                                    <div class="tabpanel-head">
                                                        <h2>FAQ</h2>
                                                    </div>
                                                    <div class="tabpanel-body">


                                                        <?php foreach ($arrFAQ AS $FAQs): ?>
                                                            <div class="panel-group faq-panel-hold" id="accordion_<?php echo $FAQs->EF_id; ?>" role="tablist" aria-multiselectable="true">
                                                                <div class="panel panel-default">
                                                                    <div class="panel-heading" role="tab" id="heading_<?php echo $FAQs->EF_id; ?>">
                                                                        <h4 class="panel-title">
                                                                            <a data-toggle="collapse" data-parent="#accordion_<?php echo $FAQs->EF_id; ?>" href="#collapse_<?php echo $FAQs->EF_id; ?>" aria-expanded="true" aria-controls="collapse_<?php echo $FAQs->EF_id; ?>">
                                                                                <?php echo $FAQs->EF_question; ?>
                                                                            </a>
                                                                        </h4>
                                                                    </div>
                                                                    <div id="collapse_<?php echo $FAQs->EF_id; ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading_<?php echo $FAQs->EF_id; ?>">
                                                                        <div class="panel-body">
                                                                            <?php echo $FAQs->EF_answer; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>


                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <!--FAQ tab-->


                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!--SUBSCRIBE TO OUR NEWSLETTERS DIV START HERE-->
                            <div class="col-md-4 right-siderbar">
                                <?php if ($relatedOffer != ""): ?>
                                    <div class="sidebar-events" style="padding:0;">
                                        <a href="javascript:void(0);"> <img src="<?php echo $config['IMAGE_UPLOAD_URL'] . '/SO_image/' . $relatedOffer; ?>" alt="Stay with us" class="img-responsive"></a>
                                    </div>
                                <?php else: ?>
                                    <div class="sidebar-events" style="padding:0;">
                                        <h4 class="text-center" style="padding: 10px;">No offers and promotion found.</h4>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <!--SUBSCRIBE TO OUR NEWSLETTERS DIV END HERE-->

                        </div>
                    </div>
                </div>


                <?php if (count($arrSimilarEvents) > 0): ?>
                    <hr class="site">
                    <div class="content-box">
                        <div class="titlebar clearfix">
                            <h3 class="pull-left">Similar Events</h3>
                            <div class="featured-navi pull-right"> <a class="prev"><i class="icon-left-open-big"></i></a> <a class="next"><i class=" icon-right-open-big"></i></a> </div>
                        </div>
                        <div class="content-row" style="margin-left: -15px; margin-right: -15px;">
                            <div id="similar-events" class="featured-row-movie owl-carousel owl-theme">
                                <?php foreach ($arrSimilarEvents AS $SimilarEvent): ?>

                                    <div class="event-item">
                                        <div class="inner similar-events-per-div" style="min-height: 220px;">
                                            <div class="item-img">
                                                <div class="overly">
                                                    <div class="dtable hw100">
                                                        <div class="dtable-cell hw100"> 
                                                            <a target="_blank" class="btn btn-primary"  href="<?php echo baseUrl(); ?>details/<?php echo $SimilarEvent->event_id; ?>/<?php echo clean($SimilarEvent->event_title); ?>"> Buy Ticket </a> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php if (file_exists($config['IMAGE_UPLOAD_PATH'] . '/event_web_logo/' . $SimilarEvent->event_web_logo)): ?>
                                                    <img src="<?php echo $config['IMAGE_UPLOAD_URL'] . '/event_web_logo/' . $SimilarEvent->event_web_logo; ?>" alt="<?php echo $SimilarEvent->event_title; ?>"> 
                                                <?php endif; ?> 
                                            </div>
                                            <div class="item-content">
                                                <h5><?php echo $SimilarEvent->event_title; ?></h5>
                                            </div>
                                        </div>
                                    </div>

                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div><!-- /.owl-wrapper-outer-->
                <?php endif; ?>

            </div>
        </div> <!--/.main-container-->



        <!-- Modal -->
        <div class="modal fade event-modal" id="evntModal" tabindex="-1" role="dialog" aria-labelledby="evntModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-seat modal-lg">
                <div class="modal-content">
                    <div class="col-md-12">
                        <div class="modal-content" id="modalContent">

                            <button type="button" class="btn btn-close" data-dismiss="modal"><i class="icon-cancel"></i> </button>

                            <div id="loaderScreen" style="width: 100% !important; background-color: white; display: none;">
                                <img src="<?php echo baseUrl(); ?>images/loader.gif" class="img-responsive" style="margin: 0 auto;">
                            </div>

                            <!--this div is for showing map image-->
                            <div  id="mainScreen" style="padding: 15px;">
                                <div class="modal-header">
                                    <h3 class="mo-title text-center" id="modalPlanTitle"></h3>

                                </div>
                                <div class="modal-body">
                                    <div class="sec-quantity" id="modalPlanContent">
                                        <!--Here modal plan content will be loaded-->
                                    </div>
                                </div>
                                <div class="modal-footer clearfix">
                                    <!--Here modal footer content will be loaded-->
                                </div>
                            </div>


                            <!--this span is for showing seat map-->
                            <div id="showSeat" style="display: none; padding: 15px;">
                                <table class="table table-emodal">
                                    <tr>
                                        <td>
                                            <a href="javascript:void(0);" onclick="javascript:seatPlanGoBack();" class="btn btn-primary"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Go Back</a>
                                        </td>
                                        <td colspan="2"></td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="pull-left text-center">Quantity : &nbsp;&nbsp;</span>
                                            <div id="showUserLimit" class="pull-left">

                                            </div>
                                        </td>
                                        <td>Total:<strong><?php echo $config['CURRENCY_SIGN']; ?> <span id="txtTotalPrice">0.00</span></strong></td>
                                        <td><a href="javascript:void(0);" onclick="javascript:goCheckout();" class="btn btn-primary">Proceed to checkout</a> </td>

                                    </tr>
                                </table>
                                <div class="sec-quantity">
                                    <span class="screen-way" data-dismiss="modal" style="font-size: larger;">
                                        <i class="fa fa-arrow-circle-up"></i>&nbsp;&nbsp;<strong>SCREEN THIS WAY</strong>
                                    </span>
                                    <div class="seat-row row-info">
                                        <ul>
                                            <li><a class="seatTooltip available"></a> Available Seat</li>
                                            <li><a class="seatTooltip selected"></a> Selected Seat</li>
                                            <li><a class="seatTooltip bookedSeat"></a> Booked Seat</li>
                                        </ul>
                                    </div>
                                    <!-- style="overflow-x: scroll; overflow-y: scroll; height: 450px; width: auto !important;" -->
                                    <div class="seat-wrapper" id="divShowSeat">

                                    </div>


                                </div>
                            </div>
                            <input type="hidden" value="" id="seatSelected">
                            <input type="hidden" value="" id="quntySelected">
                            <input type="hidden" value="" id="inputTotalPrice">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->




        <?php include basePath('social_link.php'); ?>
        <?php include basePath('footer.php'); ?>
        <?php include basePath('login_modal.php'); ?>
        <?php include basePath('signup_modal.php'); ?>
        <?php include basePath('checkout_popup.php'); ?>
        <?php include basePath('footer_script.php'); ?>
        <!-- From Event Details Page -->

        <script async src="<?php echo baseUrl('js/jquery.form.min.js'); ?>"></script>
        <script src="<?php echo baseUrl('js/jquery.magnific-popup.min.js'); ?>"></script>
        <script src="<?php echo baseUrl('js/bootstrap-datetimepicker.min.js'); ?>"></script>
        <!-- From Event Details Page -->
        <script>
                                            $(document).ready(function () {
                                                $('.gall-item').magnificPopup({
                                                    type: 'image',
                                                    gallery: {
                                                        enabled: true
                                                    }
                                                });
                                            });</script>




        <script src="<?php echo baseUrl('js/script.js'); ?>"></script>



        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>

        <script>



                                            function initialize() {

                                                var locations = [
<?php
$countRow = 0;
$totalLat = 0;
$totalLon = 0;
?>
<?php foreach ($arrEventVenues as $Venues): ?>
    <?php $arrLatLon = explode(',', $Venues->venue_geo_location); ?>
                                                    ['<strong><h3><?php echo $Venues->venue_title; ?></h3></strong><p><?php echo $Venues->venue_address; ?></p>', <?php echo $arrLatLon[0]; ?>, <?php echo $arrLatLon[1]; ?>, 4],
    <?php
    $totalLat += $arrLatLon[0];
    $totalLon += $arrLatLon[1];
    ?>
    <?php $countRow++; ?>
<?php endforeach; ?>
                                                ];
<?php
$avgLat = 0;
$avgLon = 0;
if ($countRow > 0) {
    $avgLat = $totalLat / $countRow;
    $avgLon = $totalLon / $countRow;
}
?>

                                                var map = new google.maps.Map(document.getElementById('custom-map'), {
                                                    zoom: 16,
                                                    center: new google.maps.LatLng(<?php echo $avgLat; ?>, <?php echo $avgLon; ?>),
                                                    mapTypeId: google.maps.MapTypeId.ROADMAP
                                                });

                                                var infowindow = new google.maps.InfoWindow();

                                                var marker, i;

                                                for (i = 0; i < locations.length; i++) {
                                                    marker = new google.maps.Marker({
                                                        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                                                        map: map
                                                    });

                                                    google.maps.event.addListener(marker, 'click', (function (marker, i) {
                                                        return function () {
                                                            infowindow.setContent(locations[i][0]);
                                                            infowindow.open(map, marker);
                                                        }
                                                    })(marker, i));
                                                }
                                            }

                                            $(document).ready(function () {
                                                $("#venue").click(function () {
                                                    initialize();
                                                });
                                            });


        </script>

    </body>
</html>