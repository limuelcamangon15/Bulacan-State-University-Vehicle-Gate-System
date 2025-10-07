 <?php
  include 'connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard</title>
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
        margin-left: 20px;
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
        
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .card {
            padding: 20px;
            border-radius: 15px;
            background: linear-gradient(135deg, #1f2937, #3b82f6);
            color: #ffffff;
            box-shadow: 0 6px 15px rgba(0,0,0,0.3);
            transition: transform 0.3s ease;
        }

        .card h2 {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 2rem;
            font-weight: bold;
        }

        .card:hover {
            transform: translateY(-5px) scale(1.03);
        }

        .total-users { background: linear-gradient(135deg, #3b82f6, #2563eb); }
        .active-vehicles { background: linear-gradient(135deg, #10b981, #047857); }
        .gate-status { background: linear-gradient(135deg, #f59e0b, #b45309); }
        .campus-status { background: linear-gradient(135deg,rgb(134, 124, 113),rgb(66, 61, 58)); }
        .vehicle-movements { background: linear-gradient(135deg, #ef4444, #991b1b); }

        .chart-section {
            margin-bottom: 40px;
            min-width: 600px;
            background: #1e293b;
            border-radius: 12px;
            box-shadow: inset 0 0 10px rgba(0,0,0,0.3);
        }

        .chart-section h2{
            color: #facc15;
            justify-self: center;
        }

        .analytics-section {
            display: flex;
            flex-direction: row;
            width: 100%;
            height: 430px;
            gap: 20px;
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
        <li><a href="dashboardAdmin.php" class="active-nav"><i class="fas fa-chart-line"></i> Dashboard</a></li>
        <li><a href="accountsAdminAccess.php" ><i class="fas fa-users-cog"></i> Accounts</a></li>
        <li><a href="profilesAdminAccess.php"><i class="fas fa-id-card"></i> Profiles</a></li>
        <li><a href="campusesAdminAccess.php"><i class="fas fa-university"></i> Campuses</a></li>
        <li><a href="gatesAdminAccess.php"><i class="fas fa-door-open"></i> Gates</a></li>
        <li><a href="vehicleAdminAccess.php"><i class="fas fa-car"></i> Vehicles</a></li>
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

  <div class="main-container">
  <div class="heading-div">
    <h1><i class="fas fa-chart-line"></i> Vehicle Gate Admin Dashboard</h1>
  </div>

  <div class="dashboard-cards">
    <div class="card total-users">
      <h2><i class="fa-solid fa-user-check" style="color:rgb(13, 253, 69);"></i> Registered Accounts</h2>
            <?php 
                $resultTotalRegistered = $conn->query("SELECT COUNT(*) AS TotalRegistered FROM accounts");
                $row = $resultTotalRegistered->fetch_assoc();
                $totalRegistered = $row['TotalRegistered'];
                echo "<p><strong><span id='newlyAdded'>$totalRegistered</span></strong></p>";
            ?>
    </div>
    <div class="card active-vehicles">
      <h2><i class="fas fa-check-circle" style="color: #198754;"></i> Registered Vehicles</h2>
            <?php 
                $resultTotalApproved = $conn->query("SELECT COUNT(*) AS TotalApproved FROM vehicles WHERE Status = 'Approved'");
                $row = $resultTotalApproved->fetch_assoc();
                $totalApproved = $row['TotalApproved'];
                echo "<p><strong><span id='newlyAdded'>$totalApproved</span></strong></p>";
            ?>
    </div>
    <div class="card gate-status">
      <h2><i class="fa-solid fa-door-open"></i> Gates</h2>
            <?php 
                $resultTotalGates = $conn->query("SELECT COUNT(*) AS TotalGates FROM gates");
                $row = $resultTotalGates->fetch_assoc();
                $totalGates = $row['TotalGates'];
                echo "<p><strong><span id='newlyAdded'>$totalGates</span></strong></p>";
            ?>
    </div>
    <div class="card campus-status">
      <h2><i class="fa-solid fa-building-columns" style="color:rgb(29, 42, 53);"></i> Campuses</h2>
            <?php 
                $resultTotalCampuses = $conn->query("SELECT COUNT(*) AS TotalCampuses FROM campuses");
                $row = $resultTotalCampuses->fetch_assoc();
                $totalCampuses = $row['TotalCampuses'];
                echo "<p><strong><span id='newlyAdded'>$totalCampuses</span></strong></p>";
            ?>
    </div>
    <div class="card vehicle-movements">
      <h2><i class="fas fa-list" style="color:rgb(213, 226, 32);"></i> Vehicle Movements</h2>
            <?php 
                $resulta = $conn->query("SELECT COUNT(*) AS total FROM gatelogs");
                $row = $resulta->fetch_assoc();
                $totalLogs = $row['total'];
                echo "<p><strong><span id='newlyAdded'>$totalLogs</span></strong></p>";
            ?>
    </div>
  </div>

  <div class="analytics-section">
      <div class="chart-section">
        <h2>Vehicle Movement Overview</h2>
        <canvas id="usageChart"></canvas>
      </div>

      <div class="chart-section">
        <h2>Monthly Vehicle Registrations Trend</h2>
        <canvas id="registrationsChart" width="1500px" height="350px"></canvas>
      </div>    
  </div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  fetch('getChartData.php')
    .then(response => response.json())
    .then(data => {
      const months = data.map(item => item.month);
      const entries = data.map(item => parseInt(item.entries));
      const exits = data.map(item => parseInt(item.exits));

      const ctx = document.getElementById('usageChart').getContext('2d');
      const usageChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: months,
          datasets: [
            {
              label: 'Entries',
              data: entries,
              backgroundColor: 'rgba(59, 130, 246, 0.7)',
              borderColor: 'rgba(59, 130, 246, 1)',
              borderWidth: 1
            },
            {
              label: 'Exits',
              data: exits,
              backgroundColor: 'rgba(239, 68, 68, 0.7)',
              borderColor: 'rgba(239, 68, 68, 1)',
              borderWidth: 1
            }
          ]
        },
        options: {
          responsive: true,
          plugins: {
            title: {
              display: true,
              text: 'Monthly Gate Entry/Exit',
              color: '#fff',
              font: {
                size: 18
              }
            },
            legend: {
              labels: {
                color: '#fff'
              }
            }
          },
          scales: {
            x: {
              title: { display: true, text: 'Current Month' },
              ticks: { color: '#fff' },
              grid: { color: 'rgba(255,255,255,0.1)' }
            },
            y: {
              beginAtZero: true,
              title: { display: true, text: 'Vehicle Movements' },
              ticks: { color: '#fff' },
              grid: { color: 'rgba(255,255,255,0.1)' }
            }
          }
        }
      });
    })
    .catch(error => console.error('Error loading chart data:', error));
</script>

<script>
  fetch('getRegistrationsTrend.php')
  .then(response => response.json())
  .then(data => {
    data.sort((a, b) => a.year === b.year ? a.month_num - b.month_num : a.year - b.year);

    const labels = data.map(item => `${item.month} ${item.year}`);
    const registrations = data.map(item => item.registrations);


    const ctx = document.getElementById('registrationsChart').getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Registrations',
          data: registrations,
          fill: false,
          borderColor: '#facc15',
          backgroundColor: 'rgba(255, 206, 86, 0.5)',
          tension: 0.3
        }]
      },
      options: {
        responsive: true,
        plugins: {        
          legend: { labels: { color: '#fff' } }
        },
        scales: {
          x: { 
              title: { display: true, text: 'Months' },
              ticks: { color: '#fff' }, 
              grid: { color: 'rgba(255,255,255,0.1)' }
          },
          y: { 
              beginAtZero: true, 
              title: { display: true, text: 'Registrations' },
              ticks: { color: '#fff' }, 
              grid: { color: 'rgba(255,255,255,0.1)' } 
          }
        }
      }
    });
  })
  .catch(error => console.error('Error loading registrations chart data:', error));

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