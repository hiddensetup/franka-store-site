<?php
session_start();

// Include the users file
include 'postore-users.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = $_POST['password'];

    // Check if the provided input (username or email) exists in the allowed users
    $userFound = false;
    foreach ($allowedUsers as $userData) {
        // Allow login with either username or email
        if ($userData['name'] === $input || $userData['email'] === $input) {
            // Validate username (contains only letters and numbers)
            if (preg_match('/^[a-zA-Z0-9]+$/', $userData['name'])) {
                // Verify the password
                if (password_verify($password, $userData['password'])) {
                    // Password is correct, set session variables and redirect to app.php
                    $_SESSION['user_id'] = $userData['name'];
                    $_SESSION['user_name'] = $userData['name'];
                    header('Location: postore.php');
                    exit();
                } else {
                    $userFound = true; // Password is incorrect
                }
            } else {
                $userFound = true; // Invalid characters in username
            }
            break;
        }
    }

    // Invalid username or password, display an error message
    if ($userFound) {
        $error = 'Error: Invalid user or password';
    } else {
        $error = 'Error: Sorry, something went wrong there.';
    }
}
?>
<!-- rest of the HTML remains unchanged -->

<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">

    <title>Login</title>
</head>

<body class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="container">
        <div class="row">
            <div class="text-center mt-2">
                <button class="border rounded-top-4 rounded-bottom-0 border-bottom-0 btn btn-custom-secondary" onclick="toggleTheme()">
                    <i id="theme-icon" class="bi bi-sun"></i> Theme
                </button>
            </div>
            <div class="col-md-6 offset-md-3">
                <div class="card rounded-4 shadow">
                    <div class="card-body mb-3">
                        <h5 class="h1 mt-5 text-center"> <i class="bi bi-door-open"></i> Hello!</h5>
                        <p class="text-center lead mb-5">Sign in to your Catposting account</p>
                        <?php if (isset($error)) : ?>
                            <div class="alert alert-danger rounded-4 slide" role="alert">
                                <?= htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>
                        <form method="POST" action="" id="loginForm">
                            <div id="step1">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username or Email</label>
                                    <div class="input-group flex-nowrap">
                                        <input type="text" class="form-control rounded-4" name="username" aria-label="Username or Email" required autocomplete="username">
                                    </div>
                                    <small class="form-text text-muted">*Enter your username or email associated with your account.</small>
                                </div>
                                <button type="button" class="w-100 btn btn-primary rounded-4" onclick="nextStep('step1', 'step2')">Sign In</button>
                            </div>

                            <div id="step2" style="display: none;">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control rounded-4" id="password" name="password" required autocomplete="current-password" required>
                                    <small class="form-text text-muted">Enter your password to proceed with the login.</small>
                                </div>
                                <button type="submit" class="w-100 btn btn-primary rounded-4">Sign In</button>
                            </div>

                            <div class="text-center mt-2">
                                <a href="postore-register.php" class="btn btn-secondary rounded-4 w-100">Register for free access</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <script>
        function nextStep(currentStep, nextStep) {
            document.getElementById(currentStep).style.display = 'none';
            document.getElementById(nextStep).style.display = 'block';
        }
    </script> -->


    <script>
        function nextStep(currentStep, nextStep) {
            if (currentStep === 'step1') {
                // Check if the input is a valid email or username
                var usernameInput = document.getElementsByName('username')[0].value;
                var emailPattern = /^[a-zA-Z0-9._-]+@(gmail\.com|outlook\.com|icloud\.com|me\.com|yahoo\.com|hotmail\.com)$/;

                // Validate username (contains only letters and numbers)
                var usernamePattern = /^[a-zA-Z0-9]+$/;

                if (!emailPattern.test(usernameInput) && !usernamePattern.test(usernameInput)) {
                    // Show Bootstrap alert
                    var alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-danger rounded-4 slide';
                    alertDiv.innerHTML = '<strong>Error:</strong> Please enter a valid email address or username with allowed characters.';

                    // Remove any existing alerts
                    var existingAlerts = document.querySelectorAll('.alert');
                    existingAlerts.forEach(function(alert) {
                        alert.remove();
                    });

                    document.getElementById('loginForm').prepend(alertDiv);
                    return;
                }
            }

            // Remove any existing alerts
            var existingAlerts = document.querySelectorAll('.alert');
            existingAlerts.forEach(function(alert) {
                alert.remove();
            });

            document.getElementById(currentStep).style.display = 'none';
            document.getElementById(nextStep).style.display = 'block';
        }
    </script>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/theme.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</body>

</html>