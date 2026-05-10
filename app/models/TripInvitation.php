<?php

require_once __DIR__ . '/../../core/Database.php';

class TripInvitation
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function create($trip_id, $token)
    {
        $trip_id = intval($trip_id);

        return $this->db->insert("
            INSERT INTO trip_invitations
            (
                trip_id,
                token
            )
            VALUES
            (
                $trip_id,
                '$token'
            )
        ");
    }

    public function getByToken($token)
    {
        $result = $this->db->select("
            SELECT *
            FROM trip_invitations
            WHERE token = '$token'
            LIMIT 1
        ");

        return $result[0] ?? null;
    }

    public function accept($id)
    {
        $id = intval($id);

        return $this->db->update("
            UPDATE trip_invitations
            SET status = 'accepted'
            WHERE id = $id
        ");
    }
}