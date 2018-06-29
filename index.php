<?php

require 'init.php';
require LIB_PATH . DS . 'blog.php';

$title = "Page d'accueil";
$articles = getArticles($db, 0, 3);

include THEME_PATH . DS . 'home.phtml';
