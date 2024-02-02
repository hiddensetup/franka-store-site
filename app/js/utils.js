//utils.js
function toggleFields() {
    const postType = document.getElementById('postType').value;
    const imageUploadField = document.getElementById('imageUploadField');
    const videoLinkField = document.getElementById('videoLinkField');

    // Show/hide fields based on the selected post type
    if (postType === 'text') {
        imageUploadField.style.display = 'none';
        videoLinkField.style.display = 'none';
    } else if (postType === 'image') {
        imageUploadField.style.display = 'block';
        videoLinkField.style.display = 'none';
    } else if (postType === 'video') {
        imageUploadField.style.display = 'none';
        videoLinkField.style.display = 'block';
    }
}

function resetForm() {
    // Reset the form fields
    document.getElementById('postText').value = '';
    document.getElementById('postTags').value = '';
    document.getElementById('postImage').value = '';
    document.getElementById('videoLink').value = '';

    // Set the postType select to the default value
    document.getElementById('postType').value = 'text';

    // Hide the image and video fields
    document.getElementById('imageUploadField').style.display = 'none';
    document.getElementById('videoLinkField').style.display = 'none';
}

// Call toggleFields on page load to set initial state
window.onload = function () {
    toggleFields();
};

// Add an event listener for modal close
document.getElementById('addPostModal').addEventListener('hidden.bs.modal', resetForm);
function showToast(message) {
    const toast = new bootstrap.Toast(document.getElementById('toast'), {
        autohide: true,
        delay: 3000
    });
    document.getElementById('toast-message').innerText = message;
    toast.show();
}





function renderTags(tags) {
    let tagArray = [];

    // Check if tags is already an array
    if (Array.isArray(tags)) {
        tagArray = tags;
    } else if (typeof tags === 'string') {
        tagArray = tags.split(',');
    } else {
        // Handle other cases, e.g., when tags is not a string or an array
        console.error('Invalid tags format:', tags);
        return '';
    }

    // Ensure each tag is a string
    const renderedTags = tagArray.map(tag => {
        if (typeof tag !== 'string') {
            console.error('Invalid tag format:', tag);
            return ''; // Skip invalid tags
        }
        return `<span class="color-tag alert alert-primary d-flex align-items-center m-1 p-0 px-1">${tag.trim()}</span> `;
    });

    return renderedTags.join(' ');
}




function formatWhatsAppText(text) {
    // Replace *bold* with <strong>bold</strong>
    text = text.replace(/\*(.*?)\*/g, '<strong>$1</strong>');
    // Replace _italic_ with <em>italic</em>
    text = text.replace(/_(.*?)_/g, '<em>$1</em>');
    // Replace ~strikethrough~ with <del>strikethrough</del>
    text = text.replace(/~(.*?)~/g, '<del>$1</del>');
    // Replace ```code``` with <code>code</code>
    text = text.replace(/```(.*?)```/g, '<code>$1</code>');
    return text;
}

// Function to redirect to the register page
function redirectToRegister() {
    window.location.href = 'easypost-register.php';
}

// Function to show the login modal
function showLoginModal() {
    var loginModal = document.getElementById('loginModal');
    var bootstrapModal = new bootstrap.Modal(loginModal);
    bootstrapModal.show();
}


document.getElementById('loginForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent the default form submission
    loginUser(); // Call the function to handle login
});

function loginUser() {
    // Retrieve username and password from the form
    var username = document.getElementById('loginUsername').value;
    var password = document.getElementById('loginPassword').value;


    fetch('easypost-login_process.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'username=' + encodeURIComponent(username) + '&password=' + encodeURIComponent(password),
    })
        .then(response => response.json())
        .then(data => {
            // Handle the response from the server
            if (data.success) {
                // Login successful, close the modal if needed, or perform other actions
                var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                loginModal.hide();
                location.reload(); // Reload the page after login (optional)
            } else {
                // Login failed, display an error message or take appropriate actions
                alert('Login failed: ' + data.message);
            }
        })
        .catch(error => {
            // Handle errors from the fetch request
            console.error('Error:', error);
        });
}

// Handle form submission on pressing Enter
document.getElementById("loginForm").addEventListener("keypress", function (e) {
    if (e.key === "Enter") {
        loginUser();
    }
});

