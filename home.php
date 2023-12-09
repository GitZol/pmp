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
            max-height: 300px;
            overflow-y: auto;
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

                    if (commentsData && commentsData.length > 0) {
                        commentsData.sort((a, b) => new Date(b.Timestamp) - new Date(a.Timestamp));

                        var commentList = document.createElement('div');
                        commentList.classList.add('comment-list');

                        commentsData.forEach(comment => {
                            var commentBox =document.createElement('div');
                            commentBox.classList.add('comment-box');

                            var usernameTimestamp = document.createElement('div');
                            usernameTimestamp.classList.add('username-timestamp');

                            var usernameElement = document.createElement('strong');
                            usernameElement.textContent =comment.Username;
                            usernameTimestamp.appendChild(usernameElement);

                            var timestampElement = document.createElement('span');
                            timestampElement.textContent =comment.Timestamp;
                            usernameTimestamp.appendChild(timestampElement);

                            commentBox.appendChild(usernameTimestamp);

                            var contentElement = document.createElement('div');
                            contentElement.classList.add('comment-content');
                            contentElement.textContent = comment.Content;

                            commentBox.appendChild(contentElement);

                            commentList.appendChild(commentBox);
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

        const projectButtons = document.querySelectorAll('[data-bs-toggle="collapse"]');
        projectButtons.forEach(function(button) {
            button.addEventListener('click', function(event) {
                // Check if the click occurred within the dropdown menu
                const isInsideDropdownMenu = event.target.closest('.dropdown-menu');
                
                if (!isInsideDropdownMenu) {
                    const target = document.querySelector(button.getAttribute('data-bs-target'));
                    const isExpanded = target.classList.contains('show');

                    // Close all currently expanded projects except the side menu
                    const expandedProjects = document.querySelectorAll('.collapse.show');
                    expandedProjects.forEach(function(project) {
                        if (project !== target && !project.classList.contains('side-menu')) {
                            const collapse = new bootstrap.Collapse(project);
                            collapse.hide();
                        }
                    });

                    // Toggle the collapse state for the clicked project
                    const collapse = new bootstrap.Collapse(target);
                    if (isExpanded) {
                        collapse.hide();
                    } else {
                        collapse.show();
                    }
                }
            });
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
        const dropdowns = document.querySelectorAll('.dropdown-menu');
        dropdowns.forEach(function(dropdown) {
            dropdown.addEventListener('click', function(event) {
            event.stopPropagation(); // Prevent event propagation to parent elements
            });
        });

    });
</script>
</body>
</html>