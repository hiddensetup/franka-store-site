<?php
session_start();

// Include the users file
include 'easypost-users.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Validate the input (add more validation if needed)
    if (empty($username) || empty($password) || empty($email)) {
        $error = 'Username, password, and email are required';
    } else {
        // Check if the username already exists
        if (isset($allowedUsers[$username])) {
            $error = 'Username already exists. Choose a different one.';
        } else {
            // Additional validation for email format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Invalid email format';
            } else {
                // Additional validation for username format (allow only letters and numbers)
                if (!ctype_alnum($username)) {
                    $error = 'Username can only contain letters and numbers';
                } else {
                    // Check if the email is from allowed domains (gmail, hotmail, icloud)
                    $allowedEmailDomains = ['gmail.com', 'hotmail.com', 'icloud.com'];
                    $emailDomain = substr(strrchr($email, "@"), 1);

                    if (!in_array($emailDomain, $allowedEmailDomains)) {
                        $error = 'Only registration with Gmail, Hotmail, or iCloud email is allowed';
                    } else {
                        // Hash the password before storing it
                        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                        // Add the new user to the allowedUsers array
                        $allowedUsers[$username] = [
                            'name' => $username,
                            'email' => $email,
                            'password' => $hashedPassword,
                        ];

                        // Save the updated allowedUsers array back to the users.php file
                        file_put_contents('easypost-users.php', '<?php $allowedUsers = ' . var_export($allowedUsers, true) . ';');

                        // Redirect to login page after successful registration
                        header('Location: easypost-login.php');
                        exit();
                    }
                }
            }
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">

    <title>Register</title>
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
                        <h5 class="h1 mt-5 text-center"> <i class="bi bi-person-bounding-box"></i> Sign up</h5>
                        <p class="text-center lead mb-5">Create an account to start using easypost </p>
                        <?php if (isset($error)) : ?>
                            <div class="alert alert-danger rounded-4 slide" role="alert">
                                <?= htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>
                        <form method="POST" action="" id="registrationForm">
                            <div id="step1">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <div class="input-group flex-nowrap">
                                        <input type="email" class="rounded-4 form-control" name="email" id="email" aria-label="Email" required>
                                    </div>
                                    <small class="form-text text-muted">*Sign up for your account using Gmail, Outlook, or iCloud for a seamless experience.</small>

                                </div>
                                <button type="button" class="w-100 btn btn-primary rounded-4 " onclick="nextStep('step1', 'step2')">Sign up</button>
                            </div>

                            <div id="step2" style="display: none;">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <div class="input-group flex-nowrap">
                                        <span class="input-group-text">@</span>
                                        <input type="text" class="rounded-end-4 form-control" id="username" name="username" aria-label="Username" required autocomplete="username">

                                    </div>
                                </div>
                                <button type="button" class="w-100 btn btn-primary rounded-4 " onclick="nextStep('step2', 'step3')">Next</button>
                            </div>

                            <div id="step3" style="display: none;">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="rounded-4 form-control" id="password" name="password" required autocomplete="current-password">
                                </div>
                                <button type="submit" class="w-100 rounded-4  btn btn-primary">Register</button>
                            </div>
                            <hr>

                            <div class="text-center d-flex flex-column mt-3">
                                <a href="easypost-login.php" class="mb-3 link">Already have an account?</a>
                                <small> Frankastore® - 2023</small>


                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
        function nextStep(currentStep, nextStep) {
            if (validateStep(currentStep)) {
                hideElement(currentStep);
                showElement(nextStep);
            }
        }

        function showAlert(message) {
            removeExistingAlerts();

            const alertDiv = createAlert(message);
            const container = document.querySelector('.container');
            container.insertBefore(alertDiv, container.firstChild);
        }

        function validateStep(step) {
            if (step === 'step1') {
                return validateEmail();
            } else if (step === 'step2') {
                return validateUsername();
            } else {
                return true; // No validation for other steps
            }
        }

        function validateEmail() {
            const emailInput = document.getElementById('email');
            const emailValue = emailInput.value.trim();

            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailValue)) {
                showAlert('Invalid email format');
                // Check if the input is required before clearing and focusing
                if (!emailInput.required) {
                    emailInput.value = '';
                    emailInput.focus();
                }
                return false;
            }

            return true;
        }

        function validateUsername() {
            const usernameInput = document.getElementById('username');
            const usernameValue = usernameInput.value.trim();

            if (!/^[a-zA-Z0-9]+$/.test(usernameValue)) {
                showAlert('Username can only contain letters and numbers');
                // Check if the input is required before clearing and focusing
                if (!usernameInput.required) {
                    usernameInput.value = '';
                    usernameInput.focus();
                }
                return false;
            }

            return true;
        }

        function hideElement(elementId) {
            const element = document.getElementById(elementId);
            if (element) {
                element.style.display = 'none';
            }
        }

        function showElement(elementId) {
            const element = document.getElementById(elementId);
            if (element) {
                element.style.display = 'block';
            }
        }

        function removeExistingAlerts() {
            const existingAlerts = document.querySelectorAll('.alert');
            existingAlerts.forEach(alert => alert.parentNode.removeChild(alert));
        }

        function createAlert(message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger';
            alertDiv.innerHTML = message;
            return alertDiv;
        }

        function clearAndFocusInput(inputElement) {
            inputElement.value = '';
            inputElement.focus();
        }

        // Add event listeners for "Enter" key on input fields
        document.getElementById('email').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                nextStep('step1', 'step2');
            }
        });

        document.getElementById('username').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                nextStep('step2', 'step3');
            }
        });

        document.getElementById('password').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('registrationForm').submit();
            }
        });
    </script>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/theme.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</body>

</html>