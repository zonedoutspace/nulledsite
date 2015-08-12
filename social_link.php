<section class="social-links">
    <div class="container-fluid max-width">
        <div class="row">
            <div class="col-md-12 social_media"> 
                <?php if (getConfig("FOOTER_FACEBOOK_LINK") != ""): ?>
                    <a target="_blank" class="fb" title="Facebook" href="<?php echo getConfig("FOOTER_FACEBOOK_LINK"); ?>" rel="nofollow"><i class="fa fa-facebook"></i><span>Facebook</span></a>
                <?php endif; ?>
                <?php if (getConfig("FOOTER_TWITTER_LINK") != ""): ?>  
                    <a target="_blank" class="fb" title="Twitter" href="<?php echo getConfig("FOOTER_TWITTER_LINK"); ?>" rel="nofollow"><i class="fa fa-twitter"></i><span>Twitter</span></a> 
                <?php endif; ?>
                <?php if (getConfig("FOOTER_INSTGRAM_LINK") != ""): ?>   
                    <a target="_blank" class="fb" title="Instagram" href="<?php echo getConfig("FOOTER_INSTGRAM_LINK"); ?>" rel="nofollow"><i class="fa fa-instagram"></i><span>Instagram</span></a> 
                <?php endif; ?>
                <?php if (getConfig("FOOTER_LINKEDIN_LINK") != ""): ?>
                    <a target="_blank" class="fb" title="LinkedIn" href="<?php echo getConfig("FOOTER_LINKEDIN_LINK"); ?>" rel="nofollow"><i class="fa fa-linkedin"></i><span>LinkedIn</span></a> 
                <?php endif; ?>
                <?php if (getConfig("FOOTER_GOOGLEPLUS_LINK") != ""): ?>  
                    <a target="_blank" class="fb" title="Google+" href="<?php echo getConfig("FOOTER_GOOGLEPLUS_LINK"); ?>" rel="nofollow"><i class="fa fa-google-plus"></i><span>Google+</span></a> 
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>