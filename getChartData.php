 <?php
    include 'connection.php';

    $sql = "
    SELECT 
        DATE_FORMAT(Timestamp, '%Y-%m') AS month,
        SUM(CASE WHEN AccessType = 'entry' THEN 1 ELSE 0 END) AS entries,
        SUM(CASE WHEN AccessType = 'exit' THEN 1 ELSE 0 END) AS exits
    FROM gatelogs
    GROUP BY month
    ORDER BY month
    ";

    $result = $conn->query($sql);

    $data = [];

    while ($row = $result->fetch_assoc()) {
    $data[] = $row;
    }

    echo json_encode($data);

    $conn->close();
?>