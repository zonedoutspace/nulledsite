<?php
//generating wishlist from db
$wlistUserID = 0;
$arrWishlist = array();
if (checkUserLogin()) {
    $wlistUserID = getSession('user_id');
    $sqlGetWishlist = "SELECT WL_product_id,WL_product_type FROM wishlists WHERE WL_user_id=$wlistUserID";
    $resultGetWishlist = mysqli_query($con, $sqlGetWishlist);

    if ($resultGetWishlist) {
        while ($resultGetWishlistObj = mysqli_fetch_array($resultGetWishlist)) {
            $arrWishlist[] = $resultGetWishlistObj['WL_product_id'] . '-' . $resultGetWishlistObj['WL_product_type'];
        }
    } else {
        if (DEBUG) {
            echo "resultGetWishlist error: " . mysqli_error($con);
        } else {
            echo "resultGetWishlist query failed.";
        }
    }
}
?>
<meta name="googlebot" content="index,follow" >
<meta http-equiv="Content-type" content="text/html; charset=utf-8" >
<meta name="geo.position" content="23.748323;90.403642" >
<meta name="ICBM" content="23.748323,90.403642" >
<script>
    var baseUrl = '<?php echo baseUrl(); ?>';
</script>

<!-- Fav and touch icons -->
<link rel="shortcut icon" href="<?php echo baseUrl(); ?>ico/favicon.png">
<title>Ticket Chai | Buy Online Ticket...</title>




<?php if ($config['BASE_URL'] == "http://ticketchai.com/"): ?>
    <!-- Piwik -->
    <!--    <script type="text/javascript">
        var _paq = _paq || [];
        _paq.push(['trackPageView']);
        _paq.push(['enableLinkTracking']);
        (function () {
            var u = "//piwik.ticketchai.com/";
            _paq.push(['setTrackerUrl', u + 'piwik.php']);
            _paq.push(['setSiteId', 1]);
            var d = document, g = d.createElement('script'), s = d.getElementsByTagName('script')[0];
            g.type = 'text/javascript';
            g.async = true;
            g.defer = true;
            g.src = u + 'piwik.js';
            s.parentNode.insertBefore(g, s);
        })();
    </script>
    <noscript><p><img src="//piwik.ticketchai.com/piwik.php?idsite=1" style="border:0;" alt="" /></p></noscript>-->
    <!-- End Piwik Code -->

    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
        var $_Tawk_API = {}, $_Tawk_LoadStart = new Date();
        (function () {
            var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = 'https://embed.tawk.to/54c3386e89e115ed42e24325/default';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>
    <!--End of Tawk.to Script-->
<?php endif; ?>

<!--<link rel="stylesheet" href="<?php // echo baseUrl('css/bootstrap.css');    ?>">-->
<!--<script src="<?php // echo baseUrl('js/jquery.min.1.10.1.js');  ?>"></script>--> 

<script src="http://code.jquery.com/jquery-latest.min.js"></script>


<script src="//cdnjs.cloudflare.com/ajax/libs/json2/20110223/json2.js"></script>
<script type="text/javascript" src="<?php echo baseUrl('js/jStorage.js'); ?>"></script>

<!-- Custom Script For Project -->


<script>
//    var cb = function () {
//        var l = document.createElement('link');
//        l.rel = 'stylesheet';
//        l.href = baseUrl + 'css/bootstrap.min.3.3.4.css';
//        var h = document.getElementsByTagName('head')[0];
//        h.parentNode.insertBefore(l, h);
//    };
//    var raf = requestAnimationFrame || mozRequestAnimationFrame ||
//            webkitRequestAnimationFrame || msRequestAnimationFrame;
//    if (raf)
//        raf(cb);
//    else
//        window.addEventListener('load', cb);
</script>
<script>
//    var cb = function () {
//        var l = document.createElement('link');
//        l.rel = 'stylesheet';
//        l.href = baseUrl + 'css/style.css';
//        var h = document.getElementsByTagName('head')[0];
//        h.parentNode.insertBefore(l, h);
//    };
//    var raf = requestAnimationFrame || mozRequestAnimationFrame ||
//            webkitRequestAnimationFrame || msRequestAnimationFrame;
//    if (raf)
//        raf(cb);
//    else
//        window.addEventListener('load', cb);
</script>
<script>
//    var cb = function () {
//        var l = document.createElement('link');
//        l.rel = 'stylesheet';
//        l.href = baseUrl + 'css/bootstrap-datetimepicker.min.css';
//        var h = document.getElementsByTagName('head')[0];
//        h.parentNode.insertBefore(l, h);
//    };
//    var raf = requestAnimationFrame || mozRequestAnimationFrame ||
//            webkitRequestAnimationFrame || msRequestAnimationFrame;
//    if (raf)
//        raf(cb);
//    else
//        window.addEventListener('load', cb);
</script>
<script>
//    var cb = function () {
//        var l = document.createElement('link');
//        l.rel = 'stylesheet';
//        l.href = baseUrl + 'css/simply-toast.min.css';
//        var h = document.getElementsByTagName('head')[0];
//        h.parentNode.insertBefore(l, h);
//    };
//    var raf = requestAnimationFrame || mozRequestAnimationFrame ||
//            webkitRequestAnimationFrame || msRequestAnimationFrame;
//    if (raf)
//        raf(cb);
//    else
//        window.addEventListener('load', cb);
</script>
<script>
//    var cb = function () {
//        var l = document.createElement('link');
//        l.rel = 'stylesheet';
//        l.href = baseUrl + 'css/customs.css';
//        var h = document.getElementsByTagName('head')[0];
//        h.parentNode.insertBefore(l, h);
//    };
//    var raf = requestAnimationFrame || mozRequestAnimationFrame ||
//            webkitRequestAnimationFrame || msRequestAnimationFrame;
//    if (raf)
//        raf(cb);
//    else
//        window.addEventListener('load', cb);
</script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo baseUrl('css/style.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo baseUrl('css/customs.css'); ?>">

<link rel="stylesheet" type="text/css" href="<?php echo baseUrl('css/bootstrap-datetimepicker.min.css'); ?>">
<!-- For Toast Message -->
<link rel="stylesheet" type="text/css" href="<?php echo baseUrl('css/simply-toast.min.css'); ?>">




<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->

<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

<script>
    paceOptions = {
        elements: true
    };
</script>

<script async src="<?php echo baseUrl('js/pace.min.js'); ?>"></script>