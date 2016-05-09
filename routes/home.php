<?php

require_once('../models/home.php');

$vars['posts'] = get_default_posts($pagination);
$template_path .= 'loop';

unset($vars['breadcrumbs']);
unset($vars['pageTitle']);

page_meta(array(
  'title' => 'Viki Bell',
  'description' => 'Life on the planet Viki continues...',
));
