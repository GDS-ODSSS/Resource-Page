<?php
//----------------------------------------------------------------------|
/***********************************************************************|
 * Project:     UNHCR IM Resource Page                                       |
//----------------------------------------------------------------------|
 * @link http://nawaaugustine.com                                         |
 * @copyright 2021.                                                     |
 * @author Augustine Nawa <ocjpnawa@gmail.com>                   |
 * @package UNHCR IM Resource Page                                           |
 * @version 4.7                                                         |
//----------------------------------------------------------------------|
************************************************************************/
//----------------------------------------------------------------------|
if (!defined("IN_PHPMEGATEMP"))
      die('Direct access to this location is not allowed.');

// require constants file
require_once(ABSPATH . 'includes/constants.php');
// require class files
require_once(ABSPATH . 'includes/class_mysql.php');
require_once(ABSPATH . 'includes/class_mysqli.php');
require_once(ABSPATH . 'includes/class_template.php');
require_once(ABSPATH . 'includes/class_display.php');
require_once(ABSPATH . 'includes/class_users.php');
require_once(ABSPATH . 'includes/class_permanent_links.php');
require_once(ABSPATH . 'includes/class_hooks.php');
require_once(ABSPATH . 'includes/class_image.php');
// require functions files
require_once(ABSPATH . 'includes/functions.php');
require_once(ABSPATH . 'includes/functions_language.php');
require_once(ABSPATH . 'includes/function_template.php');
require_once(ABSPATH . 'includes/functions_mailer.php');
require_once(ABSPATH . 'includes/functions_session.php');
require_once(ABSPATH . 'includes/functions_user.php');
require_once(ABSPATH . 'includes/functions_post.php');
require_once(ABSPATH . 'includes/functions_term.php');
$lang = get_all_languages();

// start theme function
if(file_exists(ABSPATH.THEME_PATH.$config['sitethemes'].'/theme_options/theme_options.php'))
{
    include(ABSPATH.THEME_PATH.$config['sitethemes'].'/theme_options/theme_options.php'); 
}
// start extensions
$extensions = (is_serialized($config['start_extensions']))? maybe_unserialize($config['start_extensions']) : array() ;
foreach($extensions as $extension)
{
    $file_extension = ABSPATH . "extensions/{$extension}/extension.php";
    if(file_exists($file_extension))
    {
        require_once($file_extension);
    }
}
?>