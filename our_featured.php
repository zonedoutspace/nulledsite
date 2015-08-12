<?php
$clientArray = array();
$sqlClients = "SELECT clients_image,clients_link,clients_name FROM clients ORDER BY clients_id DESC";
$resultClient = mysqli_query($con, $sqlClients);
if ($resultClient) {
    while ($resultClientObj = mysqli_fetch_object($resultClient)) {
        $clientArray[] = $resultClientObj;
    }
} else {
    if (DEBUG) {
        $err = "resultClient error: " . mysqli_error($con);
    } else {
        $err = "resultClient query failed";
    }
}
//debug($clientArray);
?>
<?php if (count($clientArray) > 0) : ?>
<div class="section-padd-divider"></div>
<div class="container">
    <div class="sec-header sec-header-home">
        <h2 class="wow fadeInDown" data-wow-duration="1s" data-wow-delay="0.15s"> Our Clients</h2>
        <p class="wow fadeInDown" data-wow-duration="1s" data-wow-delay="0.25s">
            Our clients who hosted their events through our platform
        </p>
    </div>
    <div style="clear:both"></div>

    <div class="row row-partner">
        <div class="row-partner-inner text-center wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.45s">
            
                <?php foreach ($clientArray AS $ClientImage) : ?>
                    <div class="col-parnter">
                        <div class="col-partner-in"> 
                            <?php if($ClientImage->clients_link != ""){ ?>
                            <a target="_blank" href="<?php echo $ClientImage->clients_link; ?>">
                                <img alt="<?php echo $ClientImage->clients_name; ?>" title="<?php echo $ClientImage->clients_name; ?>" src="<?php echo $config['IMAGE_UPLOAD_URL'] . '/clients_image/' . $ClientImage->clients_image; ?>"> 
                            </a>
                            <?php }else{ ?>
                            <a href="javascript:void(0);">
                                <img alt="<?php echo $ClientImage->clients_name; ?>" title="<?php echo $ClientImage->clients_name; ?>" src="<?php echo $config['IMAGE_UPLOAD_URL'] . '/clients_image/' . $ClientImage->clients_image; ?>"> 
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            
        </div> 
    </div>
</div>
<?php endif; ?>