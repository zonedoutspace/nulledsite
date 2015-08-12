<!-- Modal -->
<div class="modal sign-modal fade" id="signup" tabindex="-1" role="dialog" aria-labelledby="signupLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <button type="button" class="btn btn-close" data-dismiss="modal"><i class="icon-cancel"></i> </button>
            <div class="sign-box-holder-modal">
                <div class="sign-box">
                    <div class="sign-box-header">
                        <h2>Create Account</h2>
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
                                <input type="test" id="user_first_name" name="user_first_name" placeholder="Your Name" class="form-control">
                            </div>

                            <div class="form-group-icon">
                                <label><i class="fa fa-envelope-o"></i></label>
                                <input type="email" id="signup_user_email" name="user_email" class="form-control" placeholder="Email Address">
                            </div>
                            <div class="form-group-icon">
                                <label><i class="fa fa-lock"></i></label>
                                <input type="password" id="signup_user_password" name="user_password" class="form-control" placeholder="Password">
                            </div>
                            <div class="form-group-icon">
                                <label><i class="fa fa-lock"></i></label>
                                <input type="password" id="signup_user_confirm_password" name="signup_user_confirm_password" class="form-control" placeholder="Confirm Password">
                            </div>
                            <div>
                                <input type="checkbox" id="AcceptTerms" name="AcceptTerms"/> Accept TicketChai <a target="_blank" href="<?php echo baseUrl("terms-of-service"); ?>">Terms And Conditions</a>
                            </div>
                            <br/>
                            <button type="button" id="clickSignUp" class="btn btn-primary btn-lg btn-block btn-signup">Create Account</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


