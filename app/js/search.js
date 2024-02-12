window.onload = function () {
    fetch('../data/postore-data/posts.json')
        .then(response => response.json())
        .then(posts => {
            posts.forEach(addPostToPage);
            handleSearch(posts);
        })
        .catch(error => console.error('Error fetching posts:', error));
};

function filterPosts(posts, searchTerm) {
    const lowerCaseSearchTerm = searchTerm.toLowerCase();
    return posts.filter(post => {
        const lowerCaseText = post.text.toLowerCase();
        const lowerCaseTags = Array.isArray(post.tags)
            ? post.tags.map(tag => tag.toLowerCase()).join(' ')
            : typeof post.tags === 'string'
                ? post.tags.toLowerCase()
                : '';
        const lowerCaseUserId = post.user_id.toLowerCase();

        return (
            lowerCaseText.includes(lowerCaseSearchTerm) ||
            lowerCaseTags.includes(lowerCaseSearchTerm) ||
            lowerCaseUserId.includes(lowerCaseSearchTerm)
        );
    });
}

function displayFilteredPosts(filteredPosts, searchTerm) {
    const postContainer = document.getElementById('post-container');
    const searchResultsInfo = document.getElementById('search-results-info');

    // Hide all posts
    const allPosts = postContainer.querySelectorAll('.post-card');
    allPosts.forEach(post => {
        post.style.display = 'none';
    });

    // Show only filtered posts
    filteredPosts.forEach(post => {
        const postId = `post-${post.id}`;
        const filteredPost = document.getElementById(postId);

        if (filteredPost) {
            // Ensure that .post-tags element exists within filteredPost
            const postTagsElement = filteredPost.querySelector('.post-tags');

            if (postTagsElement) {
                const tagsHTML = post.tags.map(tag => {
                    const highlightedTag = tag.toLowerCase().includes(searchTerm.toLowerCase()) ? `<span class="highlight">${tag}</span>` : tag;
                    return `<span class="alert alert-primary d-flex align-items-center m-1 p-0 px-1">${highlightedTag}</span>`;
                }).join('');

                // Update the tags with highlighted search term
                postTagsElement.innerHTML = tagsHTML;

                // Apply a background color to the highlighted tags
                const highlightElements = postTagsElement.querySelectorAll('.highlight');
                highlightElements.forEach(element => {
                    element.style.backgroundColor = 'yellow'; // Change this to the desired color
                    element.style.color = 'black'; // Change this to the desired color

                });
            }

            // Highlight the user_id button in pink
            const userIdButton = filteredPost.querySelector('.user-id');
            if (userIdButton) {
                const highlightedUserId = post.user_id.toLowerCase().includes(searchTerm.toLowerCase())
                    ? `<span class="highlight">${post.user_id}</span>`
                    : post.user_id;

                // Update the user_id button with highlighted search term
                userIdButton.innerHTML = `<i class="bi bi-person"></i> ${highlightedUserId}`;

                // Apply a background color to the user_id button
                userIdButton.style.backgroundColor = 'orange';
                userIdButton.style.color = 'black';

            }

            filteredPost.style.display = 'block';
        }
    });

    // Display the quantity of tags or words reached
    searchResultsInfo.innerHTML = `Results: ${filteredPosts.length} posts found for "${searchTerm}"`;
}



function hideAllPosts(postContainer) {
    const allPosts = postContainer.querySelectorAll('.post-card');
    allPosts.forEach(post => {
        post.style.display = 'none';
    });
}

function highlightTags(filteredPost, tags, searchTerm) {
    const postTagsElement = filteredPost.querySelector('.post-tags');

    if (postTagsElement) {
        const tagsHTML = tags.map(tag => {
            const highlightedTag = tag.toLowerCase().includes(searchTerm.toLowerCase()) ? `<span class="highlight">${tag}</span>` : tag;
            return `<span class="alert alert-primary d-flex align-items-center m-1 p-0 px-1">${highlightedTag}</span>`;
        }).join('');

        postTagsElement.innerHTML = tagsHTML;

        const highlightElements = postTagsElement.querySelectorAll('.highlight, alert-primary');
        highlightElements.forEach(element => {
            element.style.backgroundColor = '#FFFF00'; // Change this to the desired color
        });
    }
}

function showResultsInfo(searchResultsInfo, count, searchTerm) {
    searchResultsInfo.innerHTML = `Results: ${count} posts found for "${searchTerm}"`;
}

function handleSearch(posts) {
    const searchForm = document.getElementById('search-form');
    const searchInput = document.getElementById('search-input');
    const navbarTogglerButton = document.querySelector('.navbar-toggler');
    const searchResultsInfo = document.getElementById('search-results-info');
    const postContainer = document.getElementById('post-container');
    const allPosts = postContainer.querySelectorAll('.post-card');

    const originalButtonContent = navbarTogglerButton.innerHTML;
    const originalButtonClass = navbarTogglerButton.className;

    function clearSearchResults() {
        searchInput.value = '';
        navbarTogglerButton.innerHTML = originalButtonContent;
        navbarTogglerButton.className = originalButtonClass;
    
        allPosts.forEach(post => {
            const highlightElements = post.querySelectorAll('.highlight');
            highlightElements.forEach(element => {
                element.style.backgroundColor = '';
                element.style.color = '';
            });
    
            const userIdButton = post.querySelector('.user-id');
            if (userIdButton) {
                userIdButton.style.backgroundColor = '';
                userIdButton.style.color = ''; 
            }
        });
    
        allPosts.forEach(post => {
            post.style.display = 'block';
        });
    
        searchResultsInfo.innerHTML = '';
    }
    

    navbarTogglerButton.addEventListener('click', clearSearchResults);

    searchForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const searchTerm = searchInput.value.trim();

        if (searchTerm !== '') {
            const filteredPosts = filterPosts(posts, searchTerm);
            displayFilteredPosts(filteredPosts, searchTerm);
        } else {
            allPosts.forEach(post => {
                post.style.display = 'block';
            });

            navbarTogglerButton.innerHTML = originalButtonContent;
            navbarTogglerButton.className = originalButtonClass;

            searchResultsInfo.innerHTML = '';
        }
    });

    searchInput.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            clearSearchResults();
        }
    });

    searchInput.addEventListener('click', function (event) {
        event.stopPropagation();
        clearSearchResults();
    });
}
