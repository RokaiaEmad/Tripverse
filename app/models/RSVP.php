<?php

require_once __DIR__ . '/../../core/Database.php';

class RSVP
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function respond(
        $activity_id,
        $user_id,
        $status
    ) {

        $activity_id = intval($activity_id);
        $user_id = intval($user_id);

        $exists = $this->db->select("
            SELECT *
            FROM activity_rsvps
            WHERE activity_id = $activity_id
            AND user_id = $user_id
            LIMIT 1
        ");

        // UPDATE EXISTING RSVP
        if($exists){

            return $this->db->update("
                UPDATE activity_rsvps
                SET status = '$status'
                WHERE activity_id = $activity_id
                AND user_id = $user_id
            ");
        }

        // INSERT NEW RSVP
        return $this->db->insert("
            INSERT INTO activity_rsvps
            (
                activity_id,
                user_id,
                status
            )
            VALUES
            (
                $activity_id,
                $user_id,
                '$status'
            )
        ");
    }

    public function getUserResponse(
        $activity_id,
        $user_id
    ) {

        $activity_id = intval($activity_id);
        $user_id = intval($user_id);

        $result = $this->db->select("
            SELECT status
            FROM activity_rsvps
            WHERE activity_id = $activity_id
            AND user_id = $user_id
            LIMIT 1
        ");

        return $result[0]['status'] ?? null;
    }
}