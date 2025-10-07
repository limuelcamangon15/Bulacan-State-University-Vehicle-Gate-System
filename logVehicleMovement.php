 <?php
    session_start();
    include 'connection.php';

    $guardID = $_SESSION['account_id'];
    $logAdded = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $plateNumber = $_POST['plate_number'] ?? '';
        $ownerID = $_POST['owner_id'] ?? '';
        $campusID = $_POST['campus_id'] ?? '';
        $gateID = $_POST['gate_id'] ?? '';
        $accessType = $_POST['access_type'] ?? '';
        $loggedBy = $_POST['logged_by'] ?? '';

        $stmt = $conn->prepare("INSERT INTO gatelogs (PlateNumber, OwnerID, CampusID, GateID, AccessType, LoggedBy) VALUES (?, ?, ?, ?, ?, ?)");

        if ($stmt) {
            $stmt->bind_param("siiisi", $plateNumber, $ownerID, $campusID, $gateID, $accessType, $loggedBy);

            if ($stmt->execute()) {
                $logAdded = true;
            } 
            else {
                echo "Error inserting log: " . $stmt->error;
            }

            $stmt->close();
        }
        else {
            echo "SQL prepare error: " . $conn->error;
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register Vehicle</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <style>
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


    * {
       font-family: Arial, sans-serif;
    }

    body {
      margin: 0;
      background: linear-gradient(135deg, #0a0e17, #425a83);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      color: #ffffff;
    }

    .form-container {
      background: rgba(15, 23, 42, 0.95);
      padding: 30px 40px;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
      width: 70%;
      max-width: 500px;
      display: flex;
      flex-direction: column;
      transition: box-shadow 0.3s ease, transform 0.3s ease;
      animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
      0% {
        opacity: 0;
        transform: translateY(-30px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .form-container:hover {
      box-shadow: 0 0 40px rgba(0, 0, 0, 0.2);
      transform: scale(1.02);
    }

    h2 {
      margin: 0 0 10px;
      text-align: center;
      color: #fcd34d;
    }

    p {
      text-align: center;
      margin-bottom: 20px;
      color: #ffffff;
      font-weight: 500;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 15px;
      width: 100%;
    }

    .row {
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
    }

    .input-group {
      display: flex;
      flex-direction: column;
      flex: 1 1 0;
      min-width: 0;
    }

    .input-group.full-width {
      min-width: 100%;
    }

    label {
      margin-bottom: 6px;
      font-size: 0.95rem;
      font-weight: 600;
    }

    input, select {
      padding: 10px;
      font-size: 1rem;
      border: 1px solid #ccc;
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    input:focus, select:focus{
      outline: none;
      border-color: #60a5fa;
      background-color: #111827;
      transform: scale(1.05);
      color: #fcd34d;
    }

    button {
      margin-top: 10px;
      padding: 12px;
      background-color: #3b82f6;
      color: #fff;
      font-weight: bold;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      cursor: pointer;
      transition: background-color 0.3s ease, transform 0.3s ease;
    }

    button:hover {
      background-color: #2563eb;
      transform: scale(1.05);
    }

    .signin-link {
      margin-top: 15px;
      text-align: center;
      font-size: 0.95rem;
      color: #ffffff;
    }

    .error-message {
      height: 0px;
      align-self: center;
      color: red;
      font-weight: bold;
      margin-top: 3px;
      font-size: 13px;
      display: none;
    }

    .cancel-btn {
      margin-top: 10px;
      padding: 12px;
      background-color: #6b7280;
      color: #fff;
      font-weight: bold;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      cursor: pointer;
      transition: background-color 0.3s ease, transform 0.3s ease;
      width: 100%;
    }

    .cancel-btn:hover {
      background-color: #4b5563;
      transform: scale(1.05);
    }

    .active-nav {
        background-color: #facc15;
        color: #0f172a !important;
        font-weight: bold;
        box-shadow: 0 0 0 3px rgba(250, 204, 21, 0.4);
    }

    #navBtn {
        display: none;
        position: fixed;
        top: -6rem;
        left: 0;
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

    input[type="file"] {
        background-color: #1e293b;
        color: #f1f5f9;
        border: 1px solid #334155;
        padding: 10px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    input[type="file"]::file-selector-button {
        background-color: #3b82f6;
        color: white;
        border: none;
        padding: 8px 14px;
        margin-right: 12px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
        transition: background-color 0.3s ease, transform 0.2s ease;
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
    }

    @media (max-width: 480px) {
      .form-container {
        padding: 20px;
        width: 90%;
      }

      .row {
        flex-direction: column;
      }

      h2 {
        font-size: 1.5rem;
      }

      p {
        font-size: 0.95rem;
      }
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
        <li><a href="accountsGuardAccess.php"><i class="fas fa-users-cog"></i> Accounts</a></li>
        <li><a href="profilesGuardAccess.php"><i class="fas fa-id-card"></i> Profiles</a></li>
        <li><a href="campusesGuardAccess.php"><i class="fas fa-university"></i> Campuses</a></li>
        <li><a href="gatesGuardAccess.php" ><i class="fas fa-door-open"></i> Gates</a></li>
        <li><a href="vehicleGuardAccess.php"><i class="fas fa-car"></i> Vehicles</a></li>
        <li><a href="gatesGuardAccessLogs.php" class="active-nav"><i class="fas fa-shield-alt"></i> Gate Logs/Access</a></li>
        <li>
        <a><i class="fas fa-cog"></i> Settings</a>
        <div class="dropdown">
            <a href="profile.html"><i class="fas fa-user"></i> Account</a>
            <a href="notif.html"><i class="fas fa-id-badge"></i> Profile</a>
            <a href="aboutUs.html"><i class="fas fa-info-circle"></i> About</a>
            <a href="contactUsGuard.html"><i class="fas fa-envelope"></i> Contact</a>
            <a href="index.php"><i class="fas fa-sign-out-alt"></i> Log Out</a>
        </div>
        </li>
    </ul>
  </div>

    <div class="form-container">
        <h2>Log Vehicle Gate Movement</h2>
        <p>Fill up this form to log a vehicle movement</p>

        <form method="POST">
            <div class="row">
                <div class="input-group">
                    <label for="plateNumber">Plate Number</label>
                    <input type="text" id="plateNumber" name="plate_number" required>
                    <span id="plateNumber-error" style="height: 0px; align-self: center; color: red; font-weight: bold; margin-top: 3px; font-size: 13px; display: none;">Vehicle is not registered</span>
                </div>

                <div class="input-group">
                    <label for="ownerID">Owner ID</label>
                    <input type="number" id="ownerID" name="owner_id" required readonly>
                </div>
            </div>

            <div class="row">
                <div class="input-group">
                    <label for="campusID">Campus</label>
                    <select id="campusID" name="campus_id" required>
                        <option value="" disabled selected>Select campus</option>
                        <?php
                            $campusResult = $conn->query("SELECT CampusID, CampusName FROM campuses");
                            while($row = $campusResult->fetch_assoc()) {
                                echo "<option value='{$row['CampusID']}'>{$row['CampusName']}</option>";
                            }
                        ?>
                    </select>
                </div>

                <div class="input-group">
                    <label for="gateID">Gate</label>
                    <select id="gateID" name="gate_id" required>
                        <option value="" disabled selected>Select gate</option>
                    </select>
                </div>
            </div>


            <div class="row">
                <div class="input-group">
                    <label for="accessType">Access Type</label>
                    <select id="accessType" name="access_type" required>
                        <option value="" disabled selected>Select access type</option>
                        <option value="entry">Entry</option>
                        <option value="exit">Exit</option>
                    </select>
                    <span id="accessType-error" style="height: 0px; align-self: center; color: red; font-weight: bold; margin-top: 3px; font-size: 13px; display: none;"></span>
                </div>

                <div class="input-group">
                    <label for="loggedBy">Logged By (Guard ID)</label>
                    <input type="text" id="loggedBy" name="logged_by" required value="<?php echo $guardID; ?>" readonly >
                </div>
            </div>

            <button type="submit" style="margin-top: 25px;">Confirm Log</button>
            <button class="cancel-btn" type="button" onclick="window.location.href='gatesGuardAccessLogs.php'">Cancel</button>
        </form>
    </div>
    

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if (!empty($logAdded) && $logAdded): ?>
    <script>
        Swal.fire({
        title: 'Log Added!',
        text: 'Gate log has been recorded.',
        icon: 'success',
        confirmButtonText: 'OK',
        }).then(() => {
                window.location.href = 'gatesGuardAccessLogs.php';   
        });
    </script>
    <?php endif; ?>


    <script>
        document.getElementById('campusID').addEventListener('change', function (){
            const campusID = this.value;
            const gateSelect = document.getElementById('gateID');

            gateSelect.innerHTML = '<option value="" disabled selected>Loading gates...</option>';
        
            const xhr = new XMLHttpRequest();
            xhr.open('POST','getGatesByCampus.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            
            xhr.onload = function (){
                if(xhr.status === 200){
                    const gates = JSON.parse(xhr.responseText);

                    if(gates.length > 0){
                        gateSelect.innerHTML = '<option value="" disabled selected>Select gate</option>';
                        gates.forEach(gate =>{
                            const option = document.createElement('option');
                            option.value = gate.GateID;
                            option.textContent = gate.GateNumber;
                            gateSelect.appendChild(option);
                        });
                    }
                    else{
                        gateSelect.innerHTML = '<option value="" disabled selected>No gates found</option>';
                    }
                }
                else{
                    gateSelect.innerHTML = '<option value="" disabled selected>Error Loading Gates</option>';
                }
            };
        
            xhr.send('campus_id=' +encodeURIComponent(campusID));
        
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

      <script>
            const plateNumberInput = document.getElementById('plateNumber');
            const submitButton = document.querySelector('button[type="submit"]');
            const ownerIDField = document.getElementById('ownerID');
            const errorField = document.getElementById('plateNumber-error');
            const errorFieldAccessType = document.getElementById('accessType-error');

            let isPlateNumberValid = false;

            plateNumberInput.addEventListener('blur', checkPlateNumber);

            function checkPlateNumber() {
              const plate = plateNumberInput.value.trim();

              if (plate === "") {
                showError("Please enter a plate number");
                isPlateNumberValid = false;
                validateForm();
                return;
              }

              const xhr = new XMLHttpRequest();
              xhr.open('POST', 'checkPlateNumber.php', true);
              xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

              xhr.onload = function () {
                if (xhr.status === 200) {
                  if (xhr.responseText === "exists") {
                    getVehicleStatus(plate);
                  } 
                  else {
                    showError("Vehicle is not registered");
                    isPlateNumberValid = false;
                    validateForm();
                  }
                }
              };

              xhr.send('plate=' + encodeURIComponent(plate));
            }

            function getVehicleStatus(plate) {
              const xhr = new XMLHttpRequest();
              xhr.open('POST', 'getVehicleStatus.php', true);
              xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

              xhr.onload = function () {
                if (xhr.status === 200) {
                  try {
                    const response = JSON.parse(xhr.responseText);

                    if (response.success && response.vehicle_status === "Approved") {
                      
                      isPlateNumberValid = true;
                      hideError();
                      getOwnerID(plate);
                    } 
                    else {
                      showError("Vehicle status must be approved");
                      isPlateNumberValid = false;
                      ownerIDField.value = "";
                    }
                  } catch (e) {
                    console.error("Invalid JSON", e);
                    showError("Unexpected error occurred.");
                    isPlateNumberValid = false;
                    ownerIDField.value = "";
                  }

                  validateForm();
                }
              };

              xhr.send('plate=' + encodeURIComponent(plate));
            }

            function getOwnerID(plate) {
              const xhr = new XMLHttpRequest();
              xhr.open('POST', 'getOwnerID.php', true);
              xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

              xhr.onload = function () {
                if (xhr.status === 200) {
                  try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                      ownerIDField.value = response.owner_id;
                    } 
                    else {
                      ownerIDField.value = '';
                    }
                  } catch (e) {
                    console.error("Invalid JSON", e);
                  }
                }
              };

              xhr.send('plate=' + encodeURIComponent(plate));
            }

            const accessTypeSelect = document.getElementById('accessType');

            accessTypeSelect.addEventListener('input', handleAccessTypeChange);
            document.getElementById('campusID').addEventListener('input', handleAccessTypeChange);

            function handleAccessTypeChange() {
              const selectedAccessType = accessTypeSelect.value;
              const plate = plateNumberInput.value.trim();
              const currentCampusID = document.getElementById('campusID').value;

              if (!plate || !currentCampusID) return;

              const xhr = new XMLHttpRequest();
              xhr.open('POST', 'getLastLog.php', true);
              xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

              xhr.onload = function () {
                if (xhr.status !== 200) return;

                try {
                  const response = JSON.parse(xhr.responseText);

                  if (!response.success) return;

                  const lastLog = response.last_log;
                  const lastCampusID = response.last_campus_id;
                  const lastCampusName = response.last_campus_name;

                  if (lastLog === "entry" && selectedAccessType === "entry") {
                    showErrorForAccessType("Vehicle must exit before re-entering");
                    submitButton.disabled = true;
                    return;
                  }

                  if ((lastLog === null || lastLog === "exit") && selectedAccessType === "exit") {
                    showErrorForAccessType("Vehicle must enter before exiting");
                    submitButton.disabled = true;
                    return;
                  }

                  if (lastLog === "entry" && selectedAccessType === "exit" && currentCampusID != lastCampusID) {
                    showErrorForAccessType(
                      `Vehicle must exit from the same campus it entered: ${lastCampusName}`
                    );
                    submitButton.disabled = true;
                    return;
                  }

                  hideErrorForAccessType();
                  submitButton.disabled = !isPlateNumberValid;

                } catch (e) {
                  console.error("Invalid JSON response", e);
                }
              };

              xhr.send('plate=' + encodeURIComponent(plate));
            }


            function validateForm() {
              submitButton.disabled = !isPlateNumberValid;
            }

            function showError(message) {
              errorField.style.display = "block";
              errorField.textContent = message;
            }

            function showErrorForAccessType(message) {
              errorFieldAccessType.style.display = "block";
              errorFieldAccessType.textContent = message;
            }

            function hideErrorForAccessType() {
              errorFieldAccessType.style.display = "none";
              errorFieldAccessType.textContent = "";
            }

            function hideError() {
              errorField.style.display = "none";
              errorField.textContent = "";
            }
      </script>

    
  

</body>
</html>