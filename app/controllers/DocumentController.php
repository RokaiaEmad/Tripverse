<?php

session_start();

require_once __DIR__ . '/../models/Document.php';

$action = $_GET['action'] ?? '';

if ($action === 'upload') {

    $file = $_FILES['document'];

    $fileName =
        time() . '_' . basename($file['name']);

    $target =
        __DIR__ .
        '/../../app/uploads/documents/' .
        $fileName;

    if (
        move_uploaded_file(
            $file['tmp_name'],
            $target
        )
    ) {

        $documentModel = new Document();

        $documentModel->create([

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

        echo "Uploaded successfully";

    } else {

        echo "Upload failed";
    }
}