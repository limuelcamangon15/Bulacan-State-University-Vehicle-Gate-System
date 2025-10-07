 <?php
    include 'connection.php';

    if (isset($_POST['plate'])) {
        $plate = $_POST['plate'];

        $query = "
            SELECT gatelogs.AccessType, gatelogs.CampusID, campuses.CampusName 
            FROM gatelogs 
            INNER JOIN campuses ON gatelogs.CampusID = campuses.CampusID 
            WHERE gatelogs.PlateNumber = ? 
            ORDER BY gatelogs.Timestamp DESC 
            LIMIT 1
        ";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $plate);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            echo json_encode([
                'success' => true,
                'last_log' => $row['AccessType'],
                'last_campus_id' => $row['CampusID'],
                'last_campus_name' => $row['CampusName']
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'last_log' => null,
                'last_campus_id' => null,
                'last_campus_name' => null
            ]);
        }

        $stmt->close();
    }
?>