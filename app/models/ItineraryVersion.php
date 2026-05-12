<?php

require_once __DIR__ . '/../../core/Database.php';

class ItineraryVersion
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getActiveVersion($itinerary_id)
    {
        $itinerary_id = intval($itinerary_id);

        $result = $this->db->select("
            SELECT *
            FROM itinerary_versions
            WHERE itinerary_id = $itinerary_id
            AND is_active = 1
            LIMIT 1
        ");

        return $result[0] ?? null;
    }

    public function getAllVersions($itinerary_id)
    {
        $itinerary_id = intval($itinerary_id);

        return $this->db->select("
            SELECT
                itinerary_versions.*,
                users.name AS creator_name

            FROM itinerary_versions

            JOIN users
            ON users.id = itinerary_versions.created_by

            WHERE itinerary_versions.itinerary_id = $itinerary_id

            ORDER BY version_number DESC
        ");
    }

    public function createNewVersion(
        $itinerary_id,
        $created_by,
        $change_note = null
    ) {

        $active =
            $this->getActiveVersion($itinerary_id);

        if (!$active) {

            return false;
        }

        $nextVersion =
            intval($active['version_number']) + 1;

        // deactivate current version
        $this->db->update("
            UPDATE itinerary_versions
            SET is_active = 0
            WHERE itinerary_id = $itinerary_id
        ");

        // create new version
        $new_version_id = $this->db->insert("
            INSERT INTO itinerary_versions
            (
                itinerary_id,
                version_number,
                created_by,
                change_note,
                is_active
            )
            VALUES
            (
                $itinerary_id,
                $nextVersion,
                $created_by,
                " . ($change_note ? "'$change_note'" : "NULL") . ",
                1
            )
        ");

        // get activities from old active version
        $activities = $this->db->select("
            SELECT *
            FROM activities
            WHERE version_id = {$active['version_id']}
        ");

        // clone activities + clone RSVPs
        if ($activities) {

            foreach ($activities as $activity) {

                $title =
                    $activity['title'];

                $location =
                    $activity['location'];

                $start =
                    $activity['start_time'];

                $end =
                    $activity['end_time'];

                $status =
                    $activity['status'];

                $creator =
                    $activity['created_by'];

                $transport_mode =
                    $activity['transport_mode'];

                // create copied activity
                $new_activity_id = $this->db->insert("
                    INSERT INTO activities
                    (
                        version_id,
                        title,
                        location,
                        start_time,
                        end_time,
                        status,
                        created_by,
                        transport_mode
                    )
                    VALUES
                    (
                        $new_version_id,
                        '$title',
                        '$location',
                        '$start',
                        '$end',
                        '$status',
                        $creator,
                        '$transport_mode'
                    )
                ");

                // get RSVPs for old activity
                $rsvps = $this->db->select("
                    SELECT *
                    FROM activity_rsvps
                    WHERE activity_id = {$activity['activity_id']}
                ");

                // clone RSVPs
                if ($rsvps) {

                    foreach ($rsvps as $rsvp) {

                        $user_id =
                            intval($rsvp['user_id']);

                        $rsvp_status =
                            $rsvp['status'];

                        $this->db->insert("
                            INSERT INTO activity_rsvps
                            (
                                activity_id,
                                user_id,
                                status
                            )
                            VALUES
                            (
                                $new_activity_id,
                                $user_id,
                                '$rsvp_status'
                            )
                        ");
                    }
                }
            }
        }

        // update itinerary current version
        $this->db->update("
            UPDATE itineraries
            SET current_version_id = $new_version_id
            WHERE itinerary_id = $itinerary_id
        ");

        return $new_version_id;
    }

    public function rollback(
        $itinerary_id,
        $version_id
    ) {

        $itinerary_id =
            intval($itinerary_id);

        $version_id =
            intval($version_id);

        // deactivate all versions
        $this->db->update("
            UPDATE itinerary_versions
            SET is_active = 0
            WHERE itinerary_id = $itinerary_id
        ");

        // activate selected version
        $this->db->update("
            UPDATE itinerary_versions
            SET is_active = 1
            WHERE version_id = $version_id
        ");

        // update itinerary current version
        return $this->db->update("
            UPDATE itineraries
            SET current_version_id = $version_id
            WHERE itinerary_id = $itinerary_id
        ");
    }

    public function getTripIdByVersion($version_id)
    {
        $version_id = intval($version_id);

        $result = $this->db->select("
            SELECT itineraries.trip_id

            FROM itinerary_versions

            JOIN itineraries
            ON itineraries.itinerary_id =
            itinerary_versions.itinerary_id

            WHERE itinerary_versions.version_id = $version_id

            LIMIT 1
        ");

        return $result[0]['trip_id'] ?? null;
    }

    
}