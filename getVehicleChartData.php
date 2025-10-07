 <?php
    session_start();
    include 'connection.php';

    $accountID = $_SESSION['account_id'];
    $sql = "
        SELECT 
            DATE_FORMAT(Timestamp, '%Y-%m') AS month,
            SUM(CASE WHEN AccessType = 'entry' THEN 1 ELSE 0 END) AS entries,
            SUM(CASE WHEN AccessType = 'exit' THEN 1 ELSE 0 END) AS exits
        FROM gatelogs
        WHERE OwnerID = '$accountID'
        GROUP BY month
        ORDER BY month
    ";

    $result = $conn->query($sql);

    $data = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'month'   => $row['month'],
                'entries' => (int)$row['entries'],
                'exits'   => (int)$row['exits']
            ];
        }
    }

    header('Content-Type: application/json');
    echo json_encode($data);

    $conn->close();
?>