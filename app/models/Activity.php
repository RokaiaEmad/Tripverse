<?php

require_once __DIR__ . '/../../core/Database.php';
require_once __DIR__ . '/ItineraryVersion.php';
require_once __DIR__ . '/TripMember.php';

class Activity
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function create($data)
    {
        $version_id = intval($data['version_id']);

        $title = trim($data['title']);
        $location = trim($data['location']);

        $start_time = $data['start_time'];
        $end_time = $data['end_time'];

        $transport_mode =
            $data['transport_mode'];

        $created_by = intval($data['created_by']);

        // invalid time
        if(strtotime($start_time) >= strtotime($end_time)){
            return 'invalid_time';
        }

        // conflict detection
        $conflict = $this->db->select("
            SELECT *

            FROM activities

            WHERE version_id = $version_id

            AND (
                ('$start_time' BETWEEN start_time AND end_time)

                OR

                ('$end_time' BETWEEN start_time AND end_time)

                OR

                (
                    start_time BETWEEN '$start_time' AND '$end_time'
                )
            )

            LIMIT 1
        ");

        if($conflict){
            return 'time_conflict';
        }

        // default status
        $status = 'draft';

        // leader check
        $versionModel = new ItineraryVersion();

        $trip_id =
            $versionModel->getTripIdByVersion($version_id);

        $tripMember = new TripMember();

        $isLeader = $tripMember->isLeader(
            $trip_id,
            $created_by
        );

        if($isLeader){
            $status = 'confirmed';
        }

        // create main activity
        $activity_id = $this->db->insert("
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
                $version_id,
                '$title',
                '$location',
                '$start_time',
                '$end_time',
                '$status',
                $created_by,
                '$transport_mode'
            )
        ");

        // previous activity same day
        $previous = $this->db->select("
            SELECT *

            FROM activities

            WHERE version_id = $version_id

            AND DATE(start_time) =
            DATE('$start_time')

            AND end_time <= '$start_time'

            AND activity_id != $activity_id

            ORDER BY end_time DESC

            LIMIT 1
        ");

        // transportation buffer logic
        if($previous){

            $previous = $previous[0];

            // different locations only
            if(
                strtolower($previous['location']) !=
                strtolower($location)
            ){

                // estimated travel time
                $minutes = 30;

                if($transport_mode == 'walking'){
                    $minutes = 20;
                }

                if($transport_mode == 'train'){
                    $minutes = 45;
                }

                if($transport_mode == 'car'){
                    $minutes = 30;
                }

                $travel_start =
                    $previous['end_time'];

                $travel_end =
                    date(
                        'Y-m-d H:i:s',
                        strtotime(
                            "+$minutes minutes",
                            strtotime($travel_start)
                        )
                    );

                // add travel block
                $this->db->insert("
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
                        $version_id,
                        'Travel Time',
                        'From {$previous['location']} to $location',
                        '$travel_start',
                        '$travel_end',
                        'confirmed',
                        $created_by,
                        '$transport_mode'
                    )
                ");
            }
        }

        return $activity_id;
    }

    public function confirm($activity_id)
    {
        $activity_id = intval($activity_id);

        return $this->db->update("
            UPDATE activities
            SET status = 'confirmed'
            WHERE activity_id = $activity_id
        ");
    }

    public function getGroupedByDay($version_id)
    {
        $version_id = intval($version_id);

        $activities = $this->db->select("
            SELECT
                activities.*,
                users.name AS creator_name

            FROM activities

            JOIN users
            ON users.id = activities.created_by

            WHERE activities.version_id = $version_id

            ORDER BY activities.start_time ASC
        ");

        $grouped = [];

        foreach ($activities as $activity) {

            $day =
                date(
                    'Y-m-d',
                    strtotime($activity['start_time'])
                );

            $grouped[$day][] = $activity;
        }

        return $grouped;
    }

    public function getActivityWithTrip($activity_id)
    {
        $activity_id = intval($activity_id);

        $result = $this->db->select("
            SELECT
                activities.*,
                itineraries.trip_id

            FROM activities

            JOIN itinerary_versions
            ON itinerary_versions.version_id =
            activities.version_id

            JOIN itineraries
            ON itineraries.itinerary_id =
            itinerary_versions.itinerary_id

            WHERE activities.activity_id = $activity_id

            LIMIT 1
        ");

        return $result[0] ?? null;
    }
}