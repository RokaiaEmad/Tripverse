<?php

session_start();

require_once __DIR__ . '/../models/ItineraryVersion.php';
require_once __DIR__ . '/../models/Activity.php';
require_once __DIR__ . '/../models/Trip.php';
require_once __DIR__ . '/../models/TripMember.php';
require_once __DIR__ . '/../models/Document.php';

class ItineraryController
{
    private $versionModel;
    private $activityModel;
    private $tripModel;
    private $memberModel;
    private $documentModel;

    public function __construct()
    {
        $this->versionModel =
            new ItineraryVersion();

        $this->activityModel =
            new Activity();

        $this->tripModel =
            new Trip();

        $this->memberModel =
            new TripMember();

        $this->documentModel =
    new Document();
    }

    public function show()
    {
        /*
        |--------------------------------------------------------------------------
        | ITINERARY ID CHECK
        |--------------------------------------------------------------------------
        */

        if (!isset($_GET['itinerary_id'])) {

            die('Itinerary ID missing');
        }

        $itinerary_id =
            intval($_GET['itinerary_id']);

        /*
        |--------------------------------------------------------------------------
        | ACTIVE VERSION
        |--------------------------------------------------------------------------
        */

        $version =
            $this->versionModel
            ->getActiveVersion($itinerary_id);

        if (!$version) {

            die('No active version found');
        }

        /*
        |--------------------------------------------------------------------------
        | VERSION HISTORY
        |--------------------------------------------------------------------------
        */

        $versions =
            $this->versionModel
            ->getAllVersions($itinerary_id);

        /*
        |--------------------------------------------------------------------------
        | ACTIVITIES
        |--------------------------------------------------------------------------
        */

        $activitiesByDay =
            $this->activityModel
            ->getGroupedByDay(
                $version['version_id']
            );

        /*
        |--------------------------------------------------------------------------
        | ITINERARY
        |--------------------------------------------------------------------------
        */

        $itinerary =
            $this->tripModel
            ->getItinerary($itinerary_id);

        if (!$itinerary) {

            die('Itinerary not found');
        }

        /*
        |--------------------------------------------------------------------------
        | TRIP
        |--------------------------------------------------------------------------
        */

        $trip =
            $this->tripModel
            ->getById($itinerary['trip_id']);

        if (!$trip) {

            die('Trip not found');
        }

        /*
        |--------------------------------------------------------------------------
        | MEMBERS
        |--------------------------------------------------------------------------
        */

        $members =
            $this->memberModel
            ->getMembers($trip['id']);

        /*
        |--------------------------------------------------------------------------
        | DOCUMENTS
        |--------------------------------------------------------------------------
        */

        $documentModel =
            new Document();

        $documents =
            $documentModel->getTripDocuments(
                $trip['id']
            );

        /*
        |--------------------------------------------------------------------------
        | LEADER CHECK
        |--------------------------------------------------------------------------
        */

        $isLeader =
            $this->memberModel
            ->isLeader(
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
        | SEND DATA TO VIEW
        |--------------------------------------------------------------------------
        */

        $documents =
    $this->documentModel
    ->getVisibleDocuments(
        $trip['id'],
        $_SESSION['user_id'],
        $isLeader
    );

    $expenseModel = new Expense();
        $analytics =
           $expenseModel->getCategoryAnalytics($trip['id']);

    /*
|------------------------------------------------------------------
| PACKING ITEMS
|------------------------------------------------------------------
*/

$items =
    $_SESSION['packing_items']
    ?? [];

unset($_SESSION['packing_items']);

        $data = [

            'trip' => $trip,

            'members' => $members,

            'documents' => $documents,

            'activitiesByDay' => $activitiesByDay,

            'version' => $version,

            'versions' => $versions,

            'itinerary_id' => $itinerary_id,

            'isLeader' => $isLeader,

            'tripDays' => $tripDays,
            'documents' => $documents,
            'items' => $items,
            'analytics' => $analytics
        ];

        /*
        |--------------------------------------------------------------------------
        | ACTIVE TAB
        |--------------------------------------------------------------------------
        */

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

/*
|--------------------------------------------------------------------------
| ROUTER
|--------------------------------------------------------------------------
*/

$controller =
    new ItineraryController();

$action =
    $_GET['action'] ?? '';

if ($action == 'show') {

    $controller->show();
}