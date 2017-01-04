<?php
	$pageTitle = (isset($pageTitle)) ? $pageTitle:"Linkify";
	$error = $_SESSION["error"] ?? "";
	$message = $_SESSION["message"] ?? "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $pageTitle; ?></title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/10up-sanitize.css/4.1.0/sanitize.min.css">
	<link rel="stylesheet" href="/assets/styles/main.css">
</head>
<body>
	<div class="acceptCookies">
		<p>This website uses <a class="cookieLink" href="https://cookielaw.org/the-cookie-law" title="Read more about the cookie law">cookies</a>
			to give you the best experience possible.</p>
		<button class="cookieButton">Accept</button>
	</div>
	<div class="page">
		<header>
			<h1><a href="/index.php">Linkify</a></h1>

			<?php if (!checkLogin($db)) { ?>
				<nav class="auth">
					<p class="menuLink">Log in</p>
				</nav>
			<?php } else { ?>
				<nav class="menuNav">
					<p class="menuLink">Menu</p>
				</nav>

			<?php }; ?>
		</header>
