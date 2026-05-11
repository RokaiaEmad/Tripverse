<?php

require_once __DIR__ . '/../../core/Database.php';

class Polling
{
    private $db;

    public function __construct()
    {
        $this->db =
            Database::getInstance();
    }


    public function create($data)
    {
        $trip_id =
            $data['trip_id'];

        $created_by_member_id =
            $data['created_by_member_id'];

        $title =
            mysqli_real_escape_string(
                $this->db->getConnection(),
                $data['title']
            );

        $type =
            $data['type'];

        $is_anonymous =
            $data['is_anonymous'];

        $deadline =
            $data['deadline'];

        $query = "

            INSERT INTO polls
            (
                trip_id,
                created_by_member_id,
                title,
                type,
                is_anonymous,
                deadline
            )

            VALUES
            (
                '$trip_id',
                '$created_by_member_id',
                '$title',
                '$type',
                '$is_anonymous',
                '$deadline'
            )
        ";

        return $this->db->insert($query);
    }

 
    public function getTripPolls($trip_id)
    {
        $query = "

            SELECT *
            FROM polls

            WHERE trip_id = '$trip_id'

            ORDER BY created_at DESC
        ";

        return $this->db->select($query);
    }
}