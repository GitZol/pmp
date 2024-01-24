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
                    <input type='submit' value='Delete Project' class='btn btn-danger btn-sm'>
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
                    echo "<li id='task-details'>{$task_row["TaskName"]} - {$task_row["Description"]} - {$task_row["DueDate"]} - {$task_row["Priority"]} - {$task_row["Status"]}
                        <button class='btn btn-secondary btn-sm more-btn' data-taskid='{$task_row["TaskID"]}' data-taskname='{$task_row["TaskName"]}'>More</button>
                        <form action='delete_task.php' method='post' style='display: inline-block;'>
                            <input type='hidden' name='taskID' value='{$task_row["TaskID"]}'>
                            <input type='submit' value='Delete' class='btn btn-danger btn-sm'>
                        </form>
                    </li>";
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
                                    <input id='taskPriority' name='taskPriority' class='form-control' type='text'/>
                                </div>
                                <div class='mb-3'>
                                    <label for='taskStatus'>Status:</label>
                                    <input id='taskStatus' name='taskStatus' class='form-control' type='text'/>
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
        const deleteProjectForms = document.querySelectorAll('[id^="deleteProjectForm"]');

        addTaskButtons.forEach(function(addTaskBtn) {
            addTaskBtn.addEventListener('click', function() {
                var projectID = addTaskBtn.getAttribute('id').replace('addTaskBtn', '');
                var addTaskModal = document.getElementById('addTaskModal' + projectID);
                var modal = new bootstrap.Modal(addTaskModal);
                modal.show();
            });
        });

        deleteProjectForms.forEach(function(deleteForm) {
            deleteForm.addEventListener('submit', function(event) {
                event.preventDefault();
                var confirmDelete = confirm("Are you sure you want to delete this project?");
                if (confirmDelete) {
                    var formData = new FormData(deleteForm);

                    fetch('delete_project.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        if (response.ok) {
                            console.log("Project deleted successfully");
                            window.location.href = 'home.php';
                        } else if (response.status === 403){
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




