<?php

require (dirname(dirname(__FILE__)) . '/vendor/autoload.php');

/**
 * Displays pretty notice when framework hasn't been initialized successfully.
 */
function echoEmergency()
{
    http_response_code(500);
  
    if ( ($_SERVER['CONTENT_TYPE'] ?? "") === 'application/json' || isset($_GET['ajax'])){
        header("Content-Type: application/json");
        echo json_encode([
            "event" => 'application-internal-error'
        ]);
    }
    else {
        echo "<h2>Application internal error</h2>";
        echo "<p><font color=\"red\">Emergency</font>: Application is unusable</p>";
    }
}


/**
 * This code is responsible for initialize framework in case when framework core is shared between applications
 * You must define in your .htaccess in web/ directory an apache environment variable which contains paths to
 * init.controller.php in path where swayframework is installed.
 * 
$initControllerIncludePath = null;

require_once ('/var/www/reverze/swayengine/init.controller.php');

if (isset($_SERVER['INIT-SWAYFRAMEWORK-CONTROLLER'])){
    require_once ($_SERVER['INIT-SWAYFRAMEWORK-CONTROLLER']);
}
else{
    if (empty($initControllerIncludePath)){
        echo '<div style="width: 100%; height: 100%; text-align: center;">';
        echo '<h2>Did you forget to include framework init controller?</h2>';
        echo "Server's variable <strong>INIT-SWAYFRAMEWORK-CONTROLLER</strong> was not found. Set it in your <u>web/.htaccess</u> or look at <u>app.controller.php</u> file for more details.";
        echo "<br/><br />Example: <i>/usr/bin/local/swayframework/init.controller.php</i>";
        echo '</div>';
    }
}
*/
require_once ('yaml/yaml.php');


/**
 * If framework core is not shared between applications, simply import init.controller.
 * Notice! If you want to share framework core between applications, please comment below line of code
 */
require_once ('init.controller.php');


use Sway\Init;

/**
 * We create a new init controller to initialize framework core.
 * Passed __FILE__ helps to determine application working directory
 */
$initController = new Init\Controller(__FILE__);

/**
 * Next steps are performed in front controllers (controller.php or controller_dev.php - according framework mode: production or development) in web/ directory
 */


?>