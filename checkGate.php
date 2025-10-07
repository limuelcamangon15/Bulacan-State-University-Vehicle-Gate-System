 <?php 
    include 'connection.php';

    if (isset($_POST['gate_number']) && isset($_POST['campus_id'])) {
        $gateNumber = $_POST['gate_number'];
        $campusID = $_POST['campus_id'];

        $query = "SELECT COUNT(*) FROM gates WHERE GateNumber = ? AND CampusID = ?";
        $stmt = $conn->prepare($query);
        
        $stmt->bind_param("ii", $gateNumber, $campusID);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0){
            echo "exists";
        }
        else{
            echo "not found";
        }
    }
?>