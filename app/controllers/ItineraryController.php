<?php

session_start();

require_once __DIR__ . '/../models/ItineraryVersion.php';
require_once __DIR__ . '/../models/Activity.php';
require_once __DIR__ . '/../models/Trip.php';
require_once __DIR__ . '/../models/TripMember.php';

class ItineraryController
{
    private $versionModel;
    private $activityModel;
    private $tripModel;
    private $memberModel;

    public function __construct()
    {
        $this->versionModel = new ItineraryVersion();

        $this->activityModel = new Activity();

        $this->tripModel = new Trip();

        $this->memberModel = new TripMember();
    }

    public function show()
    {
        // itinerary id check
        if (!isset($_GET['itinerary_id'])) {

            die('Itinerary ID missing');
        }

        $itinerary_id =
            intval($_GET['itinerary_id']);

        // active version
        $version =
            $this->versionModel
            ->getActiveVersion($itinerary_id);

        if (!$version) {

            die('No active version found');
        }

        // all versions history
        $versions =
            $this->versionModel
            ->getAllVersions($itinerary_id);

        // activities
        $activitiesByDay =
            $this->activityModel
            ->getGroupedByDay(
                $version['version_id']
            );

        // itinerary
        $itinerary =
            $this->tripModel
            ->getItinerary($itinerary_id);

        if (!$itinerary) {

            die('Itinerary not found');
        }

        // trip
        $trip =
            $this->tripModel
            ->getById($itinerary['trip_id']);

        if (!$trip) {

            die('Trip not found');
        }

        // members
        $members =
            $this->memberModel
            ->getMembers($trip['id']);

        // leader check
        $isLeader =
            $this->memberModel
            ->isLeader(
                $trip['id'],
                $_SESSION['user_id']
            );

        // generate trip days
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

        // send data to view
       // send data to view
// send data to view
$data = [

    'trip' => $trip,

    'members' => $members,

    'activitiesByDay' => $activitiesByDay,

    'version' => $version,

    'versions' => $versions,

    'itinerary_id' => $itinerary_id,

    'isLeader' => $isLeader,

    'tripDays' => $tripDays
];

$activeTab =
    $_SESSION['active_tab']
    ?? 'itinerary';

unset($_SESSION['active_tab']);

extract($data);

require_once
    __DIR__ .
    '/../views/itinerary/index.php';
    }
}

$controller = new ItineraryController();

$action = $_GET['action'] ?? '';

if ($action == 'show') {

    $controller->show();
}
