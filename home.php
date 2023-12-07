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
        .create-project-form {
            display: none;
        }
    </style>
    
</head>
<body>
    <div class="container">
         <?php include 'fetch_projects.php'; ?>

         <button id="showCreateProjectForm" class="btn btn-primary">Create New Project</button>

          <form id="createProjectForm" class="create-project-form" action="create_project.php" method="post">
            <label for="projectName">Project Name:</label>
            <input type="text" id="projectName" class="form-control" name="projectName" required><br><br>

            <label for="description">Description:</label>
            <textarea id="description" class="form-control" name="description" required></textarea><br><br>

            <label for="startDate">Start Date:</label>
            <input type="date" id="startDate" class="form-control" name="startDate" required><br><br>

            <label for="endDate">End Date:</label>
            <input type="date" id="endDate" class="form-control" name="endDate" required><br><br>

            <input type="submit" name="createProject" class="btn btn-primary" value="Create Project">
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const showFormButton = document.getElementById('showCreateProjectForm');
            const createProjectForm = document.getElementById('createProjectForm');

            showFormButton.addEventListener('click', function() {
                createProjectForm.classList.toggle('create-project-form');
            });
        });
    </script>

</body>
</html>