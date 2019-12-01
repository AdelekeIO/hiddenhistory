<?php
function setHead() {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Content-Type');
}

setHead();
define("APP_id", "7b585406");
define("APP_key", "b32fa9b848e9e2295b36a04ca68077de");
//define("GALLERY_name", "EdvesFaceidTest");
define("GALLERY_name", "EdvesDemoGallery");
 

set_time_limit(0);
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
//    print_r($url);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/vendor/autoload.php';

session_start();
require_once './ForceUTF8/Encoding.php';
use \ForceUTF8\Encoding;  // It's namespaced now.

//include './cloudinary/Cloudinary.php';
//include './cloudinary/Uploader.php';
//include './cloudinary/Api.php';
//if (file_exists('./cloudinary/settings.php')) {
//  include './cloudinary/settings.php';
//}  
// Instantiate the app
$settings = require __DIR__ . '/src/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/src/dependencies.php';

// Register middleware
require __DIR__ . '/src/middleware.php';

// Register database
require __DIR__ . '/src/Database.php';
// Register routes
require __DIR__ . '/src/routes.php';



$db= new database();
 // Run app
$app->run();
