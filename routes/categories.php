<?php
/**
 * Show the categories page 
 */

require_once('../models/categories.php');

$vars['categories'] = get_categories();
$template_path .= 'categories';
