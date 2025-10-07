 <?php
    include 'connection.php';

    $selectedRole = isset($_GET['roleFilter']) ? $_GET['roleFilter'] : 'All';

    if ($selectedRole === 'All') {
        $sql = "SELECT * FROM accounts";
    } 
    elseif ($selectedRole === 'Not Assigned') {
        $sql = "SELECT * FROM accounts WHERE Role IS NULL";
    } 
    else {
        $stmt = $conn->prepare("SELECT * FROM accounts WHERE Role = ?");
        $stmt->bind_param("s", $selectedRole);
        $stmt->execute();
        $result = $stmt->get_result();
    }

    if (!isset($result)) {
        $result = $conn->query($sql);
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Manage Accounts</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>
    body, html{
        font-family: Arial, sans-serif;
        margin: 0;
        background: linear-gradient(135deg,rgb(10, 14, 23),rgb(66, 90, 131));
        background-attachment: fixed;
        background-repeat: no-repeat;
        background-size: cover; 
        color: #ffffff;
    }


    .navbar {
        display: flex;
        align-items: center;
        background: linear-gradient(135deg,rgb(10, 15, 28),rgb(34, 46, 66));
        padding: 12px 25px 12px 12px;
        transition: margin-left 1s ease-in-out;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 999;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .navbar .logo {
        color: #facc15;
        font-size: 1.8rem;
        font-weight: 700;
        margin-right: auto;
    }

    .navbar ul {
        list-style: none;
        display: flex;
        margin: 0;
        padding: 0;
        font-weight: 600;
    }

    .navbar ul li {
        position: relative;
        margin: 0 12px;
        transition: all 0.3s ease;
    }

    .navbar ul li a {
        color: #f1f5f9;
        text-decoration: none;
        padding: 10px 16px;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .navbar ul li a:hover {
        background-color: rgba(250, 204, 21, 0.15);
        color: #facc15;
        transform: scale(1.05);
    }

    .navbar ul li:hover {
        transform: translateY(5px) scale(1.05);
    }

    .dropdown {
        display: none;
        position: absolute;
        background: #1e293b;
        min-width: 140px;
        z-index: 1;
        border-radius: 6px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
        margin-top: 8px;
    }

    .dropdown a {
        display: block;
        color: #f1f5f9;
        text-decoration: none;
        padding: 10px 16px;
        transition: background-color 0.3s ease;
    }

    .dropdown a:hover {
        background-color: #475569;
        color: #facc15;
    }

    .navbar ul li:hover .dropdown {
        display: block;
    }

    .main-container {
        margin-top: 50px;
        padding: 20px;
    }

    h1 {
        margin-left: 20px;
        margin-bottom: 20px;
        color: rgb(255, 255, 255);
        font-size: 3rem;
        font-weight: bold;
        transform: scaleX(1.150);
    }

    .filter-section {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        margin-bottom: 20px;
    }

    .filter-section label{
        margin-right: 10px;
        margin-left: 10px;
    }

    select {
        padding: 10px 14px;
        font-size: 16px;
        border-radius: 6px;
        border: none;
        background-color: #1c1c1c;
        color: #f0f0f0;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        outline: none;
        appearance: none;
        background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="white"><path d="M0 3l6 6 6-6H0z"/></svg>');
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 12px;
        padding-right: 40px;
    }

    select:hover {
        background-color: #292929;
    }

    select:focus {
        border: 1px solid #f8ac2c;
        box-shadow: 0 0 5px #f8ac2c;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #121212;
        color: #f5f5f5;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        border-radius: 10px;
        overflow: hidden;
    }

    tbody tr {
        border-bottom: 1px solid #333;
        transition: background-color 0.3s ease;
    }

    Tbody td {
        padding: 12px;
        text-align: center;
        font-size: 0.95rem;
    }

    thead th {
        background-color: #1f1f1f;
        color: #f5f5f5;
        text-transform: uppercase;
        font-size: 0.9rem;
        letter-spacing: 0.05rem;
        padding: 12px;
        border-bottom: 2px solid #333;
    }

    table tbody tr:hover {
        background: linear-gradient(90deg,rgb(11, 20, 44),rgb(32, 55, 130));
        color: #f8fafc;
        box-shadow: 0 6px 15px rgba(30, 58, 138, 0.4);
        transition: background 0.3s ease, color 0.3s ease, box-shadow 0.4s ease-in-out;
    }

    .action-btns button {
        margin: 0 4px;
        padding: 8px 14px;
        border: none;
        border-radius: 8px;
        font-weight: bold;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
    }

    .action-btns .view-btn {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: #fff;
    }

    .action-btns .update-btn {
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
    }

    .action-btns .delete-btn {
        background: linear-gradient(135deg, #ef4444, #b91c1c);
        color: #fff;
    }

    .action-btns button:hover {
        transform: scale(1.08);
        opacity: 0.95;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.4);
    }

    .add-btn {
        background: linear-gradient(135deg, #00c67c, #00734f);
        color: #ffffff;
        font-weight: bold;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .add-btn:hover {
        background: linear-gradient(135deg, #00e68a, #009f6b);
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.4);
    }

    .active-nav {
        background-color: #facc15;
        color: #0f172a !important;
        font-weight: bold;
        box-shadow: 0 0 0 3px rgba(250, 204, 21, 0.4);
    }

    .heading-div{
        display: flex;
    }

    #navBtn {
        display: none;
        position: fixed;
        top: -6rem;
        width: 2.5rem;
        height: 3rem;
        background-color: transparent;
        border: none;
        color: #93c5fd;
        font-size: 2rem;
        cursor: pointer;
        transition: color 0.3s ease, transform 0.2s ease;
        z-index: 1001;
    }

    #navBtn:hover {
        color: #60a5fa;
        transform: scale(1.1);
    }

    .table-wrapper {
        max-height: 480px;
        overflow-y: auto;
        border-radius: 10px;
    }

    .table-wrapper table thead th {
        position: sticky;
        top: 0;
        z-index: 999;
    }

    .table-wrapper::-webkit-scrollbar {
        width: 10px;
        height: 10px;
    }

    .table-wrapper::-webkit-scrollbar-track {
        background: #0a0e17;
        border-radius: 10px;
    }

    .table-wrapper::-webkit-scrollbar-thumb {
        background-color: #facc15;
        border-radius: 10px;
        border: 2px solid #0a0e17;
    }

    .table-wrapper::-webkit-scrollbar-thumb:hover {
        background-color:rgb(40, 59, 98);
    }

    .summary-card {
        min-width: 200px;
        max-width: 300px;
        background-color: #1f2937;
        border-radius: 12px;
        padding: 20px;
        color: #f8fafc;
        box-shadow: inset 0 0 10px rgba(0,0,0,0.3);
        height: fit-content;
        font-family: 'Segoe UI', sans-serif;
        transition: transform 0.3s ease, box-shadow 0.3s ease, color 0.3s ease;
    }

    .summary-card:hover, .summary-card:hover p {
        transform: translateY(-5px);
        box-shadow: -3px 0px 1px rgb(200, 164, 20);
        color: #c8a414;
    }

    .summary-card:hover h3{
        color: #f8fafc;
    }

    .summary-card h3 {
        margin-top: 0;
        font-size: 1.2rem;
        color: #facc15;
        margin-bottom: 10px;
    }

    .summary-card p {
        font-size: 1.5rem;
        margin: 8px 0;
        text-align: right;
        color: #e2e8f0;
    }

    .summary-card hr {
        border: none;
        border-top: 1px solid #374151;
        margin: 12px 0;
    }

    .summary-container{
        display: flex;
        flex-direction: row-reverse;
        gap: 20px;
        padding: 0px 0px 10px 0px;
    }


    .role-badge {
        padding: 6px 18px;
        border-radius: 9999px;
        font-size: 0.95rem;
        font-weight: 600;
        display: inline-block;
        border: 1.8px solid transparent;
        background: rgba(255 255 255 / 0.15);
        backdrop-filter: blur(8px);
        box-shadow: inset 0 1px 3px rgba(255 255 255 / 0.3), 0 6px 12px rgba(0 0 0 / 0.12);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        user-select: none;
        line-height: 1.3;
        cursor: default;
        color-scheme: light dark;
    }

    .role-badge:hover {
        transform: scale(1.08);
        box-shadow: 0 8px 20px rgba(0 0 0 / 0.15), 0 0 0 3px currentColor;
        border-color: currentColor;
        color: white !important;
    }

    .admin {
        background: linear-gradient(145deg, rgba(13, 110, 253, 0.12), rgba(13, 110, 253, 0.22));
        color: #0d6efd;
        border-color: #0d6efd;
        text-shadow: 0 1px 2px rgba(13, 110, 253, 0.5);
    }
    .admin:hover {
        background: #0d6efd;
    }

    .guard {
        background: linear-gradient(145deg, rgba(25, 135, 84, 0.12), rgba(25, 135, 84, 0.22));
        color: #198754;
        border-color: #198754;
        text-shadow: 0 1px 2px rgba(25, 135, 84, 0.5);
    }
    .guard:hover {
        background: #198754;
    }

    .vehicle-owner {
        background: linear-gradient(145deg, rgba(255, 193, 7, 0.18), rgba(255, 193, 7, 0.28));
        color: #ffc107;
        border-color: #ffc107;
        text-shadow: 0 1px 2px rgba(255, 193, 7, 0.6);
    }
    .vehicle-owner:hover {
        background: #ffc107;
    }

    .not-assigned {
        background: linear-gradient(145deg, rgba(108, 117, 125, 0.12), rgba(108, 117, 125, 0.22));
        color: #6c757d;
        border-color: #6c757d;
        text-shadow: 0 1px 2px rgba(108, 117, 125, 0.5);
    }
    .not-assigned:hover {
        background: #6c757d;
    }


    @media (max-width: 1380px){
            #navBtn{
                margin-top: 6rem;
            } 

            .navbar{
                margin-left: 0;
            }
        }

        @media (max-width: 780px) {
            .summary-container{
                flex-direction: column-reverse;
            }

            .main-container{
                margin-top: 0;
            }

            #navBtn{
                display: block;
            }

            .navbar{
                margin-left: -300px;
                height: 100%;
                width: 35%;
                position: absolute;
                flex-wrap: wrap;
                flex-direction: column;
                z-index: 999;
            }           
            
            .navbar .logo {
                color: white;
                font-family: impact;
                margin-bottom: 2rem;
            }
            .navbar ul {
                display: flex;
                flex-direction: column;
                gap: 2em;
                justify-content: left;
                list-style: none;
                font-weight: bold;
            }
            .navbar ul li a {
                color: white;
                text-decoration: none;
                border-radius: 5px;
            }
            .navbar ul li a:hover {
                background-color: #444; 
            }
            .dropdown {
                margin-top: 0.5em;       
            }
            .dropdown a {
                display: block;
                margin-top: 0.5rem;
                color: white;
                text-decoration: none;
            }
            .dropdown a:hover {
                background-color: #444; 
            }
            .navbar ul li:hover .dropdown {
                display: block;
            }



            table, thead, tbody, th, td, tr {
                display: block;
            }

            thead tr {
                display: none;
            }

            td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }

            td::before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                font-weight: bold;
                text-align: left;
            }

            .action-btns {
                display: flex;
                justify-content: center;
                gap: 5px;
            }

            .filter-section {
              align-items: center;
            }

            select {
                width: 80%;
            }
        }



        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            backdrop-filter: blur(10px);
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 999;
        }

        .modal-overlay.show {
            display: flex;
        }

        .modal-dialog {
            background: linear-gradient(135deg,rgb(10, 14, 23),rgb(17, 32, 58));
            padding: 20px;
            border-radius: 8px;
            min-width: 600px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            position: relative;

            animation: fadeSlideIn 0.35s ease-out;
        }

        .modal-header {
            font-weight: bold;
            margin-bottom: 10px;
            color: white;
        }

        .modal-footer {
            margin-top: 20px;
            text-align: right;
        }

        .close-btn {
            position: absolute;
            right: 10px;
            top: 10px;
            background: none;
            border: none;
            font-size: 25px;
            color: #facc15;
            cursor: pointer;
       
        }

        #okBtn{
            background-color: #facc15;
            color: #0f172a !important;
            font-weight: bold;
            border-radius: 10px;
            border: none;
            width: 100px; 
            height: 30px;
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        #okBtn:hover{
            transform: scale(1.1,1.1);
        }

        @keyframes fadeSlideIn {
            0% {
                opacity: 0;
                transform: translateY(-20px) scale(0.80);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .modal-table, .modal-table tr td{
            background-color: transparent;
            text-align: left;    
            width: 500px;    
            justify-self: center;
            align-self: center;
        }

        .modal-table tr td:nth-child(1){
            width: 300px;
            font-weight: bold;
        }

        .modal-table tr td:nth-child(2){
            font-style: italic;
        }



  </style>
</head>
<body>

  <button onclick="showNav()" id="navBtn">☰</button>

  <div class="navbar" id="navbar">
    <div class="logo">
      <img>
    </div>
    <ul>
        <li><a href="dashboardGuard.php"><i class="fas fa-chart-line"></i> Dashboard</a></li>
        <li><a href="accountsGuardAccess.php" class="active-nav"><i class="fas fa-users-cog"></i> Accounts</a></li>
        <li><a href="profilesGuardAccess.php"><i class="fas fa-id-card"></i> Profiles</a></li>
        <li><a href="campusesGuardAccess.php"><i class="fas fa-university"></i> Campuses</a></li>
        <li><a href="gatesGuardAccess.php"><i class="fas fa-door-open"></i> Gates</a></li>
        <li><a href="vehicleGuardAccess.php"><i class="fas fa-car"></i> Vehicles</a></li>
        <li><a href="gatesGuardAccessLogs.php"><i class="fas fa-shield-alt"></i> Logs/Access</a></li>
        <li>
        <a><i class="fas fa-cog"></i> Settings</a>
        <div class="dropdown">
        <a href="accountGuard.php"><i class="fas fa-user"></i> Account</a>
            <a href="profileGuard.php" ><i class="fas fa-user-circle"></i> Profile</a>
            <a href="aboutUsGuard.html"><i class="fas fa-info-circle"></i> About</a>
            <a href="contactUsGuard.html"><i class="fas fa-envelope"></i> Contact</a>
            <a href="index.php"><i class="fas fa-sign-out-alt"></i> Log Out</a>
        </div>
        </li>
    </ul>
  </div>

  <div class="main-container">
    <div class="heading-div">
        <h1><i class="fas fa-users-cog"></i> Manage Accounts</h1>
    </div>

    <div class="summary-container">
        <div class="summary-card">
            <h3><i class="fa-solid fa-user-plus" style="color:rgb(32, 144, 4);"></i> Recently Registered Account</h3>
            <?php 
                $resultRecentlyAdded = $conn->query("
                    SELECT CONCAT(accounts.FirstName, ' ', accounts.LastName) AS RecentlyAdded 
                    FROM accounts 
                    ORDER BY AccountID DESC 
                    LIMIT 1
                ");
                $row = $resultRecentlyAdded->fetch_assoc();
                $recentlyAddedAccount = $row['RecentlyAdded'];
                echo "<p><strong><span id='newlyAdded'>$recentlyAddedAccount</span></strong></p>";
            ?>
        </div>

        <div class="summary-card">
            <h3><i class="fa-solid fa-user-tie" style="color: #0d6efd;"></i> Admins</h3>
            <?php 
                $resultTotalAdmin = $conn->query("
                    SELECT COUNT(*) AS TotalAdmin
                    FROM accounts
                    WHERE Role = 'Admin'");
                $row = $resultTotalAdmin->fetch_assoc();
                $totalAdmin = $row['TotalAdmin'];
                echo "<p><strong><span id='newlyAdded'>$totalAdmin</span></strong></p>";
            ?>
        </div>

        <div class="summary-card">
            <h3><i class="fa-solid fa-user-shield" style="color: #198754;"></i> Guards</h3>
            <?php 
                $resultTotalGuard = $conn->query("
                    SELECT COUNT(*) AS TotalGuard
                    FROM accounts
                    WHERE Role = 'Guard'");
                $row = $resultTotalGuard->fetch_assoc();
                $totalGuard = $row['TotalGuard'];
                echo "<p><strong><span id='newlyAdded'>$totalGuard</span></strong></p>";
            ?>
        </div>

        <div class="summary-card">
            <h3><i class="fa-solid fa-car" style="color:rgb(182, 179, 13);"></i> Vehicle Owners</h3>
            <?php 
                $resultTotalVehicleOwner = $conn->query("
                    SELECT COUNT(*) AS TotalVehicleOwner
                    FROM accounts
                    WHERE Role = 'Vehicle Owner'");
                $row = $resultTotalVehicleOwner->fetch_assoc();
                $totalVehicleOwner = $row['TotalVehicleOwner'];
                echo "<p><strong><span id='newlyAdded'>$totalVehicleOwner</span></strong></p>";
            ?>
        </div>

        <div class="summary-card">
            <h3><i class="fa-solid fa-question-circle" style="color: #6c757d;"></i> Not Assigned</h3>
            <?php 
                $resultTotalNotAssigned = $conn->query("
                    SELECT COUNT(*) AS TotalNotAssigned
                    FROM accounts
                    WHERE Role IS NULL");
                $row = $resultTotalNotAssigned->fetch_assoc();
                $totalNotAssigned = $row['TotalNotAssigned'];
                echo "<p><strong><span id='newlyAdded'>$totalNotAssigned</span></strong></p>";
            ?>
        </div>

        <div class="summary-card">
            <h3><i class="fa-solid fa-user-check" style="color:rgb(13, 253, 69);"></i> Registered Accounts</h3>
            <?php 
                $resultTotalRegistered = $conn->query("SELECT COUNT(*) AS TotalRegistered FROM accounts");
                $row = $resultTotalRegistered->fetch_assoc();
                $totalRegistered = $row['TotalRegistered'];
                echo "<p><strong><span id='newlyAdded'>$totalRegistered</span></strong></p>";
            ?>
        </div>
    </div>

    <div class="filter-section">

        <form method="GET" action="accountsGuardAccess.php" style="display:inline;">
            <label for="roleFilter">Filter by Role:</label>
            <select name="roleFilter" onchange="this.form.submit()">
                <option value="All" <?= $selectedRole === 'All' ? 'selected' : '' ?>>All</option>
                <option value="Admin" <?= $selectedRole === 'Admin' ? 'selected' : '' ?>>Admin</option>
                <option value="Guard" <?= $selectedRole === 'Guard' ? 'selected' : '' ?>>Guard</option>
                <option value="Vehicle Owner" <?= $selectedRole === 'Vehicle Owner' ? 'selected' : '' ?>>Vehicle Owner</option>
                <option value="Not Assigned" <?= $selectedRole === 'Not Assigned' ? 'selected' : '' ?>>Not Assigned</option>
            </select>
        </form>
    </div>

        <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Account ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email Address</th>
                    <th>Contact Number</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Role</th>
                    <th style="width: 180px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['AccountID'] ?></td>
                            <td><?php echo $row['FirstName']?></td>
                            <td><?php echo $row['LastName'] ?></td>
                            <td><?php echo $row['EmailAddress'] ?></td>
                            <td><?php echo $row['ContactNumber'] ?></td>
                            <td><?php echo $row['Username'] ?></td>
                            <td>••••••</td>
                            <td>
                            <?php 
                                $role = $row['Role'] ?? 'Not Assigned';
                                $cssClass = match ($role) {
                                    'Admin' => 'role-badge admin',
                                    'Guard' => 'role-badge guard',
                                    'Vehicle Owner' => 'role-badge vehicle-owner',
                                    default => 'role-badge not-assigned'
                                };
                            ?>
                            <span class="<?= $cssClass ?>"><?= htmlspecialchars($role) ?></span>
                            </td>

                            <td class="action-btns">
                                <button 
                                    class="view-btn" 
                                    data-accountid = "<?= $row['AccountID'] ?>"
                                    data-firstname = "<?= $row['FirstName'] ?>"
                                    data-lastname = "<?= $row['LastName'] ?>"
                                    data-email = "<?= $row['EmailAddress'] ?>"
                                    data-contact = "<?= $row['ContactNumber'] ?>"
                                    data-username = "<?= $row['Username'] ?>"
                                    data-password = "<?= "••••••" ?>"
                                    data-role = "<?= $row['Role'] ?? 'Not Assigned' ?>"
                                >
                                    <i class="fas fa-eye"></i> View
                                </button>               
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="9">No accounts found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        </div>
</div>


    <div id="modal" class="modal-overlay">
        <div class="modal-dialog">
            <button class="close-btn" id="closeModalBtn">&times;</button>
            <div class="modal-header">
                    <i class="fas fa-circle-info" style="margin-right: 8px;"></i> Account Information
                    </div>
                    <div class="modal-body">
                    <table class="modal-table">
                        <tbody>
                        <tr>
                            <td><i class="fas fa-id-card" style="margin-right: 5px;"></i> Account ID:</td>
                            <td><span id="modalAccountID"></span></td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-user" style="margin-right: 5px;"></i> Name:</td>
                            <td><span id="modalName"></span></td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-envelope" style="margin-right: 5px;"></i> Email:</td>
                            <td><span id="modalEmail"></span></td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-phone" style="margin-right: 5px;"></i> Contact:</td>
                            <td><span id="modalContact"></span></td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-user-circle" style="margin-right: 5px;"></i> Username:</td>
                            <td><span id="modalUsername"></span></td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-key" style="margin-right: 5px;"></i> Password:</td>
                            <td><span id="modalPassword"></span></td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-user-tag" style="margin-right: 5px;"></i> Role:</td>
                            <td><span id="modalRole"></span></td>
                        </tr>
                        </tbody>
                    </table>
            </div>
            <div class="modal-footer">
            <button id="okBtn">Close</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('modal');
            const closeBtn = document.getElementById('closeModalBtn');
            const okBtn = document.getElementById('okBtn');

            document.querySelectorAll('.view-btn').forEach(btn => {
                btn.addEventListener('click', () => {

                document.getElementById('modalAccountID').textContent = btn.dataset.accountid;
                document.getElementById('modalName').textContent = `${btn.dataset.firstname} ${btn.dataset.lastname}`;
                document.getElementById('modalEmail').textContent = btn.dataset.email;
                document.getElementById('modalContact').textContent = btn.dataset.contact;
                document.getElementById('modalUsername').textContent = btn.dataset.username;
                document.getElementById('modalPassword').textContent = btn.dataset.password;
                document.getElementById('modalRole').textContent  = btn.dataset.role;

                modal.classList.add('show');
                });
            });

            closeBtn.addEventListener('click', () => modal.classList.remove('show'));
            okBtn.addEventListener('click', () => modal.classList.remove('show'));

            modal.addEventListener('click', e => {
                if (e.target === modal) modal.classList.remove('show');
            });
        });
    </script>


    <script>
        listenToScreenSize();
        
        function showNav(){
            const navBar = document.getElementById('navbar');

            navBar.style.marginLeft = '0px';
            document.body.addEventListener('click', closeNavOnClickOutside);
        }

        function closeNavOnClickOutside(e) {
            const navBar = document.getElementById('navbar');
            const navBtn = document.getElementById('navBtn');

            if (!navBar.contains(e.target) && e.target !== navBtn) {
                navBar.style.marginLeft = '-300px';

                
                document.body.removeEventListener('click', closeNavOnClickOutside);
            }
        }

        function listenToScreenSize() {
            const navBar = document.getElementById('navbar');

            function handleResize() {
                if (window.innerWidth >= 781) {
                    navBar.style.marginLeft = '0px';
                }
                else{
                    navBar.style.marginLeft = '-300px';
                }
            }

            handleResize();

            window.addEventListener('resize', handleResize);
        }
    </script>

</body>
</html>