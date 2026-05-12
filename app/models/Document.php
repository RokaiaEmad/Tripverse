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
    |----------------------------------------------------------------------
    | CREATE DOCUMENT
    |----------------------------------------------------------------------
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

    /*
    |----------------------------------------------------------------------
    | GET ALL TRIP DOCUMENTS
    |----------------------------------------------------------------------
    */

    public function getTripDocuments($trip_id)
    {
        $trip_id =
            intval($trip_id);

        return $this->db->select("

            SELECT
                documents.*,
                users.name AS owner_name

            FROM documents

            JOIN users
            ON users.id =
            documents.owner_member_id

            WHERE documents.trip_id =
            $trip_id

            ORDER BY
            documents.document_id DESC

        ");
    }

    /*
    |----------------------------------------------------------------------
    | GET VISIBLE DOCUMENTS
    |----------------------------------------------------------------------
    */

    public function getVisibleDocuments(
        $trip_id,
        $user_id,
        $isLeader
    ) {

        $trip_id =
            intval($trip_id);

        $user_id =
            intval($user_id);

        $sql = "

            SELECT
                documents.*,
                users.name AS owner_name

            FROM documents

            JOIN users
            ON users.id =
            documents.owner_member_id

            WHERE documents.trip_id =
            $trip_id

            AND
            (

                documents.owner_member_id =
                $user_id

                OR visibility = 'group'
        ";

        /*
        |------------------------------------------------------------------
        | LEADER CAN SEE LEADER DOCS
        |------------------------------------------------------------------
        */

        if ($isLeader) {

            $sql .= "

                OR visibility = 'leader'

            ";
        }

        $sql .= "

            )

            ORDER BY
            documents.document_id DESC

        ";

        return $this->db->select($sql);
    }

    /*
    |----------------------------------------------------------------------
    | DELETE DOCUMENT
    |----------------------------------------------------------------------
    */

    public function delete($document_id)
    {
        $document_id =
            intval($document_id);

        return $this->db->delete("

            DELETE FROM documents

            WHERE document_id =
            $document_id

        ");
    }

    /*
    |----------------------------------------------------------------------
    | GET SINGLE DOCUMENT
    |----------------------------------------------------------------------
    */

    public function getById($document_id)
    {
        $document_id =
            intval($document_id);

        $result = $this->db->select("

            SELECT *

            FROM documents

            WHERE document_id =
            $document_id

            LIMIT 1

        ");

        return $result[0] ?? null;
    }
}