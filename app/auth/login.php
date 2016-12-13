<?php

// validation error codes defined as constants
define("LOGIN_SUCCESS", "6");
define("NO_USERNAME", "7");
define("NO_PASSWORD", "8");
define("USER_NOT_FOUND", "9");

// Check if all fields has input
function validateLoginFields($user, $password) {
  if(empty($user)) {
    return NO_USERNAME;
  }
  if(empty($password)) {
    return NO_PASSWORD;
  }
// if both field has input
	return LOGIN_SUCCESS;
}

// Check if user exists in db and if password is correct
function checkUser($db, $user, $password) {
	$getUserQuery = <<<EOT
	SELECT * FROM users WHERE username = '{$user}' OR email = '{$user}';
EOT;
	$getUserStatement = $db->query($getUserQuery);

	if ($getUserStatement->rowCount() == 0 ) {
     return USER_NOT_FOUND;
  }
	$userInfo = $getUserStatement->fetch(PDO::FETCH_ASSOC);
	if (!password_verify($password, $userInfo["password"])) {
		return USER_NOT_FOUND;
	}
	return $userInfo;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_POST["loginSubmit"])) {
	  // escape input to avoid exploit attempts
	  foreach($_POST as $input=>$value) {
	      escapeInput($_POST[$input]);
	  }
		// user = email or username
	  $user = $_POST["username"];
	  $password = $_POST["password"];

	  // call function to validate email and password from input fields
	  $result = validateLoginFields($user, $password);

	  // output error messages if  a field is empty
	  if ($result == NO_USERNAME) {
	    $loginMessage = "Fill out username or email.";
	  } else if ($result == NO_PASSWORD) {
	    $loginMessage = "Fill out password.";
	  } else {
	    // call function to check if user exists
	    $userInfo = checkUser($db, $user, $password);
	    // output error message if user not found
	    if ($userInfo == USER_NOT_FOUND) {
	      $loginMessage = "Username, email or password is incorrect.";
	    } else {
	      // put users info in session array and relocate to index, if log in succeeds
	      $_SESSION["login"]["id"] = $userInfo["id"];
	    	if (isset($_POST["remember"])) {
	      	createLoginCookie($db, $userInfo["id"]);
	      }
	      header("location:/");

			}
    }
  }
}

?>
