<?php

session_start();

require_once __DIR__ . '/../models/ItineraryVersion.php';

class VersionController
{
    private $versionModel;

    public function __construct()
    {
        $this->versionModel =
            new ItineraryVersion();
    }

    public function rollback()
    {
        $itinerary_id =
            intval($_POST['itinerary_id']);

        $version_id =
            intval($_POST['version_id']);

        $this->versionModel->rollback(
            $itinerary_id,
            $version_id
        );

        header(
            'Location: /Tripverse/app/controllers/ItineraryController.php?action=show&itinerary_id=' .
            $itinerary_id
        );

        exit;
    }
}

$controller = new VersionController();

$action = $_GET['action'] ?? '';

if($action == 'rollback'){
    $controller->rollback();
}