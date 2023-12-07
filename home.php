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
    
</head>
<body>
    <div class="container">
         <?php include 'fetch_projects.php'; ?>

         <button id="showCreateProjectForm" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProjectModal"> Create New Project
        </button>

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
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const showFormButton = document.getElementById('showCreateProjectForm');
        const createProjectModal = new bootstrap.Modal(document.getElementById('createProjectModal'));

        showFormButton.addEventListener('click', function() {
            createProjectModal.show();
        });
    });
</script>

</body>
</html>