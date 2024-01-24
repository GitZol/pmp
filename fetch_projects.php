<div class="container">
<?php
include 'db_connection.php';

if (isset($_SESSION["Username"])) {
    $username = $_SESSION["Username"];
    echo "<h2>Welcome, $username</h2>";

    $userID = $_SESSION["UserID"];
    $createdProjects_query = $mysqli->query("SELECT ProjectID, ProjectName FROM project WHERE UserID = $userID");

    $invitedProjectsQuery = $mysqli->query("SELECT p.ProjectID, p.ProjectName FROM project p
    JOIN user_project up ON p.ProjectID = up.ProjectID
    WHERE up.UserID = $userID");

    $createdProjects = $createdProjects_query->fetch_all(MYSQLI_ASSOC);
    $invitedProjects = $invitedProjectsQuery->fetch_all(MYSQLI_ASSOC);
    $allProjects = array_merge($createdProjects, $invitedProjects);


    if (!empty($allProjects)) {
        echo "<h3>Your Projects:</h3>";
        foreach ($allProjects as $row) {
            echo "<div class='mb-3'>
                <button class='btn btn-primary' type='button' data-bs-toggle='collapse' data-bs-target='#project{$row["ProjectID"]}' aria-expanded='false' aria-controls='project{$row["ProjectID"]}'>{$row["ProjectName"]}</button>
        
                <form id='deleteProjectForm' method='post' style='display: inline-block;'>
                    <input type='hidden' name='projectID' value='{$row["ProjectID"]}'>
                    <span class='delete-icon' data-project-id='{$row["ProjectID"]}' title='Delete Project'>&#128465;</span>
                </form>
        
                <div class='collapse' id='project{$row["ProjectID"]}'>
                    <div class='card card-body'>";


            $projectID = $row["ProjectID"];
            $projects[] = $row;

            $task_query = $mysqli->query("SELECT * FROM task WHERE ProjectID = $projectID");

            if($task_query->num_rows > 0) {
                echo "<h4> Tasks in " . $row["ProjectName"] . ":</h4>
                        <ul>";
                while ($task_row = $task_query->fetch_assoc()) {
                    $dueDateFormatted = date("d-m-Y", strtotime($task_row["DueDate"]));
                    echo "<div class='task-container'>
                        <div class='task-details'>
                            <p class='task-name'>{$task_row["TaskName"]}</p>
                            <p class='task-description'>{$task_row["Description"]}</p>
                            <p class='task-due-date'>Due Date: {$dueDateFormatted}</p>
                            <p class='task-priority'>Priority: {$task_row["Priority"]}</p>
                            <p class='task-status'>Status: {$task_row["Status"]}</p>
                        </div>
                        <div class='task-actions'>
                            <button class='btn btn-secondary btn-sm more-btn' data-taskid='{$task_row["TaskID"]}' data-taskname='{$task_row["TaskName"]}'>More</button>
                            <form action='delete_task.php' method='post' style='display: inline-block;'>
                                <input type='hidden' name='taskID' value='{$task_row["TaskID"]}'>
                                <span class='delete-icon' data-task-id='{$task_row["TaskID"]}' title='Delete Task'>&#128465;</span>
                            </form>
                        </div>
                    </div>";
                }
                echo "</ul>";

            } else {
                echo "<p> No tasks found for " . $row["ProjectName"] . ".</p>";
            }

            echo "<button class='btn btn-primary btn-sm' id='addTaskBtn{$row["ProjectID"]}'>Add Task</button>
            <div id='addTaskModal{$row["ProjectID"]}' class='modal fade'>
                <div class='modal-dialog'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='modal-title' id='addTaskModalLabel{$row["ProjectID"]}'>Add Task to {$row["ProjectName"]}</h5>
                            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                        </div>
                        <form action='add_task.php' method='post'>
                            <div class='modal-body'>
                                <input type='hidden' name='projectID' value='{$row["ProjectID"]}'>
                                <div class='mb-3'>
                                    <label for='taskName'>Task Name:</label>
                                    <input id='taskName' name='taskName' required='' type='text' class='form-control'/>
                                </div>
                                <div class='mb-3'>
                                    <label for='taskDescription'>Description:</label>
                                    <textarea id='taskDescription' name='taskDescription' class='form-control' required=''></textarea>
                                </div>
                                <div class='mb-3'>
                                    <label for='taskDueDate'>Due Date:</label>
                                    <input id='taskDueDate' name='taskDueDate' class='form-control' type='date'/>
                                </div>
                                <div class='mb-3'>
                                    <label for='taskPriority'>Priority:</label>
                                    <select id='taskPriority' name='taskPriority' class='form-control'>
                                        <option value='High'>High</option>
                                        <option value='Medium'>Medium</option>
                                        <option value='Low'>Low</option>
                                    </select>
                                </div>
                                <div class='mb-3'>
                                    <label for='taskStatus'>Status:</label>
                                    <select id='taskStatus' name='taskStatus' class='form-control'>
                                        <option value='Not Started'>Not Started</option>
                                        <option value='In Progress'>In Progress</option>
                                        <option value='Completed'>Completed</option>
                                    </select>
                                </div>
                            </div>
                            <div class='modal-footer'>
                                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                <input name='createTask' type='submit' class='btn btn-success' value='Add Task'/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            </div>
            </div>
            </div>";
        
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
        const deleteProjectIcons = document.querySelectorAll('.delete-icon[data-project-id]');
        const deleteTaskIcons = document.querySelectorAll('.delete-icon[data-task-id]');

        deleteProjectIcons.forEach(function(deleteIcon) {
            deleteIcon.addEventListener('click', function() {
                var confirmDelete = confirm("Are you sure you want to delete this project?");
                if (confirmDelete) {
                    var formData = new FormData();
                    formData.append('projectID', deleteIcon.getAttribute('data-project-id'));

                    fetch('delete_project.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        if (response.ok) {
                            console.log("Project deleted successfully");
                            window.location.href = 'home.php';
                        } else if (response.status === 403) {
                            return response.text();
                        } else {
                            return Promise.reject('Error deleting project. Please try again later.');
                        }
                    })
                    .then(errorMessage => {
                        if (errorMessage) {
                            displayMessage(errorMessage);
                            setTimeout(() => {
                                const container = document.getElementById('messageContainer');
                                container.innerHTML = '';
                            }, 3000);
                        } else {
                            console.error('Error deleting project. Please try again later.');
                        }
                    })
                    .finally(() => {
                        return;
                    });
                }
            });
        });

        deleteTaskIcons.forEach(function(deleteIcon) {
            deleteIcon.addEventListener('click', function() {
                var confirmDelete = confirm("Are you sure you want to delete this task?");
                if (confirmDelete) {
                    var formData = new FormData();
                    formData.append('taskID', deleteIcon.getAttribute('data-task-id'));

                    fetch('delete_task.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        if (response.ok) {
                            console.log("Task deleted successfully");
                            window.location.href = 'home.php';
                        } else if (response.status === 403) {
                            return response.text();
                        } else {
                            return Promise.reject('Error deleting task. Please try again later.');
                        }
                    })
                    .then(errorMessage => {
                        if (errorMessage) {
                            displayMessage(errorMessage);
                            setTimeout(() => {
                                const container = document.getElementById('messageContainer');
                                container.innerHTML = '';
                            }, 3000);
                        } else {
                            console.error('Error deleting task. Please try again later.');
                        }
                    })
                    .finally(() => {
                        return;
                    });
                }
            });
        });


        addTaskButtons.forEach(function(addTaskBtn) {
            addTaskBtn.addEventListener('click', function() {
                var projectID = addTaskBtn.getAttribute('id').replace('addTaskBtn', '');
                var addTaskModal = document.getElementById('addTaskModal' + projectID);
                var modal = new bootstrap.Modal(addTaskModal);
                modal.show();
            });
        });


        function displayMessage(message) {
            const messageDiv = document.createElement('div');
            messageDiv.className = 'alert alert-danger';

            messageDiv.innerHTML = message;

            const container =document.getElementById('messageContainer');
            container.innerHTML = '';
            container.appendChild(messageDiv);
        }
        
    });
</script>