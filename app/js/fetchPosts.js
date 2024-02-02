// fetchPosts.js
async function fetchPosts(userId) {

    try {
        const response = await fetch('../data/easypost-data/posts.json');
        const posts = await response.json();

        // Filter posts based on the logged-in user ID
        const userPosts = posts.filter(post => post.user_id === userId);

        userPosts.forEach(addPostToPage);

    } catch (error) {
        console.error('Error fetching posts:', error);
    }

}

