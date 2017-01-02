<!-- post content -->
<div class="postAndVoteWrap">

	<div class="voteWrap">
		<?php $votes = countVotes($db, $post["id"]) ?>
		<form action ="app/posts/votes.php" method="post">
			<input type="hidden" name="postIdForVote" value="<?=$post["id"]?>">
			<input type="hidden" name="postUserIdForVote" value="<?=$post["user_id"]?>">
			<input type="submit" class="up<?=(!checkLogin($db)) ? " notLoggedIn" : ""; ?>" name="up" value="">
			<span class="votes<?=(!checkLogin($db)) ? " notLoggedInVotes" : ""; ?>"><?= (!$votes["sum_votes"] == NULL) ? $votes["sum_votes"] : 0 ?></span>
			<input type="submit" class="down<?=(!checkLogin($db)) ? " notLoggedIn" : ""; ?>" name="down" value="">
		</form>
	</div>

	<div class="postWrap">
		<div class="editMessage"><?php if(isset($editMessage)) echo $editMessage; ?></div>
		<h4><a href="<?=$post["url"]?>"><?=$post["subject"]?></a></h4>
		<div class="postDiv">
			<div class="postContent">
				<p><?=$post["description"]?></p>
				<!-- user content -->
				<div class="postUser">
					<p>Posted by
						<?php // name only link if user is logged in
						if (checkLogin($db)) { ?>
							<a href="profile.page.php/?user=<?=$post["username"]?>">
						<?php }
						echo $post["name"];
						if (checkLogin($db)) { ?>
							</a>
						<?php } ?>
						on <?=date('jS \of M h:i', strtotime($post["published"]))?>.
					</p>
				</div>
			</div>

			<!-- edit form shown on button click, only to logged in user on own posts -->
			<?php if (checkLogin($db) && $post["user_id"] == $_SESSION["login"]["id"]) { ?>
				<div class="editPostForm">
					<form action="app/posts/editpost.php" method="post">
						<input type="hidden" name="postIdForEdit" value="<?=$post["id"]?>">
						<div class="editInputField">
							<label for="editSubject">Subject:</label>
							<input type="text" name="editSubject" value="<?=$post["subject"]?>">
						</div>
						<div class="editInputField">
							<label for="editUrl">Link url:</label>
							<input type="text" name="editUrl" value="<?=$post["url"]?>">
						</div>
						<div class="editInputField">
							<label for="editDescription">Description:</label>
							<input type="text" name="editDescription" value="<?=$post["description"]?>">
						</div>
						<input type="submit" name="saveEdit" value="Save" class="saveEdit">
					</form>
				</div>
			<?php } ?>

			<!-- edit, delete and comment -->
			<div class="postButtons">
				<?php if (checkLogin($db) && $post["user_id"] == $_SESSION["login"]["id"]) { ?>
				<form action="app/posts/editpost.php" method="post">
					<div class="postSettingsButtons">
						<input type="hidden" name="postId" value="<?=$post["id"]?>">
						<button class="editButton">Edit post</button>
						<input type="submit" name="deletePost" value="Delete post" class="deleteButton">
					</div>
					<button class="showPostSettings"><img src="/assets/images/settingswheel.png" /></button>
				</form>
				<?php } ?>
			</div>
		</div>

		<?php if (checkLogin($db)) { ?>
		<form action="app/posts/comments.php" method="post" class="commentForm">
			<div class="inputComment">
				<input type="hidden" name="postId" value="<?=$post["id"]?>">
				<input type="text" name="comment" placeholder="Say something about this...">
				<input type="submit" name="commentPost" value="Submit" class="commentPost">
			</div>
			<!-- user only able to edit or delete own posts -->
		</form>
		<?php } else { ?>
			<p class="loginLink">Wanna discuss this? Please log in first. (Link to login)</p>
		<?php }
		// check if post has comments
		$comments = getComments($db, $post["id"]);
		if ($comments) {
			usort($comments, "sortByDate");?>
			<p class="commentCount"><a href="#" class="commentLink">This post has <?=count($comments)?> <?=count($comments) == 1 ? "comment" : "comments"?>.
				Click here to <span class="readOrClose">read</span> <?=count($comments) == 1 ? "it" : "them"?>.</a></p>
			<div class="comments">
				<?php foreach ($comments as $comment) {
					if ($comment["reply_to"] == NULL) { ?>
						<div class="commentWrap">
							<?php require __DIR__."/../partials/comment.block.php";
							foreach ($comments as $reply) {
							if ($reply["reply_to"] == $comment["id"]) {
								require __DIR__."/../partials/reply.block.php"; ?>
								<?php }} ?>
						</div>
				<?php	}}}

				if (checkLogin($db)) { ?>
				<div class="ReplyForm">
					<form action="app/posts/comments.php" method="post">
						<input type="hidden" name="commentId" value="<?=$comment["id"]?>">
						<input type="hidden" name="postId" value="<?=$post["id"]?>">
						<div class="replyFields">
							<input type="text" name="comment" placeholder="Reply to this...">
							<input type="submit" name="replySubmit" value="Submit">
						</div>
						<button class="replyButton">Reply to this</button>
					</form>
				</div>
				<?php } ?>
			</div>
	</div>
</div>
