<?php

$template = $twig->loadTemplate('templates/home.twig');

// gzip compress content
ob_start("ob_gzhandler");
  echo $template->render(array('config' => $config));
