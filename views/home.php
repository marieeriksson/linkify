<?php
require __DIR__."/partials/header.php";
$user = getUserInfo($db);
?>

	<div class="content">

		<p>Welcome <?= $user["name"]?></p>
		<p>Start page, small profile, share links, news feed. edit links, delete links, comment on links. up and down vote links.</p>

	</div>

</div> <!-- end page -->


<?php require __DIR__."/partials/navigation.php"; ?>

<?php require __DIR__."/partials/footer.php"; ?>
