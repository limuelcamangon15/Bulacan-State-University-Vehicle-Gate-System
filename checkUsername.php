 <?php
    include('connection.php');

    if (isset($_POST['username'])) {
        $username = $_POST['username'];

        $query = "SELECT COUNT(*) FROM accounts WHERE Username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
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
<?php
    include('connection.php');

    if (isset($_POST['username'])) {
        $username = $_POST['username'];

        $query = "SELECT COUNT(*) FROM accounts WHERE Username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
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