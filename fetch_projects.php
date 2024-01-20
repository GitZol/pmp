<style>
    #task-details {
        list-style-type: none;
    }

    .collapse {
        transition: height 0.3s ease-in-out !important;
    }
</style>



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
                echo "<ul>";
                while ($task_row = $task_query->fetch_assoc()) {
                    echo "<li id='task-details'>" . $task_row["TaskName"] . " - " . $task_row["Description"] . " - " . $task_row["DueDate"] . " - " . $task_row["Priority"] . " - " . $task_row["Status"];

                    echo '<button class="btn btn-secondary btn-sm more-btn" data-taskid="' . $task_row["TaskID"] . '"data-taskname="' . $task_row["TaskName"] . '">More</button>';

                    echo "</li>";

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
            echo "<div id='addTaskModal" . $row["ProjectID"] . "' class='modal fade'>";
            echo '<div class="modal-dialog">';
            echo '<div class="modal-content">';
            echo '<div class="modal-header">';
            echo '<h5 class="modal-title" id="addTaskModalLabel' . $row["ProjectID"] . '">Add Task to ' . $row["ProjectName"] . '</h5>';
            echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
            echo '</div>';
            echo '<form action="add_task.php" method="post">';
            echo '<div class="modal-body">';
            echo '<input type="hidden" name="projectID" value="' . $row["ProjectID"] . '">';
            echo '<div class="mb-3">';
            echo '<label for="taskName">Task Name:</label>';
            echo '<input id="taskName" name="taskName" required="" type="text" class="form-control"/>';
            echo '</div>';
            echo '<div class="mb-3">';
            echo '<label for="taskDescription">Description:</label>';
            echo '<textarea id="taskDescription" name="taskDescription" class="form-control" required=""></textarea>';
            echo '</div>';
            echo '<div class="mb-3">';
            echo '<label for="taskDueDate">Due Date:</label>';
            echo '<input id="taskDueDate" name="taskDueDate" class="form-control" type="date"/>';
            echo '</div>';
            echo '<div class="mb-3">';
            echo '<label for="taskPriority">Priority:</label>';
            echo '<input id="taskPriority" name="taskPriority" class="form-control" type="text"/>';
            echo '</div>';
            echo '<div class="mb-3">';
            echo '<label for="taskStatus">Status:</label>';
            echo '<input id="taskStatus" name="taskStatus" class="form-control" type="text"/>';
            echo '</div>';
            echo '</div>';
            echo '<div class="modal-footer">';
            echo '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>';
            echo '<input name="createTask" type="submit" class="btn btn-success" value="Add Task"/>';
            echo '</div>';
            echo '</form>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<p>You currently have no projects. Create a new one!</p>";
    }

    $mysqli->close();
} else {
    echo "<p>Please log in to view your projects.</p>";
}
?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addTaskButtons = document.querySelectorAll('[id^="addTaskBtn"]');
        addTaskButtons.forEach(function(addTaskBtn) {
            addTaskBtn.addEventListener('click', function() {
            var projectID = addTaskBtn.getAttribute('id').replace('addTaskBtn', '');
            var addTaskModal = document.getElementById('addTaskModal' + projectID);
            var modal = new bootstrap.Modal(addTaskModal);
            modal.show();
        });
    });
});
</script>




