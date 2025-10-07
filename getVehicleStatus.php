 <?php
    include 'connection.php';

    if (isset($_POST['plate'])) {
        $plate = $_POST['plate'];

        $query = "SELECT Status FROM vehicles WHERE PlateNumber = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $plate);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            echo json_encode([
                'success' => true,
                'vehicle_status' => $row['Status']
            ]);
        }
        else {
            echo json_encode([
                'success' => false,
                'message' => 'Status is not approved'
            ]);
        }

        $stmt->close();
    }
?>