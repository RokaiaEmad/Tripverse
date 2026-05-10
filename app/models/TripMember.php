<?php

require_once __DIR__ . '/../../core/Database.php';

class TripMember
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getMembers($trip_id)
    {
        $trip_id = intval($trip_id);

        return $this->db->select("
            SELECT
                users.id,
                users.name
            FROM trip_members

            JOIN users
            ON users.id = trip_members.user_id

            WHERE trip_members.trip_id = $trip_id
        ");
    }

    public function isLeader($trip_id, $user_id)
    {
        $trip_id = intval($trip_id);
        $user_id = intval($user_id);

        $result = $this->db->select("
            SELECT *
            FROM trip_members
            WHERE trip_id = $trip_id
            AND user_id = $user_id
            AND role = 'leader'
            LIMIT 1
        ");

        return !empty($result);
    }

    public function isMember($trip_id, $user_id)
    {
        $trip_id = intval($trip_id);
        $user_id = intval($user_id);

        $result = $this->db->select("
            SELECT *
            FROM trip_members
            WHERE trip_id = $trip_id
            AND user_id = $user_id
            LIMIT 1
        ");

        return !empty($result);
    }

    public function addMember($trip_id, $user_id)
    {
        $trip_id = intval($trip_id);
        $user_id = intval($user_id);

        return $this->db->insert("
            INSERT INTO trip_members
            (
                trip_id,
                user_id,
                role
            )
            VALUES
            (
                $trip_id,
                $user_id,
                'member'
            )
        ");
    }

    public function removeMember($trip_id, $user_id)
{
    $trip_id = intval($trip_id);
    $user_id = intval($user_id);

    return $this->db->delete("
        DELETE FROM trip_members
        WHERE trip_id = $trip_id
        AND user_id = $user_id
        AND role != 'leader'
    ");
}
}