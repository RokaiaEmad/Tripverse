<?php

session_start();

require_once __DIR__ . '/../models/Document.php';

class DocumentController
{
    private $documentModel;

    public function __construct()
    {
        $this->documentModel =
            new Document();
    }

    /*
    |--------------------------------------------------------------------------
    | OPEN DOCUMENTS TAB
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $itinerary_id =
            $_GET['itinerary_id'];

        $_SESSION['active_tab'] =
            'documents';

        header(
            'Location: /Tripverse/app/controllers/ItineraryController.php?action=show&itinerary_id=' .
            $itinerary_id
        );

        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | UPLOAD DOCUMENT
    |--------------------------------------------------------------------------
    */

    public function upload()
    {
        $file =
            $_FILES['document'];

        $fileName =
            time() . '_' . basename($file['name']);

        $target =
            __DIR__ .
            '/../uploads/documents/' .
            $fileName;

        if (
            move_uploaded_file(
                $file['tmp_name'],
                $target
            )
        ) {

            $this->documentModel->create([

                'trip_id' =>
                    $_POST['trip_id'],

                'owner_member_id' =>
                    $_SESSION['user_id'],

                'file_path' =>
                    $fileName,

                'type' =>
                    $_POST['type'],

                'visibility' =>
                    $_POST['visibility']
            ]);

            $_SESSION['active_tab'] =
                'documents';

            header(
                'Location: /Tripverse/app/controllers/ItineraryController.php?action=show&itinerary_id=' .
                $_POST['trip_id']
            );

            exit;
        }

        echo "Upload failed";
    }
    public function delete()
{
    $document_id =
        intval($_GET['document_id']);

    $itinerary_id =
        intval($_GET['itinerary_id']);

    $document =
        $this->documentModel
        ->getById($document_id);

    if (!$document) {

        die('Document not found');
    }

    /*
    |------------------------------------------------------------------
    | OWNER ONLY
    |------------------------------------------------------------------
    */

    if (
        $document['owner_member_id']
        != $_SESSION['user_id']
    ) {

        die('Unauthorized');
    }

    /*
    |------------------------------------------------------------------
    | DELETE FILE
    |------------------------------------------------------------------
    */

    $file =
        __DIR__ .
        '/../uploads/documents/' .
        $document['file_path'];

    if (file_exists($file)) {

        unlink($file);
    }

    /*
    |------------------------------------------------------------------
    | DELETE DB ROW
    |------------------------------------------------------------------
    */

    $this->documentModel
        ->delete($document_id);

    /*
    |------------------------------------------------------------------
    | BACK
    |------------------------------------------------------------------
    */

    $_SESSION['active_tab'] =
        'documents';

    header(

        'Location: /Tripverse/app/controllers/ItineraryController.php?action=show&itinerary_id=' .
        $itinerary_id

    );

    exit;
}
}

/*
|--------------------------------------------------------------------------
| ROUTER
|--------------------------------------------------------------------------
*/

$controller =
    new DocumentController();

$action =
    $_GET['action'] ?? '';

if ($action === 'index') {

    $controller->index();
}

if ($action === 'upload') {

    $controller->upload();
}

elseif ($action == 'delete') {

    $controller->delete();
}