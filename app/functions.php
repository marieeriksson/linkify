<?php

function escapeInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}

function validateCookie($db)
{
    $values = explode('|', $_COOKIE['linkify']);
    $userId = $values[1];
    $first = $values[0];
    $second = $values[2];

    $getCookieFromDb = "SELECT user_id FROM cookies
  WHERE user_id = '{$userId}' AND first = '{$first}' AND second = '{$second}' AND expire >= NOW()";

    $getCookieStatement = queryToDb($db, $getCookieFromDb);

    if ($getCookieStatement->rowCount() > 0) {
        return $userId;
    }
}

function checkLogin($db)
{
    if (!isset($_SESSION['login'])) {
        if (!isset($_COOKIE['linkify'])) {
            return false;
        }
        if (!($userId = validateCookie($db))) {
            return false;
        }
        $_SESSION['login']['id'] = $userId;
    }

    return true;
}

// validation codes for post input
define('POST_SUCCESS', '10');
define('MISSING_POST_INPUT', '11');
define('INVALID_URL', '12');

function validateNewPostFields($url)
{
    // check if all fields has input
    foreach ($_POST as $input => $value) {
        if (empty($_POST[$input])) {
            return MISSING_POST_INPUT;
        }
    }
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return INVALID_URL;
    }

    return POST_SUCCESS;
}

function getUserInfo($db)
{
    $id = $_SESSION['login']['id'];
    $getUserInfoQuery = "SELECT * FROM users WHERE id = '{$id}' LIMIT 1";
    $getUserInfoStatement = queryToDb($db, $getUserInfoQuery);
    $userInfo = $getUserInfoStatement->fetch(PDO::FETCH_ASSOC);

    return $userInfo;
}

function getPosts($db, $order, $offset, $limit, $userId = 'IS NOT NULL')
{
    $getPostsQuery = "SELECT posts.id, posts.description, posts.subject, posts.url, posts.published AS published, posts.user_id,
	posts.edited, users.name, users.username, SUM(votes.vote) AS votes  FROM posts INNER JOIN users ON posts.user_id = users.id
	LEFT JOIN votes ON posts.id = votes.post_id WHERE users.id {$userId} GROUP BY posts.id ORDER BY {$order} DESC LIMIT {$offset}, {$limit}";
    $getPostsStatement = queryToDb($db, $getPostsQuery);

    if ($getPostsStatement->rowCount() == 0) {
        return;
    }
    $posts = $getPostsStatement->fetchAll(PDO::FETCH_ASSOC);

    return $posts;
}

function checkIfLastPost($posts, $limit)
{
    $lastPost = true;
    $countPosts = count($posts);
    if ($countPosts == $limit) {
        $lastPost = false;
    }

    return $lastPost;
}

function getComments($db, $postId)
{
    $getCommentsQuery = "SELECT comments.id, comments.user_id, comments.comment, comments.published, comments.reply_to,
	comments.edited, users.name, users.username FROM comments INNER JOIN users ON comments.user_id = users.id
	WHERE comments.post_id = '{$postId}' ORDER BY comments.published DESC";
    $getCommentsStatement = queryToDb($db, $getCommentsQuery);

    if ($getCommentsStatement->rowCount() == 0) {
        return;
    }
    $comments = $getCommentsStatement->fetchAll(PDO::FETCH_ASSOC);

    return $comments;
}

function countVotes($db, $postId)
{
    $countVotesQuery = "SELECT SUM(vote) AS sum_votes FROM votes WHERE post_id = '{$postId}'";
    $countVotesStatement = queryToDb($db, $countVotesQuery);

    $votes = $countVotesStatement->fetch(PDO::FETCH_ASSOC);

    return $votes;
}
// get info for other users profile pages
function getProfileInfo($db, $profileUsername)
{
    $getProfileInfoQuery = "SELECT * FROM users WHERE username = '{$profileUsername}' LIMIT 1";
    $getProfileInfoStatement = queryToDb($db, $getProfileInfoQuery);
    $profileInfo = $getProfileInfoStatement->fetch(PDO::FETCH_ASSOC);

    return $profileInfo;
}
