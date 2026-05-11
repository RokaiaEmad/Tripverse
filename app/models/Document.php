public function create($data)
{
    $db = Database::getInstance();

    $stmt = $db->prepare("
        INSERT INTO documents
        (
            trip_id,
            owner_member_id,
            file_path,
            type,
            visibility
        )
        VALUES (?, ?, ?, ?, ?)
    ");

    return $stmt->execute([

        $data['trip_id'],
        $data['owner_member_id'],
        $data['file_path'],
        $data['type'],
        $data['visibility']
    ]);
}