<?php
/**
 * 
 */

// If no post slug is defined then route to 404
if(isset($request[1])) {
  $redirect = $redirect_base . $request[0];
  header('Location: ' . $redirect);
  exit;
} 
// Query is good so get the post if it exists
else {
  require_once('../models/sitemap.php');

  $vars['sitemap'] = sitemap_page();

  $vars['isSingle'] = true;
  $template_path .= 'sitemap';

  $vars['breadcrumbs'] = array(
    'Home' => '/',
    'Sitemap' => '/sitemap/'
  );

  $vars['pageTitle'] = 'Sitemap';

  page_meta(array(
    'title' => 'Sitemap | Viki Bell',
    'description' => 'Everything vikibell.com has to offer!',
  ));
}
