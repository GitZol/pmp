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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <script src="home.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div id="messageContainer" class="mt-3"></div>

    <div class="container mt-4">
        
        <form method="post" action="#" id="searchForm" class="mb-3">
            <div class="input-group">
                <input type="text" name="searchTerm" class="form-control" placeholder="Search by username or email">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>
        
        <div id="searchResults"></div>
        
        <?php include 'fetch_projects.php'; ?>

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
                        <button class="nav-link" id="files-tab" data-bs-toggle="tab" data-bs-target="#files" type="button" role="tab" aria-controls="files" aria-selected="false">Files</button>
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
                        <div>
                            <input type="file" id="fileInput" class="form-control mb-2" accept="*" multiple>
                            <button id="uploadFilesBtn" class="btn btn-primary mb-2">Upload Files</button>
                        </div>
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
    <div class="modal fade" id="inviteModal" tabindex="-1" aria-labelledby="inviteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="inviteModalLabel">Select Project to Invite</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="projectSelect">Select Project:</label>
                    <select id="projectSelect" class="form-select">
                        <?php
                        if (!empty($projects)) {
                            foreach ($projects as $project) {
                                echo "<option value='{$project["ProjectID"]}'>{$project["ProjectName"]}</option>";
                            }
                        } else {
                            echo "<option disabled>No projects found</option>";
                        }
                        ?>

                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="sendInvitation()">Send Invitation</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="invitationsModal" tabindex="-1" aria-labelledby="invitationsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="invitationsModalLabel">Invitations</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php
                    include 'invitations.php';
                    foreach ($invitations as $invitation) {
                        echo "<p>Sender: {$invitation['SenderUserName']}</p>
                        <p>Project: {$invitation['ProjectName']}</p>";

                        echo "<form id='invitationForm' action='handle_invitation.php' method='post'>
                        <input type='hidden' name='invitation_id' value='{$invitation['InvitationID']}'>
                                <button type='button' name='accept' class='btn btn-success' onclick='handleInvitation(`accept`)'>Accept</button>
                                <button type='button' name='decline' class='btn btn-danger' onclick='handleInvitation(`decline`)'>Decline</button>
                            </form> 
                        <hr>";
                    }
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
    document.getElementById('searchForm').addEventListener('submit', function(event) {
        event.preventDefault(); 
        const searchTerm = document.querySelector('input[name="searchTerm"]').value;

        fetch('search_users.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                search: searchTerm,
            }),
        })
        .then(response => response.json())
        .then(data => {
            displaySearchResults(data);
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    let selectedUserId;

    function setSelectedUser(userId) {
        selectedUserId =userId;
    }

    function displaySearchResults(results) {
        const searchInput = document.querySelector('input[name="searchTerm"]');
        const searchResultsContainer = document.getElementById('searchResults'); 

        if (results.error) {
            searchResultsContainer.innerHTML = `<p class="text-danger">Error: ${results.error}</p>`;
        } else {
            searchResultsContainer.innerHTML = ''; 
            if (results.length > 0) {
                const resultsBox = document.createElement('div');
                resultsBox.className = 'search-results-box';

                results.forEach(user => {
                    const card = document.createElement('div');
                    card.className = 'card mb-2';
                    card.innerHTML = `
                        <div class="card-body">
                            <p class="card-text">Username: ${user.Username}</p>
                            <p class="card-text">Email: ${user.Email}</p>
                            <button class="btn btn-success invite-btn" data-bs-toggle="modal" data-bs-target="#inviteModal" data-user-id="${user.UserID}" onclick="setSelectedUser(${user.UserID})">Invite</button>
                        </div>
                    `;
                    resultsBox.appendChild(card);

                    
                });

                document.querySelectorAll('.invite-btn').forEach(button => {
                    button.addEventListener('click', function(event) {
                        selectedUserId = this.getAttribute('data-user-id');
                    });
                });

                searchResultsContainer.appendChild(resultsBox);
            } else {
                searchResultsContainer.innerHTML = '<p class="text-muted">No results found</p>';
            }
        }

        const searchInputRect = searchInput.getBoundingClientRect();
        searchResultsContainer.style.position = 'absolute';
        searchResultsContainer.style.left = searchInputRect.left + 'px';
        searchResultsContainer.style.top = (searchInputRect.bottom + window.scrollY) + 'px';
        searchResultsContainer.style.width = searchInputRect.width + 'px';

        document.addEventListener('click', function(event) {
            const searchResultsContainer =document.getElementById('searchResults');
            if (!searchResultsContainer.contains(event.target)) {
                searchResultsContainer.innerHTML = '';
            }
        });
    }

    function sendInvitation() {
        const projectSelect =document.getElementById('projectSelect');
        const selectedProjectID =projectSelect.value;

        if (selectedUserId) {
            fetch('invite_user.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    receiverUserID: selectedUserId,
                    projectID: selectedProjectID
                }),
            })
            .then(response => response.text())
            .then(result => {
                console.log(result);
                $('#inviteModal').modal('hide');
            })
            .catch(error => {
                console.error('Error:', error);
            });
        } else {
            console.error('No user selected');
        }
    }

    function handleInvitation(action) {
        const form =document.getElementById('invitationForm');
        const formData = new FormData(form);
        formData.append(action, '');

        fetch('handle_invitation.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(result => {
            console.log(result);
            $('#invitationsModal').modal('hide');
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

</script>
</body>
</html>