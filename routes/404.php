<?php
/**
 * Show a 404 error page 
 */

$header_code = 404;
$template_path .= '404';

$vars['breadcrumbs'] = array(
  'Home' => '/',
);

$vars['pageTitle'] = '404: Page not found';

page_meta(array(
  'title' => '404 | Viki Bell',
  'description' => 'Looks like Viki can\'t find what your looking for :s',
));