<?php

session_start();

require_once __DIR__ . '/../models/PackingItem.php';
require_once __DIR__ . '/../models/Trip.php';
require_once __DIR__ . '/../models/TripMember.php';
require_once __DIR__ . '/../models/Activity.php';
require_once __DIR__ . '/../models/ItineraryVersion.php';

class PackingController
{
    private $packingModel;

    private $tripModel;

    private $memberModel;

    public function __construct()
    {
        $this->packingModel =
            new PackingItem();

        $this->tripModel =
            new Trip();

        $this->memberModel =
            new TripMember();
    }

    /*
    |------------------------------------------------------------------
    | INDEX
    |------------------------------------------------------------------
    */

  public function index()
{
    $trip_id =
        intval($_GET['trip_id']);

    /*
    |--------------------------------------------------------------
    | GENERATE SMART ITEMS
    |--------------------------------------------------------------
    */

    $this->generateSuggestions($trip_id);

    $itinerary =
        $this->tripModel
        ->getItineraryByTrip($trip_id);

    $items =
        $this->packingModel
        ->getTripItems($trip_id);

    $_SESSION['packing_items'] =
        $items;

    $_SESSION['active_tab'] =
        'packing';

    header(
        'Location: /Tripverse/app/controllers/ItineraryController.php?action=show&itinerary_id=' .
        $itinerary['itinerary_id']
    );

    exit;
}

    /*
    |------------------------------------------------------------------
    | CREATE ITEM
    |------------------------------------------------------------------
    */

    public function create()
    {
        $this->packingModel->create([

            'trip_id' =>
                $_POST['trip_id'],

            'name' =>
                $_POST['name'],

            'suggested' => 0
        ]);

        header(

            'Location: /Tripverse/app/controllers/PackingController.php?action=index&trip_id=' .
            $_POST['trip_id']

        );

        exit;
    }

    /*
    |------------------------------------------------------------------
    | ASSIGN ITEM
    |------------------------------------------------------------------
    */

    public function assign()
    {
        $success =
            $this->packingModel
            ->assignItem(

                $_POST['item_id'],
                $_POST['user_id']

            );

        /*
        |--------------------------------------------------------------
        | OPTIONAL MESSAGE
        |--------------------------------------------------------------
        */

        if (!$success) {

            $_SESSION['packing_error'] =
                'Item already assigned';
        }

        header(

            'Location: /Tripverse/app/controllers/PackingController.php?action=index&trip_id=' .
            $_POST['trip_id']

        );

        exit;
    }

    /*
    |------------------------------------------------------------------
    | TOGGLE PACKED
    |------------------------------------------------------------------
    */

    public function toggle()
    {
        $this->packingModel
            ->toggleCheck(
                $_GET['item_id']
            );

        header(

            'Location: /Tripverse/app/controllers/PackingController.php?action=index&trip_id=' .
            $_GET['trip_id']

        );

        exit;
    }

    /*
    |------------------------------------------------------------------
    | AUTO SUGGESTIONS
    |------------------------------------------------------------------
    */

    public function generateSuggestions($trip_id)
    {
        $activityModel =
            new Activity();

        $versionModel =
            new ItineraryVersion();

        $itinerary =
            $this->tripModel
            ->getItineraryByTrip($trip_id);

        if (!$itinerary) {

            return;
        }

        $version =
            $versionModel
            ->getActiveVersion(
                $itinerary['itinerary_id']
            );

        if (!$version) {

            return;
        }

        $activities =
            $activityModel
            ->getGroupedByDay(
                $version['version_id']
            );

        $suggestions = [];

        foreach ($activities as $dayActs) {

            foreach ($dayActs as $act) {

                $title =
                    strtolower(
                        $act['title']
                    );

                /*
                |------------------------------------------------------
                | HIKING
                |------------------------------------------------------
                */

                if (

                    strpos($title, 'hike') !== false

                    ||

                    strpos($title, 'mountain') !== false

                ) {

                    $suggestions[] =
                        'Hiking Boots';

                    $suggestions[] =
                        'Jacket';
                }

                /*
                |------------------------------------------------------
                | BEACH
                |------------------------------------------------------
                */

                if (

                    strpos($title, 'beach') !== false

                    ||

                    strpos($title, 'swimming') !== false

                ) {

                    $suggestions[] =
                        'Sunscreen';

                    $suggestions[] =
                        'Swimsuit';
                }

                /*
                |------------------------------------------------------
                | CAMPING
                |------------------------------------------------------
                */

                if (

                    strpos($title, 'camp') !== false

                ) {

                    $suggestions[] =
                        'Tent';

                    $suggestions[] =
                        'Flashlight';
                }
            }
        }

        /*
        |--------------------------------------------------------------
        | REMOVE DUPLICATES
        |--------------------------------------------------------------
        */

        $suggestions =
            array_unique($suggestions);

        /*
        |--------------------------------------------------------------
        | INSERT ITEMS
        |--------------------------------------------------------------
        */

        foreach ($suggestions as $item) {

            $this->packingModel->create([

                'trip_id' =>
                    $trip_id,

                'name' =>
                    $item,

                'suggested' => 1
            ]);
        }
    }

    
}

/*
|----------------------------------------------------------------------
| RUN CONTROLLER
|----------------------------------------------------------------------
*/

$controller =
    new PackingController();

$action =
    $_GET['action']
    ?? 'index';

if ($action === 'index') {

    $controller->index();
}

elseif ($action === 'create') {

    $controller->create();
}

elseif ($action === 'assign') {

    $controller->assign();
}

elseif ($action === 'toggle') {

    $controller->toggle();
}