//postOperations.js

async function addPostToPage(post) {
    // Scroll to the top, leaving 20% of the page visible
    const windowHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
    const scrollToPosition = Math.max(0, windowHeight * 0.1);
    const postContainer = document.getElementById('post-container');
    const postCard = document.createElement('div');
    const postId = `post-${post.id}`;
    postCard.id = postId;
    postCard.classList.add('col-11', 'shadow', 'post-card', 'mx-auto', 'mb-4', 'border', 'border-1', 'rounded-4', 'overflow-hidden', 'pb-2');
    const formattedText = formatWhatsAppText(post.text);

    let mediaContent = '';

    // Check if the post has an image
    if (post.image) {
        mediaContent = ` <div class="position-absolute m-2 d-flex flex-wrap">
            <p class="small mb-0 flex-wrap justify-content-start d-flex post-tags ">${renderTags(post.tags)}</p>
        </div><img src="${post.image}" alt="Post Image" class="w-100 img-fluid border-bottom border-1">`;
    }

    // Check if the post has a video link
    if (post.videoLink) {
        // Assume the YouTube video link is in the format: https://www.youtube.com/watch?v=VIDEO_ID
        const videoId = post.videoLink.split('v=')[1];
        const embedUrl = `https://www.youtube.com/embed/${videoId}`;
        mediaContent = `<iframe width="100%" height="315" src="${embedUrl}" frameborder="0" allowfullscreen></iframe>`;
    }


    postCard.innerHTML = `
    ${mediaContent}
    <p id="description-post" class="p-2 mb-0" style="white-space: pre-line;">${formattedText}</p>
    <div class="mx-2 mb-0 d-flex flex-wrap justify-content-left">
        
        <div class="small mb-0 w-100 d-flex justify-content-end align-items-center">
            <p class="alert small m-1 p-0 px-1">${post.date}</p>
            <button class="small user-id rounded-5 border m-1 py-0 px-2"><i class="bi bi-person"></i> ${post.user_id}</button>
            <button type="button" onclick="deletePost('${post.id}')" class="py-1 nav-link bi-info-circle m-1"></button>

        </div>
    </div>
    `;


    // Prepend the new post at the beginning of the postContainer
    postContainer.insertBefore(postCard, postContainer.firstChild);
    window.scrollTo(0, scrollToPosition);

}


// Function to extract YouTube video URL from post text
function extractVideoUrl(text) {
    try {
        const url = new URL(text);

        if (url.hostname.includes('youtube.com') || url.hostname === 'youtu.be') {
            const videoId = url.searchParams.get('v') || url.pathname.substr(1);

            // Exclude special YouTube URLs that may be blocked
            if (videoId !== 'generate_204') {
                return `https://www.youtube.com/embed/${videoId}`;
            }
        } else if (url.hostname === 'www.youtube.com' && url.pathname.includes('/embed/')) {
            return text; // Embedded YouTube URL
        }
    } catch (error) {
        console.error('Error extracting YouTube video URL:', error);
    }

    return null;
}



function deletePost(postId) {
    // Open the delete confirmation modal
    const deleteConfirmationModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
    deleteConfirmationModal.show();

    // Set the postId to a data attribute of the modal for reference
    document.getElementById('deleteConfirmationModal').setAttribute('data-post-id', postId);
}

function confirmDelete() {
    // Get the postId from the data attribute of the modal
    const postId = document.getElementById('deleteConfirmationModal').getAttribute('data-post-id');

    // Send a request to delete the post on the server
    fetch('../php/postore-delete_post.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            postId: postId
        }),
    })
        .then(response => {
            // Log the raw response for debugging
            console.log('Raw response:', response);
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                // Remove the post element from the DOM
                const postElement = document.getElementById(`post-${postId}`);
                postElement.remove();
                showToast('Post deleted successfully');
            } else {
                showToast('Error deleting post');
            }
        })
        .catch(error => {
            console.error('Error deleting post:', error);
            showToast('Error deleting post');
        });

    // Close the delete confirmation modal
    const deleteConfirmationModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
    deleteConfirmationModal.hide();
    window.scrollTo({ top: 0, behavior: 'smooth' });

    // Scroll to the top, leaving 20% of the page visible
    const windowHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
    const scrollToPosition = Math.max(0, windowHeight * 0.2);
    window.scrollTo(0, scrollToPosition);


}


async function submitPost() {
    const formData = new FormData(document.getElementById('addPostForm'));

    // Fetch current time from WorldTimeAPI based on your IP
    const worldTimeApiUrl = 'https://worldtimeapi.org/api/ip';
    let date;

    try {
        const response = await fetch(worldTimeApiUrl);
        const data = await response.json();

        // Extract the datetime from the WorldTimeAPI response
        date = new Date(data.datetime).toLocaleString('en-GB', {
            timeZone: data.timezone,
            day: 'numeric',
            month: 'numeric',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    } catch (error) {
        console.error('Error fetching time from WorldTimeAPI:', error);
        showToast('An error occurred while fetching the time. Using local time.');

        // Use local time as a fallback
        date = new Date().toLocaleString('en-GB', {
            day: 'numeric',
            month: 'numeric',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    // Add the date to the form data
    formData.append('date', date);

    fetch('../php/postore-process_post.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            showToast(data.message);
            addPostToPage(data.post);

            // Clear the form
            document.getElementById('addPostForm').reset();

            // Close the modal after posting
            const addPostModal = document.getElementById('addPostModal');
            bootstrap.Modal.getInstance(addPostModal).hide();
        })
        .catch(error => {
            console.error('Error submitting form:', error);
            showToast('An error occurred while submitting the form. Please try again.');
        });
}


// Call submitPost on form submission
document.getElementById('addPostForm').addEventListener('submit', function (event) {
    event.preventDefault();

    submitPost();
});
