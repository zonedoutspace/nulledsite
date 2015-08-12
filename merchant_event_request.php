<?php
include "config/config.php";
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



        <div class="main-container page-container section-padd">

            <div class="container">
                <div class="row">

                    <div class="col-md-12 col-sm-6 col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-heading clearfix">
                                <!--<i class="icon-calendar"></i>-->
                                <h3 class="panel-title">About You</h3>
                                <p>These details will not be displayed on your event listing</p>
                            </div>
                            <div class="panel-body">
                                <form class="form-horizontal row-border" action="#">


                                    <div class="form-group">
                                        <label class="col-md-3 control-label">First Name</label>
                                        <div class="col-md-6">
                                            <input id="MI_first_name" name="MI_first_name" class="form-control" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Last Name</label>
                                        <div class="col-md-6">
                                            <input id="MI_last_name" name="MI_last_name" class="form-control" type="text">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Email Address</label>
                                        <div class="col-md-6">
                                            <input id="MI_email_address" name="MI_email_address" class="form-control" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Address</label>
                                        <div class="col-md-6">
                                            <input id="MI_address" name="MI_address" class="form-control" type="text">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Phone Number</label>
                                        <div class="col-md-6">
                                            <input id="MI_number" name="MI_number" class="form-control" type="text">
                                        </div>
                                    </div>
                                   
                                </form>
                            </div>

                            <div class="panel-heading clearfix">
                                <!--<i class="icon-calendar"></i>-->
                                <h3 class="panel-title">About The Event</h3>
                            </div>
                            <div class="panel-body">
                                <form class="form-horizontal row-border" action="#">


                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Event Title</label>
                                        <div class="col-md-6">
                                            <input id="MI_event_title" name="MI_event_title" class="form-control" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Event Date And Time</label>
                                        <div class="col-md-6">
                                            <input id="MI_event_date_time" name="MI_event_date_time" class="form-control" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group">

                                        <label class="col-md-3 control-label">Is Close Group Event</label>
                                        <div class="col-md-3">
                                            <input id="MI_is_closed_event_check" name="MI_is_closed_event_check" type="checkbox">
                                            <input type="hidden" name="MI_is_closed_event" id="MI_is_closed_event" value="no">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Event Description</label>
                                        <div class="col-md-6">
                                            <textarea id="MI_event_description" name="MI_event_description" class="form-control" style="height: 250px;" cols="20" raw="2" type="text"></textarea>
                                        </div>
                                    </div>


                                </form>
                            </div>

                            <div class="panel-body">
                                <form class="form-horizontal row-border" action="#">

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Ticket Description</label>
                                        <div class="col-md-6">
                                            <textarea id="MI_about_ticket" name="MI_about_ticket" class="form-control" cols="20" raw="2" type="text"></textarea>
                                        </div>
                                    </div>


                                </form>
                            </div>

                            <div class="panel-heading clearfix">
                                <!--<i class="icon-calendar"></i>-->
                                <h3 class="panel-title">About The Venue</h3>
                            </div>
                            <div class="panel-body">
                                <form class="form-horizontal row-border">

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Venue Name</label>
                                        <div class="col-md-6">
                                            <input id="MI_venue_name" name="MI_venue_name" class="form-control" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Venue Address</label>
                                        <div class="col-md-6">
                                            <input id="MI_venue_address" name="MI_venue_address" class="form-control" type="text">
                                        </div>
                                    </div>                                
                                    <div class="form-group text-center">
                                        <button type="button"  class="btn btn-success" return="false" id="btnSaveEventRequest" name="btnSaveEventRequest">SEND</button>
                                        
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <!--/.container--> 

        </div>
        <!--/.main-container-->


        <?php include basePath('footer.php'); ?>
        <?php include basePath('login_modal.php'); ?>
        <?php include basePath('signup_modal.php'); ?>
        <?php include basePath('footer_script.php'); ?>
    </body>
</html>