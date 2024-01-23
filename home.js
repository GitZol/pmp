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
        var activeTabPane = tabContent.querySelector('.tab-pane.show.active');

        if (activeTabPane) {
            activeTabPane.classList.remove('show', 'active');
        }

        var commentsElement = document.querySelector('#comments');
        if (commentsElement) {
            commentsElement.classList.add('show', 'active');
        }
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

    function uploadFiles(currentTaskID, files) {
        const formData = new FormData();
        formData.append('taskID', currentTaskID)

        for (const file of files) {
            formData.append('files[]', file);
        }
    
        fetch('upload_files.php', {
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
                fetchFiles(currentTaskID);
            } else {
                console.error('Error uploading files:', data.message);
            }
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
    }

    function fetchFiles(taskID) {
        const formData = new FormData();
        formData.append('taskID', taskID);
    
        fetch('fetch_files.php', {
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
                const filesContainer = document.getElementById('filesContainer');
                filesContainer.innerHTML = '';
    
                data.files.forEach(file => {
                    const fileElement = document.createElement('div');
                    fileElement.innerHTML = `
                    <div class="file-info">
                        <p>File Name: ${file.FileName}</p>
                        <p>File Type: ${getFileType(file.FileType)}</p>
                        <p>Upload Date: ${file.UploadDate}</p>
                    </div>
                    <div class="file-actions">
                        <a href="${file.FileURL}" target="_blank" rel="noopener noreferrer" class="btn btn-primary"><i class="fas fa-eye"></i> View File</a>
                        <a href="#" class="btn btn-success download-link" data-file-url="${file.FileURL}" download="${file.FileName}"><i class="fas fa-download"></i> Download File</a>
                        <span class="delete-icon" data-file-id="${file.FileID}" title="Delete File"><i class="fas fa-trash-alt"></i></span>
                    </div>
                    <hr>
                    `;
                    filesContainer.appendChild(fileElement);
                });
    
                filesContainer.querySelectorAll('.delete-icon').forEach(deleteIcon => {
                    deleteIcon.addEventListener('click', function (event) {
                        const fileID = event.target.dataset.fileId;
                        deleteFile(fileID);
                    });
                });

                filesContainer.querySelectorAll('.download-link').forEach(downloadLink => {
                    downloadLink.addEventListener('click', function(event) {
                        event.preventDefault();
                        const fileURL = event.target.dataset.fileUrl;
                        downloadFile(fileURL);
                    });
                });
            } else {
                console.error('Error fetching files:', data.message);
            }
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
    }

    function getFileType(fileType) {
        const fileTypesMap = {
            'application/pdf': 'PDF',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document': 'Word',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': 'Excel',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation': 'PowerPoint',
            
        };
        return fileTypesMap[fileType] || fileType;
    }

    function downloadFile(fileURL) {
        const link = document.createElement('a');
        link.href = fileURL;
        link.download = '';
        link.target = '_blank';
        link.click();
        link.remove();
    }

    function deleteFile(fileID) {
        const confirmation = confirm("Are you sure you want to delete this file?");
    
        if (confirmation) {
            const formData = new FormData();
            formData.append('fileID', fileID);
    
            fetch('delete_file.php', {
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
                    console.log(data.message);
                    fetchFiles(currentTaskID);
                } else {
                    alert('Error deleting file: ' + data.message);
                }
            })
            .catch(error => {
                console.error('There was a problem with the delete operation:', error);
            });
        }
    }


    document.getElementById('files-tab').addEventListener('click', function(event) {
        var tabContent = document.querySelector('#myTabContent');
        var activePane = tabContent.querySelector('.tab-pane.show.active');

        if (activePane) {
            activePane.classList.remove('show', 'active');
        }

        var filesElement = document.querySelector('#files');
        if (filesElement) {
            filesElement.classList.add('show', 'active');
        }

        fetchFiles(currentTaskID);
    });

    document.getElementById('uploadFilesBtn').addEventListener('click', function() {
        var fileInput = document.getElementById('fileInput');
        var files = fileInput.files;

        if (currentTaskID && files && files.length > 0) {
            uploadFiles(currentTaskID, files);
        } else {
            alert('Please select a task and choose one or more files to upload.');
        }
    });

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
                
                fetchComments(currentTaskID);
                document.getElementById('newComment').value = ''; 
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
        saveButton.classList.add('btn', 'btn-primary', 'me-2');
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
        cancelButton.classList.add('btn', 'btn-secondary');
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