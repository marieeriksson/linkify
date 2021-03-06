<?php

require __DIR__.'/../../autoload.php';

// validation error codes
define('LOGIN_SUCCESS', '6');
define('NO_USERNAME', '7');
define('NO_PASSWORD', '8');
define('USER_NOT_FOUND', '9');

// Check if all fields has input
function validateLoginFields($user, $password)
{
    if (empty($user)) {
        return NO_USERNAME;
    }
    if (empty($password)) {
        return NO_PASSWORD;
    }

    return LOGIN_SUCCESS;
}

// Check if user exists in db and if password is correct
function checkUser($db, $user, $password)
{
    $getUserQuery = "SELECT * FROM users WHERE username = '{$user}' OR email = '{$user}'";
    $getUserStatement = queryToDb($db, $getUserQuery);

    if ($getUserStatement->rowCount() == 0) {
        return USER_NOT_FOUND;
    }
    $userInfo = $getUserStatement->fetch(PDO::FETCH_ASSOC);
    if (!password_verify($password, $userInfo['password'])) {
        return USER_NOT_FOUND;
    }

    return $userInfo;
}

function createLoginCookie($db, $userId)
{
    $first = bin2hex(random_bytes(64));
    $second = bin2hex(random_bytes(128));
    $cookie = "$first|$userId|$second";
    $timestamp = time() + 60 * 60 * 24 * 30;
    $expire = date('Y-m-d H:i:s', $timestamp);

    $insertCookieIntoDb = 'INSERT INTO cookies (user_id, expire, first, second)
	VALUES (:user_id, :expire, :first, :second)';
    prepareAndExecute($db, $insertCookieIntoDb, [
        ':user_id' => $userId,
        ':expire' => $expire,
        ':first' => $first,
        ':second' => $second,
    ]);
    setcookie('linkify', $cookie, $timestamp, '/', '', false, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['loginSubmit'])) {
        // escape input to avoid exploit attempts
      foreach ($_POST as $input => $value) {
          $_POST[$input] = escapeInput($value);
      }
        // user can log in with email or username
      $user = $_POST['username'];
        $password = $_POST['password'];

      // validate email and password from input fields
      $result = validateLoginFields($user, $password);

      // output error messages if  a field is empty
      if ($result == NO_USERNAME) {
          $_SESSION['error'] = 'Fill out your username or email.';
      } elseif ($result == NO_PASSWORD) {
          $_SESSION['error'] = 'Fill out your password.';
      } else {
          // check if user exists
        $userInfo = checkUser($db, $user, $password);
        // output error message if user not found
        if ($userInfo == USER_NOT_FOUND) {
            $_SESSION['error'] = 'Username, email or password is incorrect.';
        } else {
            // put users info in session array and relocate to index, if log in succeeds
          $_SESSION['login']['id'] = $userInfo['id'];
            $_SESSION['login']['username'] = $userInfo['username'];
            if (isset($_POST['remember'])) {
                createLoginCookie($db, $userInfo['id']);
            }
        }
      }
        header('Location: /');
        exit();
    }
}
