<?php
/**
 * Show a standard content page
 */

require_once('../models/page.php');

$page = get_page($request);

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

  unset($vars['breadcrumbs']);
  unset($vars['pageTitle']);

  page_meta(array(
    'title' => $vars['page']['title'] . ' | Viki Bell',
    'description' => $vars['page']['description'],
  ));
} 
// Otherwise route to 404
else {
  require_once('404.php');
}
