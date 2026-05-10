<?php

session_start();

require_once __DIR__ . '/../models/Activity.php';
require_once __DIR__ . '/../models/TripMember.php';
require_once __DIR__ . '/../models/ItineraryVersion.php';

class ActivityController
{
    private $activityModel;

    public function __construct()
    {
        $this->activityModel = new Activity();
    }

    public function create()
    {
        $title = trim($_POST['title']);
        $location = trim($_POST['location']);

        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];

        $transport_mode =
    $_POST['transport_mode'];

        $created_by = $_SESSION['user_id'];

        // Validation
        if (
            empty($title) ||
            empty($location) ||
            empty($start_time) ||
            empty($end_time)
        ) {

            $_SESSION['activity_error'] =
                'All fields are required';

            header(
                'Location: ' .
                $_SERVER['HTTP_REFERER']
            );

            exit;
        }

        // Create new itinerary version
        $versionModel = new ItineraryVersion();

        $new_version_id =
            $versionModel->createNewVersion(
                $_POST['itinerary_id'],
                $_SESSION['user_id'],
                'Added activity: ' . $title
            );

        // Create activity in NEW version
        $result = $this->activityModel->create([

    'version_id' => $new_version_id,

    'title' => $title,

    'location' => $location,

    'start_time' => $start_time,

    'end_time' => $end_time,

    'transport_mode' => $transport_mode,

    'created_by' => $created_by

]);

        // INVALID TIME
        if($result == 'invalid_time'){

            $_SESSION['activity_error'] =
                'End time must be after start time';

            header(
                'Location: ' .
                $_SERVER['HTTP_REFERER']
            );

            exit;
        }

        // TIME CONFLICT
        if($result == 'time_conflict'){

            $_SESSION['activity_error'] =
                'Another activity already exists in this time';

            header(
                'Location: ' .
                $_SERVER['HTTP_REFERER']
            );

            exit;
        }

        // Success
        header(
            'Location: /Tripverse/app/controllers/ItineraryController.php?action=show&itinerary_id=' .
            $_POST['itinerary_id']
        );

        exit;
    }

    public function confirm()
    {
        if(!isset($_SESSION['user_id'])){
            die('Please login first');
        }

        $activity_id = intval($_POST['activity_id']);

        $activity =
            $this->activityModel
            ->getActivityWithTrip($activity_id);

        if(!$activity){
            die('Activity not found');
        }

        // Already confirmed
        if($activity['status'] == 'confirmed'){

            $_SESSION['activity_error'] =
                'Activity already confirmed';

            header('Location: ' . $_SERVER['HTTP_REFERER']);

            exit;
        }

        $tripMember = new TripMember();

        // Leader check
        $isLeader = $tripMember->isLeader(
            $activity['trip_id'],
            $_SESSION['user_id']
        );

        if(!$isLeader){

            $_SESSION['activity_error'] =
                'Only leader can confirm activities';

            header('Location: ' . $_SERVER['HTTP_REFERER']);

            exit;
        }

        // Leader own activity auto-confirmed
        if($activity['created_by'] == $_SESSION['user_id']){

            $_SESSION['activity_error'] =
                'Your activities are auto-confirmed';

            header('Location: ' . $_SERVER['HTTP_REFERER']);

            exit;
        }

        // Confirm activity
        $this->activityModel->confirm($activity_id);

        $_SESSION['activity_success'] =
            'Activity confirmed successfully';

        header('Location: ' . $_SERVER['HTTP_REFERER']);

        exit;
    }
}

$controller = new ActivityController();

$action = $_GET['action'] ?? '';

if ($action == 'create') {
    $controller->create();
}

if ($action == 'confirm') {
    $controller->confirm();
}