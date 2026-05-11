<?php

session_start();

require_once __DIR__ . '/../models/Activity.php';
require_once __DIR__ . '/../models/RSVP.php';
require_once __DIR__ . '/../models/ItineraryVersion.php';
require_once __DIR__ . '/../models/Trip.php';
require_once __DIR__ . '/../models/TripMember.php';

$itinerary_id = $_GET['itinerary_id'] ?? null;

if (!$itinerary_id) {

    echo json_encode([
        'success' => false
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| LOAD ACTIVE VERSION
|--------------------------------------------------------------------------
*/

$versionModel = new ItineraryVersion();

$version =
    $versionModel->getActiveVersion($itinerary_id);

/*
|--------------------------------------------------------------------------
| LOAD ALL VERSIONS
|--------------------------------------------------------------------------
*/

$versions =
    $versionModel->getAllVersions($itinerary_id);

if (!$version) {

    echo json_encode([
        'success' => false
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| LOAD ACTIVITIES
|--------------------------------------------------------------------------
*/

$activityModel = new Activity();

$activitiesByDay =
    $activityModel->getGroupedByDay(
        $version['version_id']
    );

/*
|--------------------------------------------------------------------------
| LOAD ITINERARY + TRIP
|--------------------------------------------------------------------------
*/

$tripModel = new Trip();

$itinerary =
    $tripModel->getItinerary($itinerary_id);

if (!$itinerary) {

    echo json_encode([
        'success' => false
    ]);

    exit;
}

$trip =
    $tripModel->getById(
        $itinerary['trip_id']
    );

if (!$trip) {

    echo json_encode([
        'success' => false
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| LEADER CHECK
|--------------------------------------------------------------------------
*/

$memberModel = new TripMember();

$isLeader =
    $memberModel->isLeader(
        $trip['id'],
        $_SESSION['user_id']
    );

/*
|--------------------------------------------------------------------------
| GENERATE TRIP DAYS
|--------------------------------------------------------------------------
*/

$tripDays = [];

$current =
    strtotime($trip['start_date']);

$end =
    strtotime($trip['end_date']);

$dayNumber = 1;

while ($current <= $end) {

    $tripDays[] = [

        'day_number' => $dayNumber,

        'date' =>
        date(
            'Y-m-d',
            $current
        )
    ];

    $current =
        strtotime(
            '+1 day',
            $current
        );

    $dayNumber++;
}

/*
|--------------------------------------------------------------------------
| CALCULATE VISIBLE COUNTS
|--------------------------------------------------------------------------
*/

$responseModel = new RSVP();

$visibleCountByDate = [];

foreach ($activitiesByDay as $date => $acts) {

    $count = 0;

    foreach ($acts as $act) {

        if ($isLeader) {

            $count++;

        } else {

            $resp =
                $responseModel->getUserResponse(
                    $act['activity_id'],
                    $_SESSION['user_id']
                );

            if ($resp !== 'not_going') {

                $count++;
            }
        }
    }

    $visibleCountByDate[$date] = $count;
}

/*
|--------------------------------------------------------------------------
| RENDER HTML
|--------------------------------------------------------------------------
*/

ob_start();

include __DIR__ . '/../views/itinerary/partials/dayPanels.php';

$html = ob_get_clean();

/*
|--------------------------------------------------------------------------
| RETURN JSON
|--------------------------------------------------------------------------
*/

echo json_encode([

    'success' => true,

    'html' => $html

]);