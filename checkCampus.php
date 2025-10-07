 <?php
    include 'connection.php';

    if (isset($_POST['campus_name'])) {
        $campusName = $_POST['campus_name'];
        $campusID = isset($_POST['campus_id']) ? (int)$_POST['campus_id'] : null;

        if ($campusID) {
            $query = "SELECT COUNT(*) FROM campuses WHERE CampusName = ? AND CampusID != ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $campusName, $campusID);
        }
        else {
            $query = "SELECT COUNT(*) FROM campuses WHERE CampusName = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $campusName);
        }

        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();

        if ($count > 0) {
            echo "exists";
        } 
        else {
            echo "not found";
        }

        $stmt->close();
    }
    else{
        $campusID = $_POST['campus_id'];

        $query = "SELECT COUNT(*) FROM campuses WHERE CampusID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $campusID);

        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();

        if($count > 0){
            echo "exists";
        }
        else{
            echo "not found";
        }
    }
?>
<?php
    include 'connection.php';

    if (isset($_POST['campus_name'])) {
        $campusName = $_POST['campus_name'];
        $campusID = isset($_POST['campus_id']) ? (int)$_POST['campus_id'] : null;

        if ($campusID) {
            $query = "SELECT COUNT(*) FROM campuses WHERE CampusName = ? AND CampusID != ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $campusName, $campusID);
        }
        else {
            $query = "SELECT COUNT(*) FROM campuses WHERE CampusName = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $campusName);
        }

        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();

        if ($count > 0) {
            echo "exists";
        } 
        else {
            echo "not found";
        }

        $stmt->close();
    }
    else{
        $campusID = $_POST['campus_id'];

        $query = "SELECT COUNT(*) FROM campuses WHERE CampusID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $campusID);

        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();

        if($count > 0){
            echo "exists";
        }
        else{
            echo "not found";
        }
    }
?>