<?php include './config/config.php'; ?>
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
        <div class="main-container page-404">
            <div class="dtable hw100">
                <div class="dtable-cell hw100">
                    <div class="container">
                        <div class="404-content text-center">
                            <h4 style="text-transform: uppercase; font-weight: bold;">Ohh!! You have requested for a page that doesn't exist.</h4>
                            <h5 style="text-transform: uppercase; margin: 15px 0;">
                                or maybe it has been removed.
                            </h5>
                            <h1 class="title-404">4<i class="fa fa-frown-o"></i>4</h1>
                            <h2 style="font-weight: bold; text-transform: uppercase;">PAGE NOT FOUND </h2>

                            <form class="form-404">
                                <div>
                                    <a href="<?php baseUrl(); ?>home" class="btn btn-primary text-center">Go to Homepage</a>
                                </div><!-- /input-group -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php include basePath('social_link.php'); ?>
            <?php include basePath('footer.php'); ?>
            <?php include basePath('login_modal.php'); ?>
            <?php include basePath('signup_modal.php'); ?>
            <?php include basePath('footer_script.php'); ?>

    </body>
</html>
