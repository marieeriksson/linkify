"use strict";

// handle post request for new link post
const submitLinkButton = document.querySelector(".postLink");
if (submitLinkButton) {
	submitLinkButton.addEventListener("click", function(event) {
		event.preventDefault();
		handleNewPost(submitLinkButton);
	});
}

function handleNewPost(submitLinkButton) {
	submitLinkButton.setAttribute("disabled", "true");
	let subject = document.querySelector(".newPostFields input[name=subject]").value;
	let url = document.querySelector(".newPostFields input[name=url]").value;
	let description = document.querySelector(".newPostFields input[name=description]").value;
	let errorMessage = document.querySelector(".jsMessage");
	// check if all fields has content
	if (subject == "" || url == "" || description == "") {
		errorMessage.innerHTML = "Please fill out all fields.";
		errorMessage.classList.add("showError");
		submitLinkButton.removeAttribute("disabled");
	} else {
		// put form input in object
		let postData = new FormData();
		postData.append("subject", subject);
		postData.append("url", url);
		postData.append("description", description);
		postData.append("postLink", "share");
		// fetch php script handling post requests for new posts
		fetch("/app/posts/newpost.php",
		{
			method: "POST",
			body: postData,
			credentials: "same-origin",
		})
		// response after php script have been executed
		.then(function(response) {
			submitLinkButton.removeAttribute("disabled");
			// if error
			if (!response.ok) {
				return response.text().then(function (error) {
					errorMessage.innerHTML = error;
					errorMessage.classList.add("showError");
				});
			} else {
				return response.text().then(function(result) {
					// if success remove error and reset form
					errorMessage.classList.remove("showError");
					document.querySelector(".newPostForm").reset();
					document.querySelector(".newPostWrap").classList.remove("showNewPostForm");
					// display new post
					let newPost = document.createElement("div");
					newPost.innerHTML = result;
					newPost.classList.add("post");
					newPost.classList.add("fadeInPost");
					let allPosts = document.querySelector(".displayPosts");
					allPosts.insertBefore(newPost, allPosts.firstChild);
					let noPosts = document.querySelector(".noPosts");
					if (noPosts) {
						noPosts.remove();
					}
					// add event listeners to new post
					addPostEventListeners(newPost);
				});
			}
		});
	}
}

function addPostEventListeners(newPost) {
	let commentButton = newPost.querySelector(".commentPost");
		if (commentButton) {
		commentButton.addEventListener("click", function(event) {
			event.preventDefault();
			handleCommentPost(commentButton);
		});
	}
	let deleteButton = newPost.querySelector(".deleteButton");
	if (deleteButton) {
		deleteButton.addEventListener("click", function(event) {
			event.preventDefault();
			if (window.confirm("Are you sure you want to delete this post?")) {
				handlePostDelete(deleteButton);
			}
		});
	}
	let newEditButton = newPost.querySelector(".saveEdit");
	if (newEditButton) {
		newEditButton.addEventListener("click", function(event) {
			event.preventDefault();
			handleEditPost(newEditButton);
		});
	}
	let showSettingsButtons = newPost.querySelectorAll(".showPostSettings");
	if (showSettingsButtons) {
		showSettingsButtons.forEach(function(showSettingsButton) {
			showSettingsButton.addEventListener("click", function(event) {
				event.preventDefault();
				showSettings(showSettingsButton);
			});
		});
	}
	let editButton = newPost.querySelector(".editButton");
	if (editButton) {
		editButton.addEventListener("click", function(event) {
			event.preventDefault();
			replacePostWithForm(editButton);
		});
	}
	let upVoteNewPost = newPost.querySelector(".up");
	upVoteNewPost.addEventListener("click", function(event) {
		event.preventDefault();
		handleVote(upVoteNewPost, "up", 1);
	});
	let downVoteNewPost = newPost.querySelector(".down");
	downVoteNewPost.addEventListener("click", function(event) {
		event.preventDefault();
		handleVote(downVoteNewPost, "down", -1);
	});
	let showComments = newPost.querySelector(".commentLink");
	showComments.addEventListener("click", function(event) {
		event.preventDefault();
		clickToShowComments(showComments);
	});
}
