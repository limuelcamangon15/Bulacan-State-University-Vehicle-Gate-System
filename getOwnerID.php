 <?php
    include 'connection.php';

    if (isset($_POST['plate'])) {
        $plate = $_POST['plate'];

        $query = "SELECT OwnerID FROM vehicles WHERE PlateNumber = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $plate);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            echo json_encode([
                'success' => true,
                'owner_id' => $row['OwnerID']
            ]);
        }
        else {
            echo json_encode([
                'success' => false,
                'message' => 'Plate number not found'
            ]);
        }

        $stmt->close();
    }
?>