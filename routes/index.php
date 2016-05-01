<?php

$template = $twig->loadTemplate('templates/home.twig');
echo $template->render(array('the' => 'variables', 'go' => 'here'));
