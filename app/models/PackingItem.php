<?php

require_once __DIR__ . '/../../core/Database.php';

class PackingItem
{
    private $db;

    public function __construct()
    {
        $this->db =
            Database::getInstance();
    }

    /*
    |------------------------------------------------------------------
    | CREATE ITEM
    |------------------------------------------------------------------
    */

    public function create($data)
    {
        $trip_id =
            intval($data['trip_id']);

        $name =
            trim($data['name']);

        $suggested =
            intval($data['suggested'] ?? 0);

        /*
        |--------------------------------------------------------------
        | PREVENT DUPLICATES
        |--------------------------------------------------------------
        */

        $exists = $this->db->select("

            SELECT *

            FROM packing_items

            WHERE trip_id =
            $trip_id

            AND name =
            '$name'

            LIMIT 1

        ");

        if ($exists) {

            return;
        }

        return $this->db->insert("

            INSERT INTO packing_items
            (
                trip_id,
                name,
                suggested
            )

            VALUES
            (
                $trip_id,
                '$name',
                $suggested
            )

        ");
    }

    /*
    |------------------------------------------------------------------
    | GET ITEMS
    |------------------------------------------------------------------
    */

    public function getTripItems($trip_id)
    {
        $trip_id =
            intval($trip_id);

        return $this->db->select("

            SELECT
                packing_items.*,
                users.name AS assigned_name

            FROM packing_items

            LEFT JOIN users
            ON users.id =
            packing_items.assigned_to

            WHERE packing_items.trip_id =
            $trip_id

            ORDER BY
            packing_items.item_id DESC

        ");
    }

    /*
    |------------------------------------------------------------------
    | ASSIGN ITEM
    |------------------------------------------------------------------
    */

    public function assignItem(
        $item_id,
        $user_id
    ) {

        $item_id =
            intval($item_id);

        $user_id =
            intval($user_id);

        /*
        |--------------------------------------------------------------
        | CHECK IF ALREADY ASSIGNED
        |--------------------------------------------------------------
        */

        $exists = $this->db->select("

            SELECT *

            FROM packing_items

            WHERE item_id =
            $item_id

            AND assigned_to
            IS NOT NULL

            LIMIT 1

        ");

        if ($exists) {

            return false;
        }

        return $this->db->update("

            UPDATE packing_items

            SET assigned_to =
            $user_id

            WHERE item_id =
            $item_id

        ");
    }

    /*
    |------------------------------------------------------------------
    | TOGGLE CHECK
    |------------------------------------------------------------------
    */

    public function toggleCheck($item_id)
    {
        $item_id =
            intval($item_id);

        return $this->db->update("

            UPDATE packing_items

            SET is_checked =
            NOT is_checked

            WHERE item_id =
            $item_id

        ");
    }

    /*
    |------------------------------------------------------------------
    | GET SINGLE ITEM
    |------------------------------------------------------------------
    */

    public function getById($item_id)
    {
        $item_id =
            intval($item_id);

        $result = $this->db->select("

            SELECT *

            FROM packing_items

            WHERE item_id =
            $item_id

            LIMIT 1

        ");

        return $result[0] ?? null;
    }

    public function exists(
    $trip_id,
    $name
) {

    $trip_id =
        intval($trip_id);

    $name =
        trim($name);

    $result =
        $this->db->select("

            SELECT *

            FROM packing_items

            WHERE trip_id =
            $trip_id

            AND name =
            '$name'

            LIMIT 1

        ");

    return !empty($result);
}
}