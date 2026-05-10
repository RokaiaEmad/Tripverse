<?php

session_start();

require_once __DIR__ . '/../models/Trip.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $controller = new TripController();

    $action = $_POST['action'] ?? '';

    if ($action === 'create_trip') {
        $controller->create();
    }
}

class TripController
{
    public function create()
    {
        // Check login
        if (!isset($_SESSION['user_id'])) {

            header('Location: /Tripverse/app/views/auth/login.php');
            exit;
        }

        $user_id = $_SESSION['user_id'];

        // Get form data
        $trip_name   = trim($_POST['trip_name'] ?? '');
        $destination = trim($_POST['destination'] ?? '');
        $start_date  = $_POST['start_date'] ?? '';
        $end_date    = $_POST['end_date'] ?? '';
        $budget      = floatval($_POST['budget'] ?? 0);

        // Validation
        $errors = [];

        if (!$trip_name) {
            $errors[] = 'Trip name required';
        }

        if (!$destination) {
            $errors[] = 'Destination required';
        }

        if (!$start_date) {
            $errors[] = 'Start date required';
        }

        if (!$end_date) {
            $errors[] = 'End date required';
        }

        if ($budget <= 0) {
            $errors[] = 'Invalid budget';
        }

        if ($end_date < $start_date) {
            $errors[] = 'End date must be after start date';
        }

        // Return if validation failed
        if ($errors) {

            $_SESSION['trip_errors'] = $errors;

            header('Location: /Tripverse/app/views/trip/addTrip.php');

            exit;
        }

        // Upload image
        $image_path = null;

        if (!empty($_FILES['image']['name'])) {

            $upload_dir = __DIR__ . '/../assets/images/trips/';

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $ext = pathinfo(
                $_FILES['image']['name'],
                PATHINFO_EXTENSION
            );

            $filename =
                'trip_' .
                $user_id .
                '_' .
                time() .
                '.' .
                $ext;

            move_uploaded_file(
                $_FILES['image']['tmp_name'],
                $upload_dir . $filename
            );

            $image_path =
                '/Tripverse/app/assets/images/trips/' .
                $filename;
        }

        // Create trip
        $trip = new Trip();

        $itinerary_id = $trip->create([

            'trip_name'   => $trip_name,
            'destination' => $destination,
            'start_date'  => $start_date,
            'end_date'    => $end_date,
            'budget'      => $budget,
            'created_by'  => $user_id,
            'image'       => $image_path,
        ]);

        // Success
        if ($itinerary_id) {

            $_SESSION['trip_success'] =
                'Trip created successfully';

            header(
                'Location: /Tripverse/app/controllers/ItineraryController.php?action=show&itinerary_id='
                . $itinerary_id
            );

        } else {

            $_SESSION['trip_errors'] =
                ['Failed to create trip'];

            header(
                'Location: /Tripverse/app/views/trip/addTrip.php'
            );
        }

        exit;
    }
}