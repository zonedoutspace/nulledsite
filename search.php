<?php
include './config/config.php';
$Key = "";
$countResult = 0;
$searchArray = array();
if (isset($_GET['key'])) {
    $Key = trim($_GET['key']);
    $KeyArray = explode(' ', $Key);
//    debug($KeyArray);

    $sqlSearch = "SELECT events.event_id,events.event_title,events.event_category_id,events.event_description,"
            . " events.event_web_logo,categories.category_title,event_venues.venue_title,"
            . " event_venues.venue_address,event_venues.venue_start_date "
            . " FROM events "
            . " LEFT JOIN categories ON events.event_category_id = categories.category_id"
            . " LEFT JOIN event_venues ON event_venues.venue_event_id = events.event_id"
            . " WHERE events.event_status='active' AND "
            . " (events.event_title LIKE '%" . mysqli_real_escape_string($con, $Key) . "%'";
    for ($i = 0; $i < count($KeyArray); $i++) {
        $k = $KeyArray[$i];
        $sqlSearch .= " OR events.event_tag LIKE '%" . mysqli_real_escape_string($con, $k) . "%'";
        $sqlSearch .= " OR events.event_description LIKE '%" . mysqli_real_escape_string($con, $k) . "%'";
        $sqlSearch .= " OR categories.category_title LIKE '%" . mysqli_real_escape_string($con, $k) . "%' ";
        $sqlSearch .= " OR event_venues.venue_title LIKE '%" . mysqli_real_escape_string($con, $k) . "%' ";
        $sqlSearch .= " OR event_venues.venue_address LIKE '%" . mysqli_real_escape_string($con, $k) . "%' ";
    }
    $sqlSearch .= ")";
//    echo $sqlSearch;

    $resultSearch = mysqli_query($con, $sqlSearch);
    if ($resultSearch) {
        $countResult = mysqli_num_rows($resultSearch);
        while ($resultSearchObj = mysqli_fetch_object($resultSearch)) {
            $searchArray[] = $resultSearchObj;
        }
    } else {
        if (DEBUG) {
            echo "resultSearch error: " . mysqli_error($con);
        } else {
            echo "resultSearch query failed.";
        }
    }
//    debug($searchArray);
}
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
            <div class="container">
                <div class="content-box">
                    <div class="content-row">
                        <div class="content-row row">
                            <div class="col-md-8 col-sm-8 left-siderbar">
                                <div class="category-list">
                                    <div class="search-title">
                                        <h1><strong><?php echo $countResult; ?></strong> matching <strong>for "<?php echo $Key; ?>"</strong> </h1>
                                    </div>
<!--                                    <div class="tab-box">
                                        <div class="search-head">
                                            <h4><i class="fa fa-search"></i> Modify your search…
                                                <a class="pull-right" data-toggle="collapse" href="#collapseSearch" aria-expanded="false" aria-controls="collapseSearch"><i class="fa fa-plus-square"></i> </a>
                                            </h4>
                                        </div>
                                        <div class="collapse in" id="collapseSearch">
                                            <div class="search-body">
                                                <form>
                                                    <div class="row">
                                                        <div class="col-md-3 col-sm-3">
                                                            <label>Date</label>
                                                            <select class="form-control">
                                                                <option value="">All future</option>
                                                                <option value="">Today</option>
                                                                <option value="">Tomorrow</option>
                                                                <option value="">This weekend (Fri-Sun)</option>
                                                                <option value="">Next 7 days</option>
                                                                <option value="">Next 30 days</option>
                                                                <option>Past</option>
                                                                <option>Pick your dates</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3 col-sm-3">
                                                            <label>Sort by</label>
                                                            <select class="form-control">
                                                                <option value="">Popularity</option>
                                                                <option value="">Relevance</option>
                                                                <option value="">Date</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3 col-sm-3">
                                                            <label>Limit to</label>
                                                            <select class="form-control">
                                                                <option value="">All events</option>
                                                                <option value="">Concerts</option>
                                                                <option value="">Conferences</option>
                                                                <option value="">Comedy</option>
                                                                <option value="">Education</option>
                                                                <option value="">Festivals</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3 col-sm-3">
                                                            <label>Neighborhood</label>
                                                            <select class="form-control">
                                                                <option value="">All neighborhoods</option>
                                                                <option value="">Adams Shore</option>
                                                                <option value="">Addison-Orange</option>
                                                                <option value="">Admirals Hill</option>
                                                                <option value="">Agganis AC</option>
                                                                <option value="">Aggasiz - Harvard University</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                    </div>-->
                                    <!--/.tab-box-->

                                    <!-- Search Result Div Start Here -->
                                    <?php if (count($searchArray) > 0): ?>
                                        <?php foreach ($searchArray AS $search) : ?>
                                            <div class="adds-wrapper">
                                                <div class="item-list">
                                                    <div class="col-sm-2 no-padding photobox">
                                                        <div class="item-list-image">  
                                                            <a href="<?php echo baseUrl(); ?>details/<?php echo $search->event_id; ?>/<?php echo clean($search->event_title); ?>">
                                                                <img class="thumbnail no-margin" src="<?php echo $config['IMAGE_UPLOAD_URL'] . '/event_web_logo/' . $search->event_web_logo; ?>" alt="<?php echo $search->event_title; ?>">
                                                            </a> 
                                                        </div>
                                                    </div>
                                                    <!--/.photobox-->
                                                    <div class="col-sm-7 eventlist-desc-box">
                                                        <div class="eventlist-details">
                                                            <h4 class="add-title"> 
                                                                <a href="<?php echo baseUrl(); ?>details/<?php echo $search->event_id; ?>/<?php echo clean($search->event_title); ?>"> 
                                                                    <?php echo $search->event_title; ?> 
                                                                </a>
                                                            </h4>
                                                            <span class="info-row">
                                                                <span class="date"><i class=" icon-clock"></i>
                                                                    <?php
                                                                    $dayName = $search->venue_start_date;
                                                                    echo $day = date("l, d F, Y", strtotime($dayName));
                                                                    ?>&nbsp;&nbsp;
                                                                </span>
                                                                <span class="item-location">&nbsp;&nbsp;
                                                                    <i class="fa fa-map-marker"></i>&nbsp;&nbsp;
                                                                    <?php echo $search->venue_title; ?>
                                                                </span> 
                                                            </span> 
                                                            <a class="mini-link" href="<?php echo baseUrl(); ?>details/<?php echo $search->event_id; ?>/<?php echo clean($search->event_title); ?>">More info <i class="fa fa-caret-right"></i> </a>
                                                        </div>
                                                    </div>
                                                    <!--/.eventlist-desc-box-->
                                                    <div class="col-sm-3 text-right  price-box">
                                                        <a href="<?php echo baseUrl(); ?>details/<?php echo $search->event_id; ?>/<?php echo clean($search->event_title); ?>" class="btn btn-primary  btn-sm"> 
                                                            BUY TICKET <i class="fa fa-ticket"></i> 
                                                        </a>
                                                        <a id="wishlist_<?php echo $search->event_id; ?>" onclick="javascript:addToWishlist(<?php echo $search->event_id; ?>, 'event');" class="btn btn-default  btn-sm make-favorite" title="Add to Wishlist"><i class="fa fa-heart"></i></a>

                                                    </div>
                                                    <!--/.eventlist-desc-box--> 
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                    <h3 class="text-center" style="height: 50px;margin-top: 10px;">No matching found in the record.</h3>
                                    <?php endif; ?>
                                    <!-- Search Result Div End Here -->

                                    <!-- Pagination Div Start Here -->
                                    <?php if ($countResult > 5): ?>
                                        <div class="text-center">
                                            <nav class="paginationNav">
                                                <ul class="pagination">
                                                    <li class="disabled"><a href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
                                                    <li class="active"><a href="#">1 <span class="sr-only">(current)</span></a></li>
                                                    <li><a href="#">2</a></li>
                                                    <li><a href="#">3</a></li>
                                                    <li><a href="#">4</a></li>
                                                    <li><a href="#">5</a></li>
                                                    <li>
                                                        <a href="#" aria-label="Next">
                                                            <span aria-hidden="true">&raquo;</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                    <?php endif; ?>
                                    <!-- Pagination Div Start Here -->
                                </div>
                            </div>
                            <!--left-siderbar-->

                            <div class="col-md-4 col-sm-4 right-siderbar">
                                
                                
                            </div>
                            <!--right-siderbar--> 
                        </div>
                        <!--/.featured-row--> 
                    </div>
                    <!--/.content-row--> 
                </div>
                <!--/.content-box-->
            </div>
        </div><!-- main-container-->
        <?php include basePath('social_link.php'); ?>
        <?php include basePath('footer.php'); ?>
        <?php include basePath('login_modal.php'); ?>
        <?php include basePath('signup_modal.php'); ?>
        <?php include basePath('footer_script.php'); ?>


    </body>
</html>