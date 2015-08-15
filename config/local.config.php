<?php
/* change when upload to different domain 
 * setting site hosting  data 
 */

$host = $_SERVER['HTTP_HOST'];

$domain = str_replace('www.', '', str_replace('http://', '', $host));

if ($domain == 'ticketchai.com') {
    $config['SITE_NAME'] = 'ticketchai';
    $config['BASE_URL'] = 'http://ticketchai.com/';
    $config['ROOT_DIR'] = '/hometicketchai2015/public_html/ticketchai.com/';
    $config['DB_TYPE'] = 'mysql';
    $config['DB_HOST'] = 'localhost';
    $config['DB_NAME'] = 'ticketchai-new';
    $config['DB_USER'] = 'ticketchai15';
    $config['DB_PASSWORD'] = "ODsza^neen0?";
} else {
    $config['SITE_NAME'] = 'Ticket Chai | Buy Online Tickets....';
    $config['BASE_URL'] = 'http://localhost/ticketchai.com/';
    $config['ROOT_DIR'] = '/ticketchai-ori/';
    $config['DB_TYPE'] = 'mysql';
    $config['DB_HOST'] = 'localhost'; //updated the host name for local run
    $config['DB_NAME'] = 'ticketchai-ori'; //updated the database name for local run
    $config['DB_USER'] = 'root'; //updated the database user name for local run
    $config['DB_PASSWORD'] = ''; //updated the database user password for local run
}

date_default_timezone_set('Asia/Dhaka');
$config['MASTER_ADMIN_EMAIL'] = "faruk@bscheme.com"; /* Developer */
$config['PASSWORD_KEY'] = "#t1ck3tc74i*"; /* If u want to change PASSWORD_KEY value first of all make the admin table empty */
$config['ADMIN_PASSWORD_LENGTH_MAX'] = 15; /* Max password length for admin user  */
$config['ADMIN_PASSWORD_LENGTH_MIN'] = 5; /* Min password length for admin user  */
$config['ADMIN_COOKIE_EXPIRE_DURATION'] = (60 * 60 * 24 * 30); /* Min password length for admin user  */

$config['ITEMS_PER_PAGE'] = 20; /* Pagination */
$config['CATEGORY_ITEMS_PER_PAGE'] = 120; /*category.php */
$config['IMAGE_PATH'] = $config['BASE_DIR'] . '/images'; /* system image path */
$config['IMAGE_URL'] = $config['BASE_URL'] . 'images'; /* Upload system path */
$config['IMAGE_UPLOAD_PATH'] = $config['BASE_DIR'] . '/upload'; /* Upload files go here */
$config['IMAGE_UPLOAD_URL'] = $config['BASE_URL'] . 'upload'; /* Upload link with this */

$config['MAX_CATEGORY_LEVEL'] = 10; /* to control category level */
$config['PRODUCT_CATEGORY_ID'] = 2; /* product category id start from here */
$config['BOOKMARK_CATEGORY_ID'] = 119; /* bookmark category id start from here */
$config['BRAND_CATEGORY_ID'] = 1; /* Brand category id start from here */
$config['CATEGORY_CAROUSEL_LIMIT'] = 8; /* product category page per category slide limit */
$config['COUPON_MAX_APPLY'] = 4; /* Maximum attempt time for applying coupon code */
/*define banner areas*/
$config['BANNER_AREA']['HOME']="Home page top ";
$config['BANNER_AREA']['NEW']="New arrival page";
$config['BANNER_AREA']['SALES']="Sales page";

/*define banner areas*/

$config['CURRENCY'] = "TK"; /* ---Image Maximum height : This ratio will multiply with image width , if Image height exceed Image Maximum height then Image cann't upload */
$config['CURRENCY_SIGN'] = "৳"; /* ---Image Maximum height : This ratio will multiply with image width , if Image height exceed Image Maximum height then Image cann't upload */


/* Start of magic quote remover function
  This function is used for removing magic quote, Thats means using this function no slash will add automatically before quotations */
if (get_magic_quotes_gpc()) {
    $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    while (list($key, $val) = each($process)) {
        foreach ($val as $k => $v) {
            unset($process[$key][$k]);
            if (is_array($v)) {
                $process[$key][stripslashes($k)] = $v;
                $process[] = &$process[$key][stripslashes($k)];
            } else {
                $process[$key][stripslashes($k)] = stripslashes($v);
            }
        }
    }
    unset($process);
}

    /* End of magic quote remover function */
   
