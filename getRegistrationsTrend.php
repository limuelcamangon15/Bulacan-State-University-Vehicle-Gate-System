 <?php
    include 'connection.php';

    header('Content-Type: application/json');

    $months = [];
    $current = new DateTime('first day of this month');
    for ($i = 11; $i >= 0; $i--) {
        $month = clone $current;
        $month->modify("-$i months");
        $key = $month->format('Y-m');
        $months[$key] = [
            'year' => (int)$month->format('Y'),
            'month' => $month->format('M'),
            'month_num' => (int)$month->format('n'),
            'registrations' => 0
        ];
    }

    $sql = "SELECT 
                DATE_FORMAT(PostedAt, '%Y-%m') AS month_key,
                YEAR(PostedAt) AS year,
                MONTH(PostedAt) AS month_num,
                COUNT(*) AS registrations
            FROM vehicles
            WHERE PostedAt >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
            GROUP BY month_key
            ORDER BY month_key ASC";

    $result = $conn->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $key = $row['month_key'];
            if (isset($months[$key])) {
                $months[$key]['registrations'] = (int)$row['registrations'];
            }
        }
    }

    $data = array_values($months);

    echo json_encode($data);
?>