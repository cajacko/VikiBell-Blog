<?php

// Setup config
$config = parse_ini_file('config.ini', true);

$project_root = __DIR__; // Get the root dir

// For each directory in the config replace it with it's absolute path
foreach($config['dirs'] as $dir_name => $dir_path) {
  $config['dirs'][$dir_name] = $project_root . $dir_path;
}

$config['dirs']['root'] = $project_root; // Add the absolute path to the dirs

require_once('models/database.php'); 

if($config['environment']['dev']) {
  $static_public = '';
} else { 
  $static_public = $config['cdn']['staticContent'] . $config['cdn']['staticPublic'];
}

require_once('../helpers/process_image_meta.php');

// Get composer dependencies
require_once('vendor/autoload.php'); 

// Load twig templating engine
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem($config['dirs']['views']);
$twig = new Twig_Environment($loader);

// Route the request
require_once($config['dirs']['routes'] . '/index.php');
