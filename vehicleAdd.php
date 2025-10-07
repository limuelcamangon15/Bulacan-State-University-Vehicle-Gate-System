 <?php
    include 'connection.php';

    $vehicleAdded = false;

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      $plateNumber = $_POST['plate_number'] ?? '';
      $ownerID = $_POST['owner_id'] ?? '';
      $vehicleType = $_POST['vehicle_type'] ?? '';
      $vehicleBrand = $_POST['vehicle_brand'] ?? '';
      $vehicleModel = $_POST['vehicle_model'] ?? '';
      $status = 'Pending';

      $platePhotoPath = null;

      if (isset($_FILES['plate_photo']) && $_FILES['plate_photo']['error'] === UPLOAD_ERR_OK) {
          $fileTmpPath   = $_FILES['plate_photo']['tmp_name'];
          $fileName      = $_FILES['plate_photo']['name'];
          $fileSize      = $_FILES['plate_photo']['size'];
          $fileType      = $_FILES['plate_photo']['type'];
          $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

          $uploadDir = __DIR__ . '/uploads/plates/';
          $uniqueName = 'plate_' . time() . '_' . uniqid() . '.' . $fileExtension;
          $destination = $uploadDir . $uniqueName;

          if (move_uploaded_file($fileTmpPath, $destination)) {
              $platePhotoPath = 'uploads/plates/' . $uniqueName;
          } 
          else {
              echo "Failed to move uploaded file.";
              exit;
          }
      }

      $stmt = $conn->prepare("INSERT INTO vehicles (PlateNumber, OwnerID, VehicleType, VehicleBrand, VehicleModel, Status, PlatePhoto) VALUES (?, ?, ?, ?, ?, ?, ?)");

      if ($stmt) {
          $stmt->bind_param("sssssss", $plateNumber, $ownerID, $vehicleType, $vehicleBrand, $vehicleModel, $status, $platePhotoPath);

          if ($stmt->execute()) {
              $vehicleAdded = true;
          } 
          else {
              echo "Error inserting vehicle: " . $stmt->error;
          }

          $stmt->close();
      }
      else {
          echo "SQL prepare error: " . $conn->error;
      }

      $conn->close();
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
        <li><a href="dashboardAdmin.php"><i class="fas fa-chart-line"></i> Dashboard</a></li>
        <li><a href="accountsAdminAccess.php"><i class="fas fa-users-cog"></i> Accounts</a></li>
        <li><a href="profilesAdminAccess.php"><i class="fas fa-id-card"></i> Profiles</a></li>
        <li><a href="campusesAdminAccess.php"><i class="fas fa-university"></i> Campuses</a></li>
        <li><a href="gatesAdminAccess.php"><i class="fas fa-door-open"></i> Gates</a></li>
        <li><a href="vehicleAdminAccess.php" class="active-nav"><i class="fas fa-car"></i> Vehicles</a></li>
        <li><a href="gatesAdminAccessLogs.php"><i class="fas fa-shield-alt"></i> Logs/Access</a></li>
        <li>
        <a><i class="fas fa-cog"></i> Settings</a>
        <div class="dropdown">
            <a href="accountAdmin.php"><i class="fas fa-user"></i> Account</a>
            <a href="profileAdmin.php"><i class="fas fa-user-circle"></i> Profile</a>
            <a href="aboutUs.html"><i class="fas fa-info-circle"></i> About</a>
            <a href="contactUsAdmin.html"><i class="fas fa-envelope"></i> Contact</a>
            <a href="index.php"><i class="fas fa-sign-out-alt"></i> Log Out</a>
        </div>
        </li>
    </ul>
  </div>

    <div class="form-container">
        <h2>Add Vehicle</h2>
        <p>Fill up this form to add a new vehicle</p>
    
        <form method="POST" enctype="multipart/form-data">
          <div class="row">
            <div class="input-group">
                <label for="plateNumber">Plate Number</label>
                <input type="text" id="plateNumber" name="plate_number" required>
                <span id="plateNumber-error" style="height: 0px; align-self: center; color: red; font-weight: bold; margin-top: 3px; font-size: 13px; display: none;">Plate Number already exists</span>
            </div>
    
            <div class="input-group">
                <label for="vehicleType">Vehicle Type</label>
                <select name="vehicle_type" id="vehicleType" required>    
                    <option value="" disabled selected></option>
                    <option value="Motorcycle">Motorcycle</option>
                    <option value="Tricycle">Tricycle</option>
                    <option value="SUV">SUV</option>
                    <option value="Pickup">Pickup</option>
                    <option value="Car">Car</option>
                    <option value="Truck">Truck</option>
                    <option value="Van">Van</option>
                    <option value="Bus">Bus</option>
                </select>
            </div>
          </div>
    
            <div class="input-group">
                <label for="vehicleBrand">Vehicle Brand</label>
                <input type="text" id="vehicleBrand" name="vehicle_brand" required>
            </div>
    
            <div class="input-group">
                <label for="vehicleModel">Vehicle Model</label>
                <input type="text" id="vehicleModel" name="vehicle_model" required>
            </div>
    
            <div class="input-group">
                <label for="ownerID">Owner ID</label>
                <input type="number" id="ownerID" name="owner_id" required>
                <span id="ownerID-error" style="height: 0px; align-self: center; color: red; font-weight: bold; margin-top: 3px; font-size: 13px; display: none;">Owner ID not found</span>
            </div>

            <div class="input-group full-width">
                <label for="platePhoto">Plate Number Photo</label>
                <input type="file" id="platePhoto" name="plate_photo" accept="image/*">
            </div>
    
            <button type="submit">Add Vehicle</button>
        </form>
         <div class="input-group full-width">
          <button class="cancel-btn" type="none" onclick="window.location.href='vehicleAdminAccess.php'">Cancel</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <?php if (!empty($vehicleAdded) && $vehicleAdded): ?>
      <script>
        Swal.fire({
          title: 'Vehicle Added Successfully!',
          text: 'The vehicle has been added successfully!',
          icon: 'success',
          confirmButtonText: 'OK',
          allowOutsideClick: true,
          allowEscapeKey: true
        }).then((result) => {
          if (result.isConfirmed || result.dismiss) {
            window.location.href = 'vehicleAdminAccess.php';
          }
        });
      </script>
    <?php endif; ?>



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


      const plateNumberInput = document.getElementById('plateNumber');
      const ownerIdInput = document.getElementById('ownerID');
      const submitButton = document.querySelector('button[type="submit"]');
    
      let isPlateNumberValid = false;
      let isOwnerIdValid = false;
    
      plateNumberInput.addEventListener('blur', checkPlateNumber);
      ownerIdInput.addEventListener('blur', checkOwnerId);
    
      function checkPlateNumber() {
        const plate = plateNumberInput.value.trim();
    
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'checkPlateNumber.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
        xhr.onload = function () {
          if (xhr.status === 200) {
            if (xhr.responseText === "exists") {
              document.getElementById('plateNumber-error').style.display = "block";
              isPlateNumberValid = false;
            } else {
              document.getElementById('plateNumber-error').style.display = "none";
              isPlateNumberValid = true;
            }
            validateForm();
          }
        };
    
        xhr.send('plate=' + encodeURIComponent(plate));
      }
    
      function checkOwnerId() {
        const ownerId = ownerIdInput.value.trim();
        const errorSpan = document.getElementById('ownerID-error');

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'checkOwnerId.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function () {
          if (xhr.status === 200) {
            const response = xhr.responseText.trim();
            errorSpan.style.display = "none";

            switch (response) {
              case "exists_owner":
                isOwnerIdValid = true;
                break;
              case "exists_not_owner":
                isOwnerIdValid = false;
                errorSpan.textContent = "The Owner ID is not yet registered as Vehicle Owner";
                errorSpan.style.display = "block";
                break;
              case "not_exists":
              default:
                isOwnerIdValid = false;
                errorSpan.textContent = "Owner ID not found.";
                errorSpan.style.display = "block";
            }

            validateForm();
          }
        };

        xhr.send('owner_id=' + encodeURIComponent(ownerId));
      }

    
      function validateForm() {
        submitButton.disabled = !(isPlateNumberValid && isOwnerIdValid);
      }
    </script>
    
  

</body>
</html>