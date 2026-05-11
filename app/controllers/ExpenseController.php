<?php

session_start();

require_once __DIR__ . '/../models/Expense.php';
require_once __DIR__ . '/../models/Trip.php';
require_once __DIR__ . '/../models/TripMember.php';

class ExpenseController
{
    private $expenseModel;
    private $tripModel;
    private $memberModel;

    public function __construct()
    {
        $this->expenseModel = new Expense();

        $this->tripModel = new Trip();

        $this->memberModel = new TripMember();
    }
    public function index()
    {
        $itinerary_id =
            $_GET['itinerary_id'];

        $_SESSION['active_tab'] =
            'expenses';

        header(
            'Location: /Tripverse/app/controllers/ItineraryController.php?action=show&itinerary_id=' .
                $itinerary_id
        );

        exit;
    }


    public function show()
    {
        if (!isset($_GET['trip_id'])) {

            die('Trip ID missing');
        }

        $trip_id = intval($_GET['trip_id']);

        // get trip
        $trip = $this->tripModel->getById($trip_id);

        if (!$trip) {

            die('Trip not found');
        }

        // get trip members
        $members = $this->memberModel->getMembers($trip_id);

        // send data to view
        $data = [

            'trip' => $trip,

            'members' => $members
        ];

        extract($data);

        require_once
            __DIR__ .
            '/../views/expense/addExpense.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // create expense
            $expenseId = $this->expenseModel->createExpense($_POST);

            // selected members
            $members = $_POST['members'];

            // total expense amount
            $amount = floatval($_POST['amount']);

            // divide equally
            $splitAmount = $amount / count($members);

            // save splits
            foreach ($members as $memberId) {

                $this->expenseModel->addSplit(

                    $expenseId,
                    $memberId,
                    $splitAmount
                );
            }

            header(
                "Location: ../controllers/ExpenseController.php?action=show&trip_id=" .
                    $_POST['trip_id']
            );

            exit;
        }
    }
}

$controller = new ExpenseController();

$action = $_GET['action'] ?? '';

if ($action === 'index') {

    $controller->index();
}

if ($action === 'show') {

    $controller->show();
}

if ($action === 'store') {

    $controller->store();
}
