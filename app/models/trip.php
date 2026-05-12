<?php

require_once __DIR__ . '/../../core/Database.php';

class Trip
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function create(array $data)
    {
        $trip_name   = $data['trip_name'];
        $destination = $data['destination'];
        $start_date  = $data['start_date'];
        $end_date    = $data['end_date'];
        $budget      = floatval($data['budget']);
        $created_by  = intval($data['created_by']);
        $image       = $data['image'] ?? null;

        $image_val = $image ? "'$image'" : "NULL";

        // Create trip
        $trip_id = $this->db->insert("
            INSERT INTO
             trips
            (
                trip_name,
                destination,
                start_date,
                end_date,
                budget,
                created_by,
                image,
                created_at
            )
            VALUES
            (
                '$trip_name',
                '$destination',
                '$start_date',
                '$end_date',
                $budget,
                $created_by,
                $image_val,
                NOW()
            )
        ");

        if ($trip_id) {

            // Add creator as leader
            $this->db->insert("
                INSERT INTO trip_members
                (
                    trip_id,
                    user_id,
                    role,
                    created_at
                )
                VALUES
                (
                    $trip_id,
                    $created_by,
                    'leader',
                    NOW()
                )
            ");

            // Create itinerary
            $itinerary_id = $this->db->insert("
                INSERT INTO itineraries
                (
                    trip_id,
                    current_version_id
                )
                VALUES
                (
                    $trip_id,
                    NULL
                )
            ");

            // Create first itinerary version
            $version_id = $this->db->insert("
                INSERT INTO itinerary_versions
                (
                    itinerary_id,
                    version_number,
                    is_active,
                    created_by,
                    created_at
                )
                VALUES
                (
                    $itinerary_id,
                    1,
                    1,
                    $created_by,
                    NOW()
                )
            ");

            // Update itinerary current version
            $this->db->update("
                UPDATE itineraries
                SET current_version_id = $version_id
                WHERE itinerary_id = $itinerary_id
            ");

            return $itinerary_id;
        }

        return false;
    }

    public function getByUser($user_id)
{
    $user_id = intval($user_id);

    return $this->db->select("
        SELECT DISTINCT
            trips.*,
            itineraries.itinerary_id,
            trip_members.role

        FROM trip_members

        JOIN trips
        ON trips.id = trip_members.trip_id

        LEFT JOIN itineraries
        ON itineraries.trip_id = trips.id

        WHERE trip_members.user_id = $user_id

        ORDER BY trips.created_at DESC
    ");
}
public function getItineraryId($trip_id)
{
    $trip_id = intval($trip_id);

    $result = $this->db->select("
        SELECT itinerary_id
        FROM itineraries
        WHERE trip_id = $trip_id
        LIMIT 1
    ");

    return $result[0]['itinerary_id'] ?? null;
}

    public function countUpcoming($user_id)
    {
        $user_id = intval($user_id);

        $res = $this->db->select("
            SELECT COUNT(*) as cnt
            FROM trips
            WHERE created_by = $user_id
            AND start_date > CURDATE()
        ");

        return $res ? (int)$res[0]['cnt'] : 0;
    }

    public function countAll($user_id)
    {
        $user_id = intval($user_id);

        $res = $this->db->select("
            SELECT COUNT(*) as cnt
            FROM trips
            WHERE created_by = $user_id
        ");

        return $res ? (int)$res[0]['cnt'] : 0;
    }
    public function getById($trip_id)
{
    $trip_id = intval($trip_id);

    $result = $this->db->select("
        SELECT *
        FROM trips
        WHERE id = $trip_id
        LIMIT 1
    ");

    return $result ? $result[0] : null;
}

public function getItinerary($itinerary_id)
{
    $itinerary_id = intval($itinerary_id);

    $result = $this->db->select("
        SELECT *
        FROM itineraries
        WHERE itinerary_id = $itinerary_id
        LIMIT 1
    ");

    return $result[0] ?? null;
}

public function getItineraryByTrip($trip_id)
    {
        $trip_id =
            intval($trip_id);

        $result = $this->db->select("

            SELECT *

            FROM itineraries

            WHERE trip_id =
            $trip_id

            LIMIT 1

        ");

        return $result[0] ?? null;
    }
}