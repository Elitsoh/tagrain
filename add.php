<?php

require 'init.php';
require LIB_PATH . DS . 'user.php';

hasSession();

$title = "Page d'accueil";


include THEME_PATH . DS . 'add.phtml';
