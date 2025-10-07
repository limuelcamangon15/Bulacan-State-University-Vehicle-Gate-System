 <?php
    session_start();

    include 'connection.php';

    $accountID = $_SESSION['account_id'];

    $selectedSort = isset($_GET['typeFilter']) ? $_GET['typeFilter'] : 'DESC';
    $selectedCampus = isset($_GET['campusFilter']) ? $_GET['campusFilter'] : 'All';
    $selectedGateNumber = isset($_GET['gateNumberFilter']) ? $_GET['gateNumberFilter'] : 'All';

    $sql = "
        SELECT
            gatelogs.LogID,
            gatelogs.PlateNumber,
            CONCAT(owner.FirstName, ' ', owner.LastName) AS OwnerFullName,
            gatelogs.OwnerID,
            campuses.CampusName,
            gates.GateNumber,
            gatelogs.AccessType,
            gatelogs.Timestamp,
            CONCAT(guard.FirstName, ' ', guard.LastName) AS GuardFullName
        FROM gatelogs
        INNER JOIN accounts AS owner ON gatelogs.OwnerID = owner.AccountID
        INNER JOIN accounts AS guard ON gatelogs.LoggedBy = guard.AccountID
        INNER JOIN campuses ON gatelogs.CampusID = campuses.CampusID
        INNER JOIN gates ON gatelogs.GateID = gates.GateID
    ";

    $whereClauses = [];
    $params = [];
    $types = "";

    $whereClauses[] = "gatelogs.OwnerID = ?";
    $params[] = $accountID;
    $types .= "i";

    if ($selectedCampus !== 'All') {
        $whereClauses[] = "campuses.CampusName = ?";
        $params[] = $selectedCampus;
        $types .= "s";
    }

    if ($selectedGateNumber !== 'All') {
        $whereClauses[] = "gates.GateNumber = ?";
        $params[] = $selectedGateNumber;
        $types .= "s";
    }

    if (!empty($whereClauses)) {
        $sql .= " WHERE " . implode(" AND ", $whereClauses);
    }

    $sortDirection = strtoupper($selectedSort) === 'ASC' ? 'ASC' : 'DESC';
    $sql .= " ORDER BY gatelogs.Timestamp $sortDirection";

    $stmt = $conn->prepare($sql);

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Manage Vehicles</title>
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
        z-index: 1001;
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


    @media (max-width: 1380px){
            #navBtn{
                margin-top: 6rem;
            } 

            .navbar{
                margin-left: 0;
            }
        }

        @media (max-width: 780px) {

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
                background-color: rgb(0, 0, 0);
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
            z-index: 1001;
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
            width: 200px;
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
        <li><a href="dashboardOwner.php" ><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="gatesLogsOwnerAccess.php" class="active-nav"><i class="fas fa-door-open"></i> Gate Logs</a></li>
        <li><a href="vehicleOwnerAccess.php"><i class="fas fa-car"></i> MyVehicles</a></li>
        <li>
        <a><i class="fas fa-cog"></i> Settings</a>
        <div class="dropdown">
        <a href="accountOwner.php"><i class="fas fa-user"></i> Account</a>
            <a href="profileOwner.php"><i class="fas fa-user-circle"></i> Profile</a>
            <a href="aboutUsOwner.html"><i class="fas fa-info-circle"></i> About</a>
            <a href="contactUsOwner.html"><i class="fas fa-envelope"></i> Contact</a>
            <a href="index.php"><i class="fas fa-sign-out-alt"></i> Log Out</a>
        </div>
        </li>
    </ul>
  </div>

  <div class="main-container">
    <div class="heading-div">
        <h1><i class="fas fa-shield-alt"></i> Gate Logs and Access</h1>
    </div>

    <div class="summary-container">
        <div class="summary-card">
            <h3><i class="fa-solid fa-right-to-bracket" style="color: #0d6efd;"></i> Entry</h3>
            <?php 
                $resultc = $conn->query("SELECT COUNT(*) AS total FROM gatelogs WHERE AccessType = 'entry' AND OwnerID = '$accountID'");
                $row = $resultc->fetch_assoc();
                $totalentry = $row['total'];
                echo "<p><strong><span id='newlyAdded'>$totalentry</span></strong></p>";
            ?>
        </div>

        <div class="summary-card">
            <h3><i class="fa-solid fa-right-from-bracket" style="color: #dc3545;"></i> Exit</h3>
            <?php 
                $resultb = $conn->query("SELECT COUNT(*) AS total FROM gatelogs WHERE AccessType = 'exit' AND OwnerID = '$accountID'");
                $row = $resultb->fetch_assoc();
                $totalexit = $row['total'];
                echo "<p><strong><span id='newlyAdded'>$totalexit</span></strong></p>";
            ?>
        </div>

        <div class="summary-card">
            <h3><i class="fas fa-list" style="color: #6c757d;"></i> Total Logs</h3>
            <?php 
                $resulta = $conn->query("SELECT COUNT(*) AS total FROM gatelogs WHERE OwnerID = '$accountID'");
                $row = $resulta->fetch_assoc();
                $totalLogs = $row['total'];
                echo "<p><strong><span id='newlyAdded'>$totalLogs</span></strong></p>";
            ?>
        </div>
    </div>

    <div class="filter-section">

        <form method="GET" action="gatesLogsOwnerAccess.php" style="display:inline;">
            <label for="typeFilter">Filter by Date:</label>
            <select name="typeFilter" onchange="this.form.submit()">
                <option value="DESC" <?= $selectedSort === 'DESC' ? 'selected' : '' ?>>Most Recent → Oldest</option>
                <option value="ASC" <?= $selectedSort === 'ASC' ? 'selected' : '' ?>>Oldest → Most Recent</option>
            </select>
            
            <label for="campusFilter">Campus:</label>
            <select name="campusFilter" onchange="this.form.submit()">
            <option value="All" <?= $selectedCampus === 'All' ? 'selected' : '' ?>>All</option>
            <?php 
                $campusResult = $conn->query("SELECT CampusName FROM campuses"); 
                while($row = $campusResult->fetch_assoc()){
                    $campusName = $row['CampusName'];
                    $selected = ($selectedCampus === $campusName) ? 'selected' : '';
                    echo "<option value=\"$campusName\" $selected>$campusName</option>";
                }
            ?>         
          </select>

            <label for="gateNumberFilter">Gate Number:</label>
            <select name="gateNumberFilter" onchange="this.form.submit()">
                <option value="All" <?= $selectedCampus === 'All' ? 'selected' : '' ?>>All</option>
                <?php 
                    $gateNumberResult = $conn->query("SELECT DISTINCT GateNumber FROM gates"); 
                    while($row = $gateNumberResult->fetch_assoc()){
                        $gateNumber = $row['GateNumber'];
                        $selectedGateN = ($selectedGateNumber === $gateNumber) ? 'selected' : '';
                        echo "<option value=\"$gateNumber\" $selectedGateN>$gateNumber</option>";
                    }
                ?>  
            </select>

        </form>
    </div>

    <div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>Log ID</th>
                <th>Plate Number</th>
                <th>Owner</th>
                <th>Campus</th>
                <th>Gate Number</th>
                <th>Access Type</th>
                <th>Timestamp</th>
                <th>Guard</th>
                <th style="width: 200px;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['LogID'] ?></td>
                        <td><?php echo $row['PlateNumber'] ?></td>
                        <td><?php echo $row['OwnerFullName'] ?></td>
                        <td><?php echo $row['CampusName'] ?></td>
                        <td><?php echo $row['GateNumber'] ?></td>
                        <td><?php echo $row['AccessType'] ?></td>
                        <td><?php echo $row['Timestamp'] ?></td>
                        <td><?php echo $row['GuardFullName'] ?></td>
                        <td class="action-btns">
                            <button 
                                    class="view-btn" 
                                    data-logid = "<?= $row['LogID'] ?>"
                                    data-platenumber = "<?= $row['PlateNumber'] ?>"
                                    data-ownerfullname = "<?= $row['OwnerFullName'] ?>"
                                    data-campusname = "<?= $row['CampusName'] ?>"
                                    data-gatenumber = "<?= $row['GateNumber'] ?>"
                                    data-accesstype = "<?= $row['AccessType'] ?>"
                                    data-timestamp = "<?= $row['Timestamp'] ?>"
                                    data-guardfullname = "<?= $row['GuardFullName'] ?>"
                                >
                                    <i class="fas fa-eye"></i> View
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="9">No logs found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    </div>
</div>


                <div id="modal" class="modal-overlay">
                    <div class="modal-dialog">
                        <button class="close-btn" id="closeModalBtn">&times;</button>
                        <div class="modal-header"><i class="fas fa-circle-info" style="margin-right: 8px;"></i> Movement Log History Information</div>

                            <div class="modal-body">             
                                <table class="modal-table">
                                    <tbody>
                                        <tr>
                                            <td><i class="fas fa-receipt" style="margin-right: 5px;"></i> Log ID:</td>
                                            <td><span id="modalLogID"></span></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-car-side" style="margin-right: 5px;"></i> Plate Number:</td>
                                            <td><span id="modalPlateNumber"></span></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-user" style="margin-right: 5px;"></i> Owner:</td>
                                            <td><span id="modelOwnerFullName"></span></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-school" style="margin-right: 5px;"></i> Campus Name:</td>
                                            <td><span id="modelCampusName"></span></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-door-open" style="margin-right: 5px;"></i> Gate Number:</td>
                                            <td><span id="modelGateNumber"></span></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-shield-alt" style="margin-right: 5px;"></i> Access Type:</td>
                                            <td><span id="modelAccesstype"></span></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-clock" style="margin-right: 5px;"></i> Timestamp:</td>
                                            <td><span id="modelTimestamp"></span></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-user-shield" style="margin-right: 5px;"></i> Guard:</td>
                                            <td><span id="modelGuardFullName"></span></td>
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

                    document.getElementById('modalLogID').textContent = btn.dataset.logid;
                    document.getElementById('modalPlateNumber').textContent = btn.dataset.platenumber;
                    document.getElementById('modelOwnerFullName').textContent = btn.dataset.ownerfullname;
                    document.getElementById('modelCampusName').textContent = btn.dataset.campusname;
                    document.getElementById('modelGateNumber').textContent = btn.dataset.gatenumber;
                    document.getElementById('modelAccesstype').textContent = btn.dataset.accesstype;
                    document.getElementById('modelTimestamp').textContent = btn.dataset.timestamp;
                    document.getElementById('modelGuardFullName').textContent = btn.dataset.guardfullname;

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