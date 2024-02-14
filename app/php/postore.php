<?php
// Start or resume the session
session_start();

// Include the users file
include 'postore-users.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($allowedUsers[$_SESSION['user_id']])) {
    $isAnonymous = true; // Flag to indicate anonymous user
} else {
    $isAnonymous = false;

    // Provide the current user's information to JavaScript
    echo '<script>';
    echo 'const currentUser = ' . json_encode($_SESSION['user_id']) . ';';
    echo '</script>';
}
// Logout logic
if (isset($_POST['logout'])) {
    // Destroy the session and redirect to the login page
    session_destroy();
    header('Location: postore-login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">

    <title>CatPosting | Frankastore</title>

</head>

<body class="fadeIn d-flex m-1 pb-4 pt-3 mt-4 flex-column align-items-center justify-content-space-evenly min-vh-100">
    <nav class="border-top navbar bg-body-tertiary fixed-bottom">
        <div class="container-fluid">
            <button class="ms-1 navbar-toggler p-2 position-relative rounded-4 border-0 focus-ring focus-ring-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <i class="bi fs-5 bi-search"></i>
            </button>

            <?php if ($isAnonymous) { ?>
                <button id="register-btn" class="rounded-4 border focus-ring btn-custom-secondary btn btn-lg focus-ring-secondary" onclick="redirectToRegister()">
                    Create an account
                </button>
            <?php } else { ?>
                <button id="add-post-btn" class="btn btn-bg py-0 focus-ring focus-ring-secondary" data-bs-toggle="modal" data-bs-target="#addPostModal">
                    <i class="bi bi-plus-lg fs-3"></i>
                </button>
            <?php } ?>

            <div class="dropup dropup-center me-2">
                <button class="rotate-gear btn border-0 px-1" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fs-4 bi bi-gear"></i>
                </button>
                <ul class="rounded-4 dropdown-menu dropdown-menu-end">


                    <?php if (!$isAnonymous) { ?>
                        <li>
                            <p class="ps-3 pe-2 mb-1" id="offcanvasNavbarLabel"> <i class="bi bi-person-circle"></i> <?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User Name'; ?> </p>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <button type="" class="dropdown-item" name=""><i class="bi bi-cash-stack"></i> Donate</button>
                        </li>
                        <li>
                            <button class="dropdown-item" onclick="toggleTheme()">
                                <i id="theme-icon" class="bi bi-sun"></i> Theme
                            </button>
                        </li>
                        <li>
                            <form method="post">
                                <button type="submit" class="dropdown-item" name="logout"><i class="bi bi-arrow-right-circle"></i> Logout</button>
                            </form>
                        </li>
                    <?php } ?>
                    <?php if ($isAnonymous) { ?>

                        <li>
                            <button class="dropdown-item" onclick="toggleTheme()">
                                <i id="theme-icon" class="bi bi-sun"></i> Theme
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#loginModal">
                                <i class="bi bi-box-arrow-in-right"></i> Sign in
                            </button>
                        </li>
                    <?php } ?>
                    <li>
                        <button class="dropdown-item" onclick="changelog()">
                            <i id="changelog-icon" class="bi bi-card-list"></i> Changelog
                        </button>
                    </li>
                </ul>
            </div>


            <div class="rounded-bottom-4 offcanvas offcanvas-top" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel"> <i class="bi bi-cat"></i> Welcome, <?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Annon'; ?>!</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <form id="search-form" class="d-flex mt-3" role="search">
                        <input id="search-input" class="rounded-4 form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    </form>
                    </form>
                    <div id="search-results-info" class="mt-2"></div>
                </div>

            </div>
        </div>
    </nav>
    <div id="post-container" class="w-100 max-vh-90 mb-5 pt-4 overflow-auto max-width-700">
        <!-- Posts will be dynamically added here -->
    </div>

    <!-- Modal for deleting a post confirmation -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="rounded-5 modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel"><i class="bi bi-info-circle"></i> Delete Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this post?
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="rounded-4 btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="rounded-4 btn btn-danger" data-bs-dismiss="modal" onclick="confirmDelete()">Delete</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap toast container -->
    <div id="toast" class="rounded-4 toast position-fixed top-10 end-10 zindex-1000 w-auto" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header rounded-top-4">
            <strong class="me-auto">Post Status</strong>
        </div>
        <div class="toast-body" id="toast-message"></div>
    </div>

    <!-- Modal for Login -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="rounded-5 modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Sign in to Catposting</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Your login form goes here -->
                    <form id="loginForm">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username or Email</label>
                            <div class="input-group flex-nowrap">
                                <input type="text" class="rounded-4 form-control focus-ring shadow-0 focus-ring-secondary" name="username" id="loginUsername" aria-label="Username or Email" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="rounded-4 form-control focus-ring shadow-0 focus-ring-secondary" id="loginPassword" name="password" autocomplete="password" required>
                        </div>
                        <button type="button" class="rounded-4 w-100 btn btn-primary" onclick="loginUser()">Sign in</button>
                        <div class="text-center mt-2">
                            <a href="postore-register.php" class="btn btn-secondary rounded-4 w-100">Sign up for free</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal for adding a new post -->
    <div class="modal fade" id="addPostModal" tabindex="-1" aria-labelledby="addPostModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="rounded-5 modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPostModalLabel"><i class="bi bi-plus-lg"></i> Add New Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Your form for adding new posts (text, tags, image) goes here -->
                    <form id="addPostForm">
                        <div class="mb-3">
                            <label for="postText" class="form-label">Write a Post</label>
                            <textarea class="rounded-4 form-control focus-ring shadow-0 focus-ring-secondary" id="postText" name="postText" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="postTags" class="form-label">Add Tags</label>
                            <input type="text" class="rounded-4 form-control focus-ring focus-ring-secondary" id="postTags" name="postTags">
                        </div>
                        <div class="mb-3" id="imageUploadField" style="display: none;">
                            <label for="postImage" class="form-label">Upload Image</label>
                            <input type="file" class="rounded-4 form-control focus-ring focus-ring-secondary" id="postImage" name="postImage" accept="image/*">
                        </div>
                        <div class="mb-3" id="videoLinkField" style="display: none;">
                            <label for="videoLink" class="form-label">YouTube Video Link (optional)</label>
                            <input type="text" class="rounded-4 form-control focus-ring focus-ring-secondary" id="videoLink" name="videoLink" placeholder="Enter YouTube video link">
                        </div>
                        <div class="mb-3">
                            <label for="postType" class="form-label">Select Post Type</label>
                            <select class="form-select" id="postType" onchange="toggleFields()">
                                <option value="text">Text</option>
                                <option value="image">Image</option>
                                <option value="video">Video</option>
                            </select>
                        </div>
                        <button type="button" class="rounded-4 btn w-100 btn-primary" data-bs-dismiss="modal" onclick="submitPost()">Post</button>
                    </form>





                </div>
            </div>
        </div>
    </div>
    <script src="../js/utils.js"></script>
    <script src="../js/postOperations.js"></script>
    <script src="../js/theme.js"></script>
    <script src="../js/navigation.js"></script>
    <script src="../js/fetchPosts.js"></script>
    <script src="../js/search.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

</body>

</html>