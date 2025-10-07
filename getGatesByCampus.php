 <?php
    include 'connection.php';

    if (isset($_POST['campus_id'])) {
        $campusID = intval($_POST['campus_id']);

        $stmt = $conn->prepare("SELECT GateID, GateNumber FROM gates WHERE CampusID = ?");
        $stmt->bind_param("i", $campusID);
        $stmt->execute();
        $result = $stmt->get_result();

        $gates = [];
        while ($row = $result->fetch_assoc()) {
            $gates[] = $row;
        }

        echo json_encode($gates);
        $stmt->close();
    }
    $conn->close();
?>