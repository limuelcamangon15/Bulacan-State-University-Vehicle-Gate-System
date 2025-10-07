 <?php
    include 'connection.php';

    if (isset($_POST['owner_id'])) {
        $owner_id = $_POST['owner_id'];

        $query = "SELECT Role FROM accounts WHERE AccountID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $owner_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($role);
            $stmt->fetch();

            if ($role === 'Vehicle Owner') {
                echo "exists_owner";
            }
            else {
                echo "exists_not_owner";
            }
        }
        else {
            echo "not_exists";
        }

        $stmt->close();
    }
?>