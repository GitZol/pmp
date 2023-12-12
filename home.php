<?php
session_start();
if (!isset($_SESSION["UserID"])){
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous" defer></script>
    <style>
        .side-menu {
            position: fixed;
            top: 0;
            right: -45%; 
            width: 45%;
            height: 100%;
            background-color: #f5f5f5;
            transition: right 0.3s ease; 
        }

        .side-menu.show {
            right: 0; 
        }
        
        .task-info {
            padding: 20px;
        }

        #taskName {
            margin-top: 20px;
            font-size: 1.5rem;
        }

        .comment-box {
            border: 1px solid #ccc;
            margin-bottom: 10px;
            padding: 10px;
        }

        .username-timestamp {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .comment-content {
            margin-top: 5px;
        }

        .comment-list {
            max-height: 500px;
            overflow-y: auto;
        }

        .dropdown {
            position: relative;
        }

        .dropbtn {
            background-color: #f5f5f5;
            color: #333;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
        }

        .dropdown-container {
            position: relative;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #f9f9f9;
            min-width: 120px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: #333;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown-content.show {
            display: block;
            right: 0;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown:hover .dropbtn {
            background-color: #f1f1f1;
        }

    </style>
</head>
<body>
    <div class="container">
        <?php include 'fetch_projects.php'; ?>

        <button id="showCreateProjectForm" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProjectModal"> Create New Project
        </button>
        
        <div id="sideMenu" class="side-menu">
            <button id="closeSideMenu" class="btn btn-danger" style="position: absolute; top: 10px; left: 10px;">X</button>

            <div class="task-info">
                <div id="taskNameDisplay" style="margin-top: 40px;"></div>

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="comments-tab" data-bs-toggle="tab" data-bs-target="#comments" type="button" role="tab" aria-controls="comments" aria-selected="true">Comments</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="files-tab" data-bs-toggle="tab" data-bs-target="#files" type="button" role="tab" aria-controls="files" aria-selected="true">Files</button>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="comments" role="tabpanel" aria-labelledby="comments-tab">
                        <h3>Comments</h3>
                        <div>
                            <textarea id="newComment" class="form-control" placeholder="Add a comment" rows="3"></textarea>
                            <button id="addCommentBtn" class="btn btn-primary mt-2">Add Comment</button>
                        </div>

                        <div id="commentsContainer"></div>
                    </div>
                    <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="files-tab">
                        <h3>Files</h3>
                        <div id="filesContainer"></div>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="createProjectModal" tabindex="-1" aria-labelledby="createProjectModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createProjectModalLabel">Create New Project</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                <form id="createProjectForm" action="create_project.php" method="post">
                    <div class="modal-body">
                        <label for="projectName">Project Name:</label>
                        <input type="text" id="projectName" class="form-control" name="projectName" required><br><br>

                        <label for="description">Description:</label>
                        <textarea id="description" class="form-control" name="description" required></textarea><br><br>

                        <label for="startDate">Start Date:</label>
                        <input type="date" id="startDate" class="form-control" name="startDate" required><br><br>

                        <label for="endDate">End Date:</label>
                        <input type="date" id="endDate" class="form-control" name="endDate" required><br><br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input type="submit" name="createProject" class="btn btn-primary" value="Create Project">
                    </div>
                </form>
            </div>
        </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        var currentTaskID;
        var moreButtons = document.querySelectorAll('.more-btn');
        moreButtons.forEach(function(button){
            button.addEventListener('click', function(event){
                currentTaskID = event.target.getAttribute('data-taskid');
                var taskName =event.target.getAttribute('data-taskname');
                var sideMenu =document.querySelector('.side-menu');
                var tabToDisplay =document.querySelector('#comments-tab');

                if (sideMenu){
                    sideMenu.classList.add('show');
                }
                var taskNameElement =document.getElementById('taskNameDisplay');
                taskNameElement.textContent = taskName;

                fetchComments(currentTaskID);
                
                //make comments default sidemenu display
                if (tabToDisplay) {
                    var tabContent = document.querySelector('#myTabContent');
                    tabContent.querySelector('.tab-pane.show.active').classList.remove('show', 'active');
                    document.querySelector(tabToDisplay.getAttribute('data-bs-target')).classList.add('show', 'active');
                }

            });
        });

        document.getElementById('comments-tab').addEventListener('click', function(event) {
            var tabContent = document.querySelector('#myTabContent');
            tabContent.querySelector('.tab-pane.show.active').classList.remove('show', 'active');
            document.querySelector('#comments').classList.add('show', 'active');
        });

        document.getElementById('files-tab').addEventListener('click', function(event) {
            var tabContent = document.querySelector('#myTabContent');
            tabContent.querySelector('.tab-pane.show.active').classList.remove('show', 'active');
            document.querySelector('#files').classList.add('show', 'active');
        });


        function fetchComments(currentTaskID) {
            fetch('fetch_comments.php?taskID=' + currentTaskID)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(commentsData => {
                    var commentsContainer = document.getElementById('commentsContainer');
                    commentsContainer.innerHTML = '';

                    var commentList = document.createElement('div');
                    commentList.classList.add('comment-list');

                    if (commentsData && commentsData.length > 0) {
                        commentsData.sort((a, b) => new Date(b.Timestamp) - new Date(a.Timestamp));

                        commentsData.forEach(comment => {
                            var commentBox =document.createElement('div');
                            commentBox.classList.add('comment-box');
                            commentBox.id = 'comment_' +comment.CommentID;

                            var dropdownContainer = document.createElement('div');
                            dropdownContainer.classList.add('dropdown-container');

                            var usernameTimestamp = document.createElement('div');
                            usernameTimestamp.classList.add('username-timestamp');

                            var usernameElement = document.createElement('strong');
                            usernameElement.textContent =comment.Username;
                            usernameTimestamp.appendChild(usernameElement);

                            var timestampElement = document.createElement('span');
                            var commentTime = new Date(comment.Timestamp);
                            timestampElement.textContent = timeSince(commentTime);

                            timestampElement.setAttribute('title', commentTime.toLocaleString('en-US', {day: '2-digit', month: 'short', hour: 'numeric', minute: 'numeric', hour12: true}));
                            usernameTimestamp.appendChild(timestampElement);
                            
                            
                            var icon =document.createElement('i');
                            icon.classList.add('fas', 'fa-ellipsis-v', 'dropdown-icon');
                            icon.style.marginLeft = '5px';
                            usernameTimestamp.appendChild(icon);

                            var dropdownContent =document.createElement('div');
                            dropdownContent.classList.add('dropdown-content');

                            var editOption = document.createElement('a');
                            editOption.textContent = 'Edit';
                            
                            editOption.addEventListener('click', function(){
                                enableEditMode('comment_' + comment.CommentID, comment.Content);
                            });
                            

                            var deleteOption = document.createElement('a');
                            deleteOption.textContent = 'Delete';

                            deleteOption.addEventListener('click', function(){
                                var confirmation = confirm("Are you sure you want to delete this comment?");

                                if (confirmation){
                                    deleteComment(comment.CommentID);
                                }
                            });
                            

                            dropdownContent.appendChild(editOption);
                            dropdownContent.appendChild(deleteOption);

                            // dropdownContainer.appendChild(usernameTimestamp);
                            dropdownContainer.appendChild(dropdownContent);

                            icon.addEventListener('click', function(e){
                                e.stopPropagation();

                                dropdownContent.classList.toggle('show');

                                const allDropdowns =document.querySelectorAll('.dropdown-content');
                                allDropdowns.forEach(dropdown => {
                                    if (dropdown !== dropdownContent) {
                                        dropdown.classList.remove('show');
                                    }
                                });
                            });
                            
                            var contentElement = document.createElement('div');
                            contentElement.classList.add('comment-content');
                            contentElement.textContent = comment.Content;
                            
                            commentBox.appendChild(usernameTimestamp);
                            commentBox.appendChild(dropdownContainer);
                            commentBox.appendChild(contentElement);

                            commentList.appendChild(commentBox);
                            
                        });
                        document.addEventListener('click', function(e){
                            var dropdowns = document.querySelectorAll('.dropdown-content');
                            var isClickInsideDropdown = false;
                            var isClickInsideSideMenu = e.target.closest('.side-menu');

                            dropdowns.forEach(function(dropdown) {
                                if (dropdown.contains(e.target)) {
                                    isClickInsideDropdown = true;
                                }
                            });

                            if (!isClickInsideDropdown && !isClickInsideSideMenu) {
                                dropdowns.forEach(function(dropdown) {
                                    dropdown.classList.remove('show');
                                });
                            }
                        });

                        commentsContainer.appendChild(commentList);
                    } else {
                        commentsContainer.textContent = 'No comments found for this task.';
                    }
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });
        }

        document.getElementById('addCommentBtn').addEventListener('click', function() {
            var commentContent = document.getElementById('newComment').value.trim();

            if (currentTaskID && commentContent !== '') {
                addComment(currentTaskID, commentContent);
            } else {
                alert('Please select a task and enter a comment.');
            }
        });

        

        function addComment(currentTaskID, commentContent) {
            var formData = new FormData();
            formData.append('taskID', currentTaskID);
            formData.append('comment', commentContent);

            fetch('add_comment.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Comment added successfully
                    fetchComments(currentTaskID); // Update comments after adding
                    document.getElementById('newComment').value = ''; // Clear the comment field
                } else {
                    alert('Error adding comment: ' + data.message);
                }
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
        }

        function deleteComment(commentID) {
            var formData = new FormData();
            formData.append('commentID', commentID);

            fetch('delete_comment.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then (data => {
                if (data.success) {
                    fetchComments(currentTaskID);
                } else {
                    alert('Error deleting comment: ' + data.message);
                }
            })
            .catch(error=>{
                console.error('There was a problem with the fetch operation: ', error);
            });           
        }

        function enableEditMode(commentID, content) {
            var commentIDNumeric =commentID.split('_')[1];
            
            var commentBox = document.getElementById(commentID);
            if (!commentBox) {
                console.error("Comment box not found");
                return;
            }

            var contentElement = commentBox.querySelector('.comment-content');
            if (!contentElement) {
                console.error("Content element not found");
                return;
            }

            var editInput = document.createElement('textarea');
            editInput.classList.add('form-control');
            editInput.value = content;

            var saveButton = document.createElement('button');
            saveButton.textContent = 'Save';
            saveButton.disabled = true;

            editInput.addEventListener('input', function(){
                saveButton.disabled =editInput.value.trim() === '';
            });

            saveButton.addEventListener('click', function(){
                if (editInput.value.trim() !== '') {
                    editComment(commentIDNumeric, editInput.value);
                } else {
                    alert ("Comment can't be blank");
                }
            });

            var cancelButton =document.createElement('button');
            cancelButton.textContent = 'Cancel';
            cancelButton.addEventListener('click', function(event) {
                event.stopPropagation();
                contentElement.innerHTML = content;
            });

            contentElement.innerHTML = '';
            contentElement.appendChild(editInput);
            contentElement.appendChild(saveButton);
            contentElement.appendChild(cancelButton);
        }

        function editComment(commentID, commentContent) {
            console.log('Comment ID:' ,commentID);
            console.log('Comment Content:', commentContent);

            var formData = new FormData();
            formData.append('commentID', commentID);
            formData.append('commentContent', commentContent);

            fetch('edit_comment.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok){
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then (data => {
                if (data.success) {
                    fetchComments(currentTaskID);
                } else {
                    alert('Error editing comment: ' + data.message);
                }
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation: ', error);
            });
        }

        const closeSideMenuButton =document.getElementById('closeSideMenu');
        const sideMenu = document.querySelector('.side-menu');

        closeSideMenuButton.addEventListener('click', function(){
            sideMenu.classList.remove('show');
        });

        const showFormButton = document.getElementById('showCreateProjectForm');
        const createProjectModal = new bootstrap.Modal(document.getElementById('createProjectModal'));

        showFormButton.addEventListener('click', function() {
            createProjectModal.show();
        });
        // Close expanded project when clicking outside the expanded section
        document.addEventListener('click', function(event) {
            const isOutsideProject = !event.target.closest('.collapse');
            if (isOutsideProject) {
                const expandedProjects = document.querySelectorAll('.collapse.show');
                expandedProjects.forEach(function(project) {
                    if (!project.classList.contains('side-menu')) {
                        const collapse = new bootstrap.Collapse(project);
                        collapse.hide();
                    }
                });
            }
        });

        // Prevent collapse when clicking inside the side menu on elements that are not collapse triggers
        document.querySelector('.side-menu').addEventListener('click', function(event) {
            const isInsideSideMenu = event.target.closest('.side-menu');
            const isCollapseTrigger = event.target.closest('[data-bs-toggle="collapse"]');
            if (isInsideSideMenu && !isCollapseTrigger) {
                event.stopPropagation();
            }
        });

        function timeSince(date) {
            const seconds = Math.floor((new Date() - date) / 1000);

            let interval = seconds / 31536000;
            if (interval > 1) {
                return Math.floor(interval) + " years ago";
            }
            interval = seconds / 2592000;
            if (interval > 1) {
                return Math.floor(interval) + " months ago";
            }
            interval = seconds / 86400;
            if (interval > 1) {
                return Math.floor(interval) + " days ago";
            }
            interval = seconds / 3600;
            if (interval > 1) {
                return Math.floor(interval) + " hours ago";
            }
            interval = seconds / 60;
            if (interval > 1) {
                return Math.floor(interval) + " minutes ago";
            }
            return Math.floor(seconds) + " seconds ago";
        }

    });
</script>
</body>
</html>