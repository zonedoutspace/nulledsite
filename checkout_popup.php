<div class="modal  product-notify fade   zoom" id="notify" tabindex="-1" role="dialog" aria-labelledby="loginLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <button type="button" class="btn btn-close" data-dismiss="modal"><i class="icon-cancel"></i> </button>
            <div class="item-list">
                <div class="col-sm-3 no-padding photobox">
                    <div class="item-list-image">
                        <?php if (file_exists($config['IMAGE_UPLOAD_PATH'] . '/event_web_logo/' . $eventWebLogo) AND $eventWebLogo != ''): ?>
                            <img class="thumbnail no-margin img-responsive" src="<?php echo $config['IMAGE_UPLOAD_URL'] . '/event_web_logo/' . $eventWebLogo; ?>" alt="<?php echo $eventTitle; ?>">
                        <?php endif; ?>  
                    </div>
                </div>
                <!--/.photobox-->
                <div class="col-sm-9 eventlist-desc-box">
                    <div class="eventlist-details">
                        <h4 class="add-title"><?php echo $eventTitle; ?></h4>
                    </div>
                    <!--/.eventlist-desc-box-->
                </div>
                <div class="clearfix"></div><br>
                <div class="col-sm-12 alert alert-success" role="alert">
                    <i class="icon-ok-circled"></i> <div style="width: 100% !important; margin-top: 15px;">item added to your shopping cart.</div>
                </div>
            </div><!--/.item-list-->
            <div class="modal-footer">
                <div class="merged-buttons"> 
                    <a data-dismiss="modal" class="btn btn-primary"><i class="fa fa-shopping-cart"></i>&nbsp;&nbsp;Continue Shopping</a> 
                    <a href="<?php echo baseUrl() . 'checkout-step-one'; ?>" class="btn btn-inverse "><i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Let's Checkout</a> </div>
            </div>
        </div>
    </div>
</div>