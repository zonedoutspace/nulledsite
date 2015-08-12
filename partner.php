<?php
$partnerArray = array();
$sqlPartner = "SELECT partner_name,partner_image,partner_link FROM partner ORDER BY partner_id DESC";
$resultPartner = mysqli_query($con, $sqlPartner);
if ($resultPartner) {
    while ($resultPartnerObj = mysqli_fetch_object($resultPartner)) {
        $partnerArray[] = $resultPartnerObj;
    }
} else {
    if (DEBUG) {
        $err = "resultPartner error: " . mysqli_error($con);
    } else {
        $err = "resultPartner query failed";
    }
}
//debug($partnerArray);
?>
<?php if (count($partnerArray) > 0) : ?>
<div class="container">
    <div class="sec-header sec-header-home">
        <h2 class="wow fadeInDown" data-wow-duration="1s" data-wow-delay="0.15s"> Our Partners</h2>
        <p class="wow fadeInDown" data-wow-duration="1s" data-wow-delay="0.25s">
            Our partners who supports us
        </p>
    </div>
    <div style="clear:both"></div>
    <div class="row row-partner">
        <div class="row-partner-inner text-center wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.45s">
            
                <?php foreach ($partnerArray AS $partner) : ?>
                    <div class="col-parnter">
                        <div class="col-partner-in"> 
                            <?php if ($partner->partner_link != "") { ?>
                                <a target="_blank" href="<?php echo $partner->partner_link; ?>">
                                    <img alt="Partner Logo" src="<?php echo $config['IMAGE_UPLOAD_URL'] . '/partner_image/' . $partner->partner_image; ?>"> 
                                </a>
                            <?php } else { ?>
                                <a href="javascript:void(0);">
                                    <img alt="Partner Logo" src="<?php echo $config['IMAGE_UPLOAD_URL'] . '/partner_image/' . $partner->partner_image; ?>"> 
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            
        </div>
    </div>
</div>
<?php endif; ?>