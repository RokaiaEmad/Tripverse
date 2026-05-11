<?php

require_once __DIR__ . '/../../core/Database.php';


class Expense
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function createExpense($data)
    {
        $tripId = $data['trip_id'];
        $paidBy = $data['paid_by_member_id'];
        $amount = $data['amount'];
        $category = $data['category'];

        $query = "
            INSERT INTO expenses
            (trip_id, paid_by_member_id, amount, category)
            VALUES
            ('$tripId', '$paidBy', '$amount', '$category')
        ";

        return $this->db->insert($query);
    }

    public function addSplit($expenseId, $memberId, $shareAmount)
    {
        $query = "
            INSERT INTO expense_splits
            (expense_id, member_id, share_amount)
            VALUES
            ('$expenseId', '$memberId', '$shareAmount')
        ";

        return $this->db->insert($query);
    }
    public function getMembers($trip_id)
    {
        $query = "

    SELECT users.id AS member_id,
           users.username

    FROM trip_members

    JOIN users
    ON trip_members.user_id = users.id

    WHERE trip_members.trip_id = '$trip_id'
    ";

        return $this->db->select($query);
    }
}
