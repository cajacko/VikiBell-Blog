<?php
/**
 * Show the tags page 
 */

require_once('../models/tags.php');

$vars['tags'] = get_tags();
$template_path .= 'tags';
