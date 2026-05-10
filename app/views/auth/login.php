<?php
session_start();

$errMsg =
    $_SESSION['error'] ?? '';

$old_email =
    $_SESSION['old_email'] ?? '';

unset($_SESSION['error']);
unset($_SESSION['old_email']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login-TripVerse</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            margin: 0;
            background: url('https://images.unsplash.com/photo-1501785888041-af3ef285b470') no-repeat center center/cover;
        }

        .overlay {
            background: rgba(0, 0, 0, 0.55);
            height: 100%;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            border-radius: 15px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            color: #fff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .login-card h2 {
            font-weight: 600;
            margin-bottom: 20px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.15);
            border: none;
            color: #fff;
        }

        .form-control::placeholder {
            color: #ddd;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.2);
            box-shadow: none;
            color: #fff;
        }

        .btn-primary {
            background: linear-gradient(45deg, #1e90ff, #0066ff);
            border: none;
            border-radius: 30px;
            padding: 10px;
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        .logo {
            font-weight: bold;
            font-size: 22px;
            margin-bottom: 20px;
        }

        .link {
            color: #ccc;
            font-size: 14px;
        }

        .link:hover {
            color: #fff;
        }
    </style>
</head>

<body>

    <div class="overlay">
        <div class="login-card text-center">

            <div class="logo">✈️ TripVerse</div>

            <h2>Welcome Back</h2>



            <?php if (!empty($errMsg)) { ?>
                <div class="alert alert-danger">
                    <?php echo $errMsg; ?>
                </div>
            <?php } ?>


            <form
                action="../../controllers/AuthController.php?action=login"
                method="POST">

                <div class="mb-3 text-start">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter your email"
                        value="<?= htmlspecialchars($old_email) ?>">

                </div>

                <div class="mb-3 text-start">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter your password">
                </div>

                <button type="submit" class="btn btn-primary w-100 mt-3">
                    Login
                </button>

                <div class="mt-3">
                    <p>Don't have an account? <a href="register.php" class="link"><strong>Register</strong></a></p>
                </div>

            </form>
        </div>
    </div>
    <script>
        // When refresh → clear all fields and error
        if (performance.getEntriesByType("navigation")[0].type === "reload") {
            document.querySelector('input[name="email"]').value = "";
            document.querySelector('input[name="password"]').value = "";
            document.querySelector('.alert')?.remove();
        }

        // When user starts typing → hide error message
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('input', function() {
                const alert = document.querySelector('.alert');
                if (alert) alert.remove();
            });
        });
    </script>

</html>