<?php include './config/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php include basePath('header_script.php'); ?>

    </head>
    <body>
        <input type="radio" name="myRadios" onclick="handleClick(this);" value="1" />
        <input type="radio" name="myRadios" onclick="handleClick(this);" value="2" />

        <script>
            var currentValue = 0;
            function handleClick(myRadio) {
                alert('Old value: ' + currentValue);
                alert('New value: ' + myRadio.value);
                currentValue = myRadio.value;
            }
        </script>
    </body>

</html>