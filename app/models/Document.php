<?php

require_once __DIR__ . '/../../core/Database.php';

class Document
{
    private $db;

    public function __construct()
    {
        $this->db =
            Database::getInstance();
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE DOCUMENT
    |--------------------------------------------------------------------------
    */

    public function create($data)
    {
        $trip_id =
            intval($data['trip_id']);

        $owner_member_id =
            intval($data['owner_member_id']);

        $file_path =
            trim($data['file_path']);

        $type =
            trim($data['type']);

        $visibility =
            trim($data['visibility']);

        return $this->db->insert("

            INSERT INTO documents
            (
                trip_id,
                owner_member_id,
                file_path,
                type,
                visibility
            )

            VALUES
            (
                $trip_id,
                $owner_member_id,
                '$file_path',
                '$type',
                '$visibility'
            )

        ");
    }
}