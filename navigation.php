<?php
include "lib/category2.php";
//Getting root category from database
$arrRootCatNav = array();
$arrRootCatNavID = array();
$strRootCatNavID = '';
$sqlRootCatNav = "SELECT category_color,category_id,category_title,category_parent_id,category_priority"
        . " FROM categories WHERE category_parent_id=0 ORDER BY category_priority DESC";
$resultRootCatNav = mysqli_query($con, $sqlRootCatNav);
if ($resultRootCatNav) {
    while ($resultRootCatNavObj = mysqli_fetch_object($resultRootCatNav)) {
        $arrRootCatNav[] = $resultRootCatNavObj;
        $arrRootCatNavID[] = $resultRootCatNavObj->category_id;
    }
} else {
    if (DEBUG) {
        $err = "resultRootCatNav error: " . mysqli_error($con);
    } else {
        $err = "resultRootCatNav query failed.";
    }
}



//getting all featured events from db
$arrAllFeatured = array();
$sqlGetAllFeatured = "SELECT event_id,event_category_id,event_title,event_web_logo FROM events WHERE event_is_featured='yes' AND event_status='active' ORDER BY event_featured_priority DESC";
$resultGetAllFeatured = mysqli_query($con, $sqlGetAllFeatured);
if ($resultGetAllFeatured) {
    while ($resultGetAllFeaturedObj = mysqli_fetch_array($resultGetAllFeatured)) {
        $arrAllFeatured[] = $resultGetAllFeaturedObj;
        $arrCat = explode(',', $resultGetAllFeaturedObj['event_category_id']);
        $arrAllFeatured[(count($arrAllFeatured) - 1)]['event_category_id_array'] = $arrCat;
    }
} else {
    if (DEBUG) {
        $err = "resultGetAllFeatured error: " . mysqli_error($con);
    } else {
        $err = "resultGetAllFeatured query failed.";
    }
}


//getting all featured events from db
$arrAllUpcoming = array();
$sqlGetAllUpcoming = "SELECT event_id,event_category_id,event_title,event_web_logo FROM events WHERE event_is_coming='yes' AND event_status='active' ORDER BY event_coming_priority DESC";
$resultGetAllUpcoming = mysqli_query($con, $sqlGetAllUpcoming);
if ($resultGetAllUpcoming) {
    while ($resultGetAllUpcomingObj = mysqli_fetch_array($resultGetAllUpcoming)) {
        $arrAllUpcoming[] = $resultGetAllUpcomingObj;
        $arrCat = explode(',', $resultGetAllUpcomingObj['event_category_id']);
        $arrAllUpcoming[(count($arrAllUpcoming) - 1)]['event_category_id_array'] = $arrCat;
    }
} else {
    if (DEBUG) {
        $err = "resultGetAllUpcoming error: " . mysqli_error($con);
    } else {
        $err = "resultGetAllUpcoming query failed.";
    }
}

//debug($arrAllUpcoming)
?>
<div style="clear:both"></div>
<div class="header-nav">
    <div class="navbar navbar-default navbar-site  navbar-static-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                <a class="navbar-brand" href="<?php echo baseUrl('home'); ?>"><img src="<?php echo baseUrl('images/ticketchai_logo.png'); ?>" alt="ticketchai"> </a> </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-main">
                    <li><a href="<?php echo baseUrl(); ?>home">Home</a> </li>

                    <?php
                    $arrAllChildCat = array();
                    ?>
                    <?php if (count($arrRootCatNav) > 0): ?>
                        <?php foreach ($arrRootCatNav AS $Nav): ?>       
                            <li class="dropdown menu-large"> 
                                <a href="javascript:void(0);" class="dropdown-toggle hidden-xs hidden-sm" data-toggle="dropdown"> <?php echo $Nav->category_title; ?> </a>
                                <a href="<?php echo baseUrl(); ?>category/<?php echo $Nav->category_id; ?>/<?php echo clean($Nav->category_title); ?>" class="hidden-md hidden-lg"> <?php echo $Nav->category_title; ?> </a>
                                <ul class="dropdown-menu megamenu ">
                                    <li>
                                        <div class="container hidden-xs hidden-sm">
                                            <?php
                                            $libCat = new Category2($con);
                                            $arrAllChildCat = $libCat->getChildsFromTreeArray($Nav->category_id);
                                            ?>


                                            <div class="row">
                                                <?php
                                                $countFeatured = 0;
                                                if (count($arrAllFeatured) > 0) {
                                                    foreach ($arrAllFeatured AS $Featured) {
                                                        if ($countFeatured < 2) {
                                                            if (in_array($Nav->category_id, $Featured['event_category_id_array'])) {
                                                                $countFeatured++;
                                                                ?>
                                                                <div class="col-sm-3 no-padding-left">
                                                                    <ul>
                                                                        <li>
                                                                            <a href="<?php echo baseUrl(); ?>details/<?php echo $Featured['event_id']; ?>/<?php echo clean($Featured['event_title']); ?>"> 
                                                                                <img title="<?php echo $Featured['event_title']; ?>" alt="<?php echo $Featured['event_title']; ?>" src="<?php echo baseUrl(); ?>upload/event_web_logo/<?php echo $Featured['event_web_logo']; ?>" class="img-responsive">
                                                                            </a> 
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                                <?php
                                                            }
                                                        }
                                                    }
                                                }
                                                ?>

                                                <?php if ($countFeatured == 1): ?>
                                                    <div class="col-sm-3 no-padding-left">
                                                        <ul>
                                                            <li>
                                                                <h4 class="text-center">No more featured event found.</h4>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if ($countFeatured == 0): ?>
                                                    <div class="col-sm-6 no-padding-left">
                                                        <ul>
                                                            <li>
                                                                <h4 class="text-center">No featured event found.</h4>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                <?php endif; ?>

                                                <div class="col-sm-2">
                                                    <ul>
                                                        <li class="dropdown-header">Top Categories</li>
                                                        <?php if (count($arrAllChildCat) > 0): ?>
                                                            <?php foreach ($arrAllChildCat AS $allChild): ?>
                                                                <li><a href="<?php echo baseUrl(); ?>category/<?php echo $allChild['category_id']; ?>/<?php echo clean($allChild['category_title']); ?>"><?php echo $allChild['category_title']; ?> </a></li>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <li>No top category found.</li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                                <div class="col-sm-4">
                                                    <ul class="navevent">
                                                        <li class="dropdown-header">Upcoming Events</li>
                                                        <?php
                                                        $countUpcoming = 0;
                                                        if (count($arrAllUpcoming) > 0) {
                                                            foreach ($arrAllUpcoming AS $Upcoming) {
                                                                if ($countUpcoming < 3) {
                                                                    if (in_array($Nav->category_id, $Upcoming['event_category_id_array'])) {
                                                                        $countUpcoming++;
                                                                        ?>
                                                                        <li>
                                                                            <a class="" href="<?php echo baseUrl(); ?>details/<?php echo $Upcoming['event_id']; ?>/<?php echo clean($Upcoming['event_title']); ?>" > 
                                                                                <img class="img-responsive" src="<?php echo baseUrl(); ?>upload/event_web_logo/<?php echo $Upcoming['event_web_logo']; ?>" alt="<?php echo $Upcoming['event_title']; ?>"> 
                                                                                <span class="m-event-title"><?php echo $Upcoming['event_title']; ?> </span>
                                                                            </a>
                                                                        </li>
                                                                        <?php
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        ?>

                                                        <?php if ($countFeatured == 0): ?>
                                                            <li>
                                                                <h5>No upcoming event found.</h5>
                                                            </li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
                <!-- /.main menu  -->
                <?php if (basename($_SERVER['PHP_SELF']) != "cart.php"): ?>
                    <?php include basePath('popup_cart.php'); ?>
                <?php endif; ?>
                <!-- /.navbar-right --> 
            </div>
        </div>
    </div>
</div>