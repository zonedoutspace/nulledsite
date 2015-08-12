
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script type="text/javascript" src="<?php echo baseUrl('custom_javascript/customScript.js'); ?>"></script>
<script src="<?php echo baseUrl('js/moment.js'); ?>"></script>
<script async src="<?php echo baseUrl('js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo baseUrl('js/owl.carousel.js'); ?>"></script>

<script src="<?php echo baseUrl('js/simply-toast.min.js'); ?>"></script>


<script async src="<?php echo baseUrl('js/script.js'); ?>"></script>

<!-- From Category Page -->
<script src="<?php echo baseUrl('js/icheck/icheck.js?v=1.0.2'); ?>"></script>
<script>
    $(document).ready(function () {
        $(document).ready(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
                increaseArea: '10%' // optional
            });
        });
    });
 
</script> 

<script>
    var baseUrl = '<?php echo baseUrl(); ?>';
</script>


<!--Start of Tawk.to Script-->
<script type="text/javascript">
//    var $_Tawk_API = {}, $_Tawk_LoadStart = new Date();
//    (function () {
//        var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
//        s1.async = true;
//        s1.src = 'https://embed.tawk.to/54c3386e89e115ed42e24325/default';
//        s1.charset = 'UTF-8';
//        s1.setAttribute('crossorigin', '*');
//        s0.parentNode.insertBefore(s1, s0);
//    })();
</script>
<!--End of Tawk.to Script-->
