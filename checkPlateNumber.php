 <?php
    include 'connection.php';

    if (isset($_POST['plate'])) {
        $plate = $_POST['plate'];

        $query = "SELECT COUNT(*) FROM vehicles WHERE PlateNumber = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $plate);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();

        if ($count > 0) {
            echo "exists";
        } 
        else {
            echo "available";
        }

        $stmt->close();
    }
?>