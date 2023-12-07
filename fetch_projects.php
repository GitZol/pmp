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

        <?php

        if (isset($_SESSION["Username"])) {
            $username = $_SESSION["Username"];
            echo "<h2>Welcome, $username</h2>";

            $hostname = "127.0.0.1";
            $username = "root";
            $password = "";
            $db_name = "project_management_platform";

            $mysqli = new mysqli($hostname, $username, $password, $db_name);

            if ($mysqli->connect_error) {
                die("Connection failed: ". $mysqli->connect_error);
            }

            $userID = $_SESSION["UserID"];
            $projects_query = $mysqli->query("SELECT ProjectID, ProjectName FROM project WHERE UserID = $userID");

            if ($projects_query->num_rows > 0) {
                echo "<h3>Your Projects:</h3>";
                while ($row = $projects_query->fetch_assoc()) {
                    echo "<div class='mb-3'>";
                    echo "<button class='btn btn-primary' type='button' data-bs-toggle='collapse' data-bs-target='#project" . $row["ProjectID"] . "' aria-expanded='false' aria-controls='project" . $row["ProjectID"] . "'>" . $row["ProjectName"] . "</button>";

                    echo "<form action='delete_project.php' method='post' style='display: inline-block;'>";
                    echo "<input type='hidden' name='projectID' value='" . $row["ProjectID"] . "'>";
                    echo "<input type='submit' value='Delete Project' class='btn btn-danger btn-sm'>";
                    echo "</form>";

                    echo "<div class='collapse' id='project" . $row["ProjectID"] . "'>";
                    echo "<div class='card card-body'>";


                    $projectID = $row["ProjectID"];
                    $task_query = $mysqli->query("SELECT * FROM task WHERE ProjectID = $projectID");

                    if($task_query->num_rows > 0) {
                        echo "<h4> Tasks in " . $row["ProjectName"] . ":</h4>";
                        while ($task_row = $task_query->fetch_assoc()) {
                            echo "<li>" . $task_row["TaskName"] . " - " . $task_row["Description"] . "</li>";
                            echo "<form action='delete_task.php' method='post' style='display: inline-block;'>";
                            echo "<input type='hidden' name='taskID' value='" . $task_row["TaskID"] . "'>";
                            echo "<input type='submit' value='Delete' class='btn btn-danger btn-sm'>";
                            echo "</form>";
                            echo "</li>";
                        }
                        echo "</ul>";

                    } else {
                        echo "<p> No tasks found for " . $row["ProjectName"] . ".</p>";
                    }

                    echo "<button class='btn btn-primary btn-sm' id='addTaskBtn" . $row["ProjectID"] . "'>Add Task</button>";
                    echo "<div id='taskForm" . $row["ProjectID"] . "'style='display: none;'>";
                    echo "<h4>Add Task to " . $row["ProjectName"] . "</h4>";
                    echo "<form action='add_task.php' method='post'>";
                    echo "<input type='hidden' name='projectID' value='" . $row["ProjectID"] . "'>";
                    echo "<div class='mb-3'>";
                    echo "<label for='taskName'>Task Name:</label>";
                    echo "<input id='taskName' name='taskName' required='' type='text'/>";
                    echo "</div>";
                    echo "<div class='mb-3'>";
                    echo "<label for='taskDescription'>Description:</label>";
                    echo "<textarea id='taskDescription' name='taskDescription' required=''></textarea>";
                    echo "</div>";
                    echo "<div class='mb-3'>";
                    echo "<label for='taskDueDate'>Due Date:</label>";
                    echo "<input id='taskDueDate' name='taskDueDate' type='date'/>";
                    echo "</div>";
                    echo "<div class='mb-3'>";
                    echo "<label for='taskPriority'>Priority:</label>";
                    echo "<input id='taskPriority' name='taskPriority' type='text'/>";
                    echo "</div>";
                    echo "<div class='mb-3'>";
                    echo "<label for='taskStatus'>Status:</label>";
                    echo "<input id='taskStatus' name='taskStatus' type='text'/>";
                    echo "</div>";
                    echo "<input name='createTask' type='submit' value='Create Task'/>";
                    echo "</form>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }

            } else {
                echo "<p>No projects found.</p>";
            }

            $mysqli->close();
        } else {
            echo "<p>Please log in to view your projects.</p>";
        }
        ?>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var collapses = document.querySelectorAll('.collapse');

            collapses.forEach(function(collapse){
                collapse.addEventListener('click', function(event){
                    event.stopPropagation();
                });

                var addTaskButton = document.querySelectorAll('[id^="addTaskBtn"]');

                addTaskButton.forEach(function(addTaskBtn){
                    var projectID = addTaskBtn.getAttribute('id').replace('addTaskBtn', '');
                    var taskForm =document.getElementById('taskForm' + projectID);
                
                    addTaskBtn.addEventListener('click', function() {
                        taskForm.style.display = (taskForm.style.display === 'none' || taskForm.style.display === '') ? 'block' : 'none';
                    });
                });

                var button =collapse.previousElementSibling;

                button.addEventListener('click', function(){
                    if (collapse.classList.contains('show')) {
                        collapse.classList.remove('show');
                    } else {
                        collapses.forEach(function(item) {
                            item.classList.remove('show');
                    });
                    collapse.classList.add('show');
                    }
                });
            });

            document.body.addEventListener('click', function(event){
                if (!event.target.closest('.collapse')){
                    collapses.forEach(function(collapse){
                        collaspe.classList.remove('show');
                    });
                }
            });
        });
    </script>

</body>
</html>



