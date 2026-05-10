<?php

session_start();

require_once __DIR__ . '/../models/RSVP.php';

class RSVPController
{
    private $responseModel;

    public function __construct()
    {
        $this->responseModel =
            new RSVP();
    }

    public function respond()
    {
        if(!isset($_SESSION['user_id'])){
            die('Please login first');
        }

        $activity_id =
            intval($_POST['activity_id']);

        $status =
            $_POST['status'];

        $this->responseModel->respond(
            $activity_id,
            $_SESSION['user_id'],
            $status
        );

        header('Location: ' . $_SERVER['HTTP_REFERER']);

        exit;
    }
}

$controller = new RSVPController();

$action = $_GET['action'] ?? '';

if($action == 'respond'){
    $controller->respond();
}