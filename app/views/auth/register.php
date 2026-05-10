<?php

session_start();

$errMsg =
    $_SESSION['error'] ?? '';

$old_name =
    $_SESSION['old_name'] ?? '';

$old_email =
    $_SESSION['old_email'] ?? '';

unset($_SESSION['error']);
unset($_SESSION['old_name']);
unset($_SESSION['old_email']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - TripVerse</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Style -->
    <style>
        body {
            background: url('https://images.unsplash.com/photo-1501785888041-af3ef285b470') no-repeat center center/cover;
            height: 100vh;
        }

        .overlay {
            background: rgba(0, 0, 0, 0.5);
            height: 100vh;
        }

        .register-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            color: white;
        }

        .form-control {
            background: transparent;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .form-control::placeholder {
            color: #ddd;
        }

        .btn-custom {
            background: linear-gradient(45deg, #007bff, #00c6ff);
            border: none;
        }

        .btn-custom:hover {
            opacity: 0.9;
        }
    </style>
</head>

<body>

    <div class="overlay d-flex justify-content-center align-items-center">
        <div class="col-md-4">
            <div class="card register-card p-4 shadow">

                <h2 class="text-center mb-4">Create Account</h2>

                <form action="../../controllers/AuthController.php?action=register" method="POST">
                    <?php
                    if (!empty($errMsg)) {
                    ?>
                        <div class="alert alert-danger text-center">
                            <?php echo $errMsg;
                            ?>
                        </div>
                    <?php
                    }
                    ?>
                    <div class="mb-3">
                        <input type="text" name="name" class="form-control" placeholder="Full Name"
                            value="<?= htmlspecialchars($old_name) ?>">
                    </div>

                    <div class="mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email Address"
                            value="<?= htmlspecialchars($old_email) ?>">
                    </div>

                    <div class="mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password">
                    </div>

                    <button type="submit" class="btn btn-custom w-100 py-2">
                        Register
                    </button>

                    <p class="text-center mt-3">
                        Already have an account? <a href="login.php" class="text-info">Login</a>
                    </p>

                </form>

            </div>
        </div>
    </div>
    <script>
        // Refresh → clear all fields and error
        if (performance.getEntriesByType("navigation")[0].type === "reload") {
            document.querySelector('input[name="name"]').value = "";
            document.querySelector('input[name="email"]').value = "";
            document.querySelector('input[name="password"]').value = "";
            document.querySelector('.alert')?.remove();
        }

        // Typing → hide error message
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('input', function() {
                document.querySelector('.alert')?.remove();
            });
        });
    </script>
</body>

</html>