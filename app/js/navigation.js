// navigation.js
window.onload = function () {
    fetchPosts(); // Call the fetchPosts function to load posts when the page loads
    fetch('../data/postore-data/posts.json')
        .then(response => response.json())
        .then(posts => posts.forEach(addPostToPage))
        .catch(error => console.error('Error fetching posts:', error));
};

