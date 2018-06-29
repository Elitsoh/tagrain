<?php

require 'init.php';
require LIB_PATH . DS . 'user.php';

hasSession();

$title = 'Tableau de bord';

include THEME_PATH . DS . 'dashboard.phtml';
