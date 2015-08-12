<?php include './ajax/facebookLogin.php'; ?>
<?php include './ajax/googleLogin.php'; ?>


<div class="navbar-top">
    <div class="container center-block no-padding">
        <ul class="info pull-left">
             <?php if (getConfig("HEADER_EMAIL_ADDRESS")): ?>
            <li>
                <a href="#"><i class="icon-mail contact"></i>
                    <?php echo getConfig("HEADER_EMAIL_ADDRESS"); ?>
                </a>
            </li>
             <?php endif; ?>
            
            <?php if (getConfig("HEADER_PHONE_NUMBER")): ?>
                <li>
                    <i class="icon-mobile contact"></i>
                    <?php echo getConfig("HEADER_PHONE_NUMBER"); ?>
                </li>
            <?php endif; ?>
        </ul>
        <ul class="info pull-right   ">
            <?php if (checkUserLogin()): ?>
                <li>
                    <span id="login_form"><a  href="<?php echo baseUrl(); ?>account"><i class="fa fa-user"></i>&nbsp;<?php echo getSession("user_first_name"); ?></a></span> 
                </li>
                <li>
                    <span id="register_form"><a href="javascript:void();" onclick="javascript:userLogout();"><i class="fa fa-sign-out"></i>&nbsp;Logout</a></span>
                </li> 
            <?php else: ?>  
                <li>
                    <span id="login_form"><a href="javascript:void(0);" data-toggle="modal" data-target="#login" id="anchorSigninPopup"><i class="fa fa-sign-in"></i>&nbsp;Login</a></span>
                </li>
                <li>
                    <span id="register_form"> <a href="javascript:void(0);" data-toggle="modal" data-target="#signup"><i class="fa fa-user-plus"></i>&nbsp;Signup</a></span>
                </li>
            <?php endif; ?>    
        </ul>
    </div>
</div>