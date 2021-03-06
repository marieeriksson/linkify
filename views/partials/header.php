<?php
$pageTitle = (isset($pageTitle)) ? $pageTitle : 'Linkify';
$error = $_SESSION['error'] ?? '';
$message = $_SESSION['message'] ?? '';
if (checkLogin($db)) {
    $user = getUserInfo($db);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $pageTitle; ?></title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/10up-sanitize.css/4.1.0/sanitize.min.css">
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Syncopate">
	<link rel="stylesheet" href="/assets/styles/main.css">
</head>
<body>
	<div class="page">
		<div class="acceptCookies">
			<p>This website uses <a class="cookieLink" href="https://cookielaw.org/the-cookie-law" title="Read more about the cookie law">cookies</a>
				to give you the best experience possible.</p>
			<button class="cookieButton">Accept</button>
		</div>
		<header>
			<div class="leftHeader">
				<a href="/">
					<img src="/assets/images/logo.png" alt="Linkify logo"/>
				</a>
				<a href="/">
					<h1>Linkify</h1>
				</a>
			</div>

			<?php if (!checkLogin($db)) {
    ?>
			<nav class="authNav">
				<div class="sortPosts">
					<form action="/" method="post">
						<input type="submit" name="byDate" value="New" class="<?=$sortMethod === 'published' ? 'sortMethod' : ''; ?>" >
						<input type="submit" name="byPop" value="Popular" class="<?=$sortMethod === 'votes' ? 'sortMethod' : ''; ?>">
					</form>
				</div>
				<div class="menuLink">
					<img src="/assets/images/menuicon.png"/ alt="menu">
				</div>
			</nav>
			<?php 
} else {
    ?>
			<nav class="menuNav">
				<?php if ($posts) {
        if ($pageTitle != 'Linkify - Settings' && $pageTitle != 'Linkify - Profile') {
            ?>
				<div class="sortPosts">
					<form action="/" method="post">
						<input type="submit" name="byDate" value="New" class="<?=$sortMethod === 'published' ? 'sortMethod' : ''; ?>">
						<input type="submit" name="byPop" value="Popular" class="<?=$sortMethod === 'votes' ? 'sortMethod' : ''; ?>">
					</form>
				</div>
				<?php 
        }
    } ?>
				<div class="profileLink">
					<a href="/profile/<?=$_SESSION['login']['username']?>">
						<?php if ($user['avatar'] !== null) {
        ?>
						<img src="/assets/images/users/<?=$user['id']?>/<?=$user['avatar']?>" alt="profile" />
						<?php 
    } else {
        ?>
						<img src="/assets/images/profileicon.png" alt="profile" />
						<?php 
    } ?>
					</a>
				</div>
				<div class="menuLink">
					<img src="/assets/images/menuicon.png" alt="menu"/>
				</div>
			</nav>
			<?php 
} ?>
		</header>
