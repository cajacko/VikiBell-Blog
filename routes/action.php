<?php
/**
 * Show a category taxonomy page
 */

// If no category was specified then redirect to the tags page
if(!isset($request[1])) {
  require_once('404.php');
} 
// Otherwise show the category loop
else {

  switch($request[1]) {
    case 'build-sitemap':
      require_once('../models/sitemap.php');
      $response = create_xml_sitemap();
      echo $response;
      exit;

    default:
      require_once('404.php');
      break;
  }
}
