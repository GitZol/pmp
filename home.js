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