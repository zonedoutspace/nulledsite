<!-- Modal -->
<div class="modal sign-modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="loginLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <button type="button" class="btn btn-close" data-dismiss="modal"><i class="icon-cancel"></i> </button>
            <div class="sign-box-holder-modal">
                <div class="sign-box">
                    <div class="sign-box-header">
                        <h1>Login to Ticketchai</h1>
                    </div>
                    <div class="sign-box-body">
                        <div class="social-btn-holder">
                            <a href="javascript:void(0);" onclick="facebookLogin();" class="btn btn-lg btn-block btn-facebook" style="width: 45%; float: left; margin: 5px;">
                                <i class="fa fa-facebook"></i>&nbsp;&nbsp;&nbsp;&nbsp;Facebook
                            </a> 
                            <a href="javascript:void(0);" onclick="googleLogin();" style="background-color: #dd4b39; width: 45%; float: left; margin: 5px;" class="btn btn-lg btn-block btn-facebook">
                                <i class="fa fa-google-plus"></i>&nbsp;&nbsp;&nbsp;&nbsp;Google+
                            </a>
                            <div class="clearfix"></div>
                            <div class="sign-hr"> <span>OR</span> </div>
                        </div>
                        <form class="sign-form">
                            <div class="form-group-icon">
                                <label><i class="fa fa-user"></i></label>
                                <input type="email" id="login_user_email" name="user_email" class="form-control" placeholder="Email Address">
                            </div>
                            <div class="form-group-icon">
                                <label><i class="fa fa-lock"></i></label>
                                <input type="password" id="login_user_password" name="user_password" class="form-control" placeholder="Password">
                            </div>
                            <a href="javascript:void(0);" id="clickLogin" class="btn btn-primary btn-lg btn-block btn-login">Login</a>
                        </form>
                        <div class="remind-bar clearfix">
                            <div class="row">
                                <div class="col-md-6 col-sm-6"> <a href="<?php echo baseUrl('forget_password.php'); ?>">Lost your password?</a> </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


