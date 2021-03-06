<?php
require __DIR__.'/../app/posts/sortposts.php';
require __DIR__.'/partials/header.php';
?>

	<div class="content">
		<!-- client side validation error message -->
		<div class="jsMessage">
		</div>
		<main class="authMain">
			<div class="displayPosts">
				<?php if ($posts) {
    foreach ($posts as $post) {
        ?>
				<div class="post">
					<?php require __DIR__.'/partials/post.block.php' ?>
				</div>
				<?php 
    }
} else {
    ?>
				<div class="noPosts">
					<h2>Welcome to Linkify!</h2>
					<p>Join our community to share and discuss interesting things you find on the web.</p>
					<button class="joinLinkify">Let's join!</button>
				</div>
				<?php 
} ?>
			</div>
			<?php if (!$lastPost) {
    ?>
			<div class="showMoreDiv">
				<button class="showMore">Show more links...</button>
			</div>
			<?php 
} ?>

		</main>
	</div>
</div> <!-- end page -->

<!-- forms slide in -->
<div class="menuSlide authSlide">

	<p class="menuClose">X</p>

	<div class="forms">

		<div class="loginWrap">
			<h3>Log in</h3>
			<form action="app/auth/login.php" method="post" class="login">
			<?php if ($error) {
    ?>
				<div class="error loginError">
					<?= $error; ?>
				</div>
				<?php unset($_SESSION['error']);
} ?>
				<input type="text" name="username" placeholder="Email or username" value="<?=isset($_POST['username']) ? $_POST['username'] : ''; ?>">
				<input type="password" name="password" placeholder="Password">
				<div>
					<input type="checkbox" name="remember" checked><label for="remember">Remember me</label>
				</div>
				<input type="submit" name="loginSubmit" value="Log in">
			</form>
		</div>

		<div class="registerWrap">
			<h3>Register</h3>
			<form action="app/auth/register.php" method="post" class="register">
				<?php if ($error) {
    ?>
				<div class="error">
					<?= $error; ?>
				</div>
				<?php unset($_SESSION['error']);
} ?>
				<input type="text" name="fullName" placeholder="Full name" value="<?=isset($_POST['fullName']) ? $_POST['fullName'] : ''; ?>">
	      <input type="text" name="usernameReg" placeholder="Username" value="<?=isset($_POST['usernameReg']) ? $_POST['usernameReg'] : ''; ?>">
				<input type="email" name="emailReg" placeholder="Email" value="<?=isset($_POST['emailReg']) ? $_POST['emailReg'] : ''; ?>">
				<input type="password" name="passwordReg" placeholder="Password">
				<div>
					<input type="checkbox" name="terms"><label for="terms">Accept terms</label>
				</div>
				<input type="submit" name="registerSubmit" value="Register">
			</form>
		</div>

	</div>

</div>


<?php require __DIR__.'/partials/footer.php'; ?>
