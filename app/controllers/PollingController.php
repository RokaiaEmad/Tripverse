<?php

session_start();

require_once __DIR__ . '/../models/Polling.php';

class PollingController
{
    private $pollingModel;

    public function __construct()
    {
        $this->pollingModel =
            new Polling();
    }


    public function index()
    {
        $itinerary_id =
            $_GET['itinerary_id'];

        $_SESSION['active_tab'] =
            'polls';

        header(
            'Location: /Tripverse/app/controllers/ItineraryController.php?action=show&itinerary_id=' .
            $itinerary_id
        );

        exit;
    }


    public function create()
    {
        $this->pollingModel->create([

            'trip_id' =>
                $_POST['trip_id'],

            'created_by_member_id' =>
                $_SESSION['user_id'],

            'title' =>
                $_POST['title'],

            'type' =>
                $_POST['type'],

            'is_anonymous' =>
                $_POST['is_anonymous'] ?? 0,

            'deadline' =>
                $_POST['deadline']
        ]);

        $_SESSION['active_tab'] =
            'polls';

        header(
            'Location: /Tripverse/app/controllers/ItineraryController.php?action=show&itinerary_id=' .
            $_POST['trip_id']
        );

        exit;
    }
}



$controller =
    new PollingController();

$action =
    $_GET['action'] ?? '';

if ($action === 'index') {

    $controller->index();
}

if ($action === 'create') {

    $controller->create();
}