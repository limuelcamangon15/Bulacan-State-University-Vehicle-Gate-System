 <?php
include 'connection.php';

if (isset($_POST['account_id'])) {
    $account_id = (int)$_POST['account_id'];

    $accountCheck = $conn->prepare("SELECT COUNT(*) FROM accounts WHERE AccountID = ?");
    $accountCheck->bind_param("i", $account_id);
    $accountCheck->execute();
    $accountCheck->bind_result($accountExists);
    $accountCheck->fetch();
    $accountCheck->close();

    if ($accountExists == 0) {
        echo "invalid";
        exit;
    }

    $profileCheck = $conn->prepare("SELECT COUNT(*) FROM profiles WHERE AccountID = ?");
    $profileCheck->bind_param("i", $account_id);
    $profileCheck->execute();
    $profileCheck->bind_result($profileExists);
    $profileCheck->fetch();
    $profileCheck->close();

    if ($profileExists > 0) {
        echo "exists";
    } 
    else {
        echo "valid";
    }
}
?>