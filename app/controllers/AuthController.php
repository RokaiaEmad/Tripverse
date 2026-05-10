<?php

require_once '../../core/Database.php';
require_once '../models/User.php';
class AuthController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function register($name, $email, $password)
    {
        $existingUser = $this->userModel
            ->findByEmail($email);

        if ($existingUser && count($existingUser) > 0) {
            return false;
        }
        $user = new User(
            $name,
            $password,
            $email
        );

        return $user->create();
    }

    public function handleRegister()
    {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $_SESSION['old_name'] = $name;
            $_SESSION['old_email'] = $email;

            if (
                empty($name) ||
                empty($email) ||
                empty($password)
            ) {

                $_SESSION['error'] =
                    "Please fill all fields";

                header(
                    "Location: ../views/auth/register.php"
                );

                exit();
            }

            $result = $this->register(
                $name,
                $email,
                $password
            );

            if ($result) {

                $_SESSION['user_id'] = $result;

                $_SESSION['user_name'] = $name;

                unset($_SESSION['old_name']);
                unset($_SESSION['old_email']);

                header(
                    "Location: /Tripverse/index.php"
                );

                exit();
            } else {

                $_SESSION['error'] =
                    "Email already exists";

                header(
                    "Location: ../views/auth/register.php"
                );

                exit();
            }
        }
    }

    public function login($email, $password)
    {
        $queryResult = $this->userModel
            ->findByEmail($email);

        if ($queryResult && count($queryResult) > 0) {

            $user = $queryResult[0];

            if (
                password_verify(
                    $password,
                    $user['password']
                )
            ) {

                $_SESSION['user_id'] = $user['id'];

                $_SESSION['user_name'] = $user['name'];

                return true;
            }
        }

        return false;
    }

    public function handleLogin()
    {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (
                empty($email) ||
                empty($password)
            ) {

                $_SESSION['error'] =
                    "Please fill all fields";

                header(
                    "Location: ../views/auth/login.php"
                );

                exit();
            }

            $result = $this->login(
                $email,
                $password
            );

            if ($result) {

                header(
                    "Location: /Tripverse/index.php"
                );

                exit();
            } else {

                $_SESSION['error'] =
                    "Wrong email or password";

                $_SESSION['old_email'] = $email;

                header(
                    "Location: ../views/auth/login.php"
                );

                exit();
            }
        }
    }
    public function logout()
    {
        session_start();

        session_destroy();

        header("Location: /Tripverse/index.php");

        exit();
    }
}


$auth = new AuthController();

if (isset($_GET['action'])) {

    switch ($_GET['action']) {

        case 'login':
            $auth->handleLogin();
            break;

        case 'register':
            $auth->handleRegister();
            break;

        case 'logout':
            $auth->logout();
            break;
    }
}
