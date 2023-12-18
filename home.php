<?php
session_start();
if (!isset($_SESSION["UserID"])){
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <script src="home.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

<body>
    <!-- The navbar for this page is in fetch_projects.php -->
    <?php include 'fetch_projects.php'; ?>
    <div class="container">
        <button id="showCreateProjectForm" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProjectModal"> Create New Project</button>
        
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
                        <input type="text" id="projectName" class="form-control" name="projectName" required><br>

                        <label for="description">Description:</label>
                        <textarea id="description" class="form-control" name="description" required></textarea><br>

                        <label for="startDate">Start Date:</label>
                        <input type="date" id="startDate" class="form-control" name="startDate" required><br>

                        <label for="endDate">End Date:</label>
                        <input type="date" id="endDate" class="form-control" name="endDate" required><br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input type="submit" name="createProject" class="btn btn-primary" value="Create Project">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>