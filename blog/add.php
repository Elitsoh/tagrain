<?php

// blogger/blog/add.php

require '../init.php';
require LIB_PATH . DS . 'user.php';

hasSession();

require LIB_PATH . DS . 'blog.php';

$title = 'Nouvel article';
$categories = getCategories($db);

$inputTitle = $_POST['title'] ?? null;
$teaser = $_POST['teaser'] ?? null;
$content = $_POST['content'] ?? null;
$status = $_POST['status'] ?? false;
$inputCats = $_POST['categories'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $article = $_POST;
    $article['user_id'] = $_SESSION['user']['user_id'];

    if (addArticle($db, $article)) {
        header('Location: mes-articles.php');
        exit;
    }
}

include THEME_PATH . DS . 'blog/add.phtml';