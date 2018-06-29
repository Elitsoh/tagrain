<?php
// library/blog.php

/**
 * Return all articles.
 *
 * @param PDO $pdo
 * @return bool|PDOStatement
 */
function getArticles(PDO $pdo, $start = 0, $end = -1) {
    $limit = '';
    if ($start >= 0 && $end > 0) {
        $limit = sprintf(' LIMIT %d,%d', $start, $end);
    }

    $sql = 'SELECT 
              a.article_id,
              a.title,
              a.teaser,
              a.status,
              a.created_at,
              u.user_id,
              u.username,
              u.email
            FROM article AS a
            JOIN `user` AS u
              ON a.user_id = u.user_id
            WHERE status=1' . $limit;

    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

function getUserArticles(PDO $pdo, $userId) {
    $sql = 'SELECT 
              a.article_id,
              a.title,
              a.teaser,
              a.status,
              a.created_at,
              u.username,
              u.user_id,
              u.email,
              GROUP_CONCAT(c.name) AS categories 
            FROM article AS a 
            LEFT JOIN article_has_category AS ac
              ON ac.article_id = a.article_id
            LEFT JOIN category AS c 
              ON ac.category_id = c.category_id
            JOIN `user` AS u 
              ON a.user_id = u.user_id
            WHERE a.user_id = ?
            GROUP BY a.article_id
            ORDER BY a.created_at DESC';

    $stmt = $pdo->prepare($sql);
    $articles = [];

    if ($stmt->execute(array($userId))) {
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    return $articles;
}

function getCategories(PDO $pdo) {
    $sql = 'SELECT category_id, `name` FROM category ORDER BY name';
    $stmt = $pdo->prepare($sql);

    $categories = [];
    if ($stmt->execute()) {
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return $categories;
}

function addArticle(PDO $pdo, $article) {
    $sql = 'INSERT INTO article VALUES (NULL, :userId, :title, :teaser, :content, NOW(), NOW(), :status)';
    $sqlCat = 'INSERT INTO article_has_category VALUES (?, ?)';

    $dataArticle = array(
        'userId' => $article['user_id'],
        'title' => $article['title'],
        'teaser' => $article['teaser'],
        'content' => $article['content'],
        'status' => $article['status'] === 'on' ? 1 : 0,
    );

    $pdo->beginTransaction();

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($dataArticle);

        $articleId = $pdo->lastInsertId();
        $stmt = $pdo->prepare($sqlCat);

        foreach ($article['categories'] as $categoryId) {
            $stmt->execute(array($articleId, $categoryId));
        }

        $pdo->commit();
        return $stmt->rowCount();
    } catch (PDOException $e) {
        $pdo->rollBack();
        throw $e;
    }

    return 0;
}
