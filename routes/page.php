<?php
/**
 * Show a standard content page
 */

require_once('../models/page.php');

$page = get_page($page_request);

// If there is a redirect url then redirect
if(isset($page['redirect'])) {
  $redirect = $redirect_base . $page['redirect'];
  header('Location: ' . $redirect);
  exit;
} 
// Otherwise, if the page exists then show it
elseif($page) {
  $vars['page'] = $page;
  $vars['isSingle'] = true;
  $template_path .= 'page';
} 
// Otherwise route to 404
else {
  require_once('404.php');
}
