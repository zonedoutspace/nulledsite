<?php
include './config/config.php';

$SP_id = $_GET['SP_id'];
if ($SP_id > 0) {
    $getSeatPlace = "SELECT * FROM seat_place WHERE SP_id=$SP_id";
    $resultSeatPlace = mysqli_query($con, $getSeatPlace);
    if ($resultSeatPlace) {
        $resultSeatPlaceObj = mysqli_fetch_object($resultSeatPlace);
    }

    $arrPlaceMap = array();
    $getPlaceMap = "SELECT * FROM seat_place_coordinate WHERE SPC_SP_id=$SP_id";
    $resultPlaceMap = mysqli_query($con, $getPlaceMap);
    if ($resultPlaceMap) {
        while ($resultPlaceMapObj = mysqli_fetch_object($resultPlaceMap)) {
            $arrPlaceMap[] = $resultPlaceMapObj;
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <body>

        <p>Click on the sun or on one of the planets to watch it closer:</p>

        <img src="<?php echo $config['IMAGE_UPLOAD_URL'] . '/place_layout/' . $resultSeatPlaceObj->SP_image; ?>" alt="<?php echo $resultSeatPlaceObj->SP_title; ?>" usemap="#planetmap">

        <map name="planetmap">
            <?php if (count($arrPlaceMap) > 0): ?>
                <?php foreach ($arrPlaceMap AS $Map): ?>
                    <area shape="<?php echo $Map->SPC_shape_type; ?>" coords="<?php echo $Map->SPC_coordinates; ?>" alt="<?php echo $Map->SPC_title; ?>" href="javascript:void(0);" title="<?php echo $Map->SPC_title; ?>" style="color:red;">
                <?php endforeach; ?>
            <?php endif; ?>
        </map>

    </body>
</html>
