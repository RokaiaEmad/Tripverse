<?php

session_start();

require_once __DIR__ . '/../models/TripMember.php';
require_once __DIR__ . '/../models/TripInvitation.php';
require_once __DIR__ . '/../models/Trip.php';

class TripMemberController
{
    private $memberModel;
    private $inviteModel;
    private $tripModel;

    public function __construct()
    {
        $this->memberModel = new TripMember();
        $this->inviteModel = new TripInvitation();
        $this->tripModel = new Trip();
    }

    public function invite()
    {
        $trip_id = intval($_POST['trip_id']);

        $token = bin2hex(random_bytes(32));

        $this->inviteModel->create(
            $trip_id,
            $token
        );

        $link =
            "http://localhost/Tripverse/app/controllers/TripMemberController.php?action=accept&token=$token";

        require_once
            __DIR__ .
            '/../views/trip/invite-link.php';
    }

    public function remove()
{
    if(!isset($_SESSION['user_id'])){
        die('Please login first');
    }

    $trip_id = intval($_POST['trip_id']);
    $member_id = intval($_POST['member_id']);

    // check leader
    $isLeader =
        $this->memberModel
        ->isLeader(
            $trip_id,
            $_SESSION['user_id']
        );

    if(!$isLeader){
        die('Only leader can remove members');
    }

    // prevent leader removing himself
    if($member_id == $_SESSION['user_id']){
        die('Leader cannot remove himself');
    }

    $this->memberModel
        ->removeMember($trip_id, $member_id);

    header('Location: ' . $_SERVER['HTTP_REFERER']);

    exit;
}

    public function accept()
    {
        if(!isset($_GET['token'])){
            die('Invitation token missing');
        }

        $token = $_GET['token'];

        $invite =
            $this->inviteModel
            ->getByToken($token);

        if(!$invite){
            die('Invalid invitation');
        }

        if(!isset($_SESSION['user_id'])){
            die('You must login first');
        }

        $user_id = $_SESSION['user_id'];

        $trip_id = intval($invite['trip_id']);

        $exists =
            $this->memberModel
            ->isMember($trip_id, $user_id);

        if(!$exists){
            $this->memberModel
                ->addMember($trip_id, $user_id);
        }

        $this->inviteModel
            ->accept($invite['id']);

        $itinerary_id =
            $this->tripModel
            ->getItineraryId($trip_id);

        header(
            'Location: /Tripverse/app/controllers/ItineraryController.php?action=show&itinerary_id=' .
            $itinerary_id
        );

        exit;
    }
}

$controller = new TripMemberController();

$action = $_GET['action'] ?? '';

if($action == 'invite'){
    $controller->invite();
}

if($action == 'accept'){
    $controller->accept();
}

if($action == 'remove'){
    $controller->remove();
}