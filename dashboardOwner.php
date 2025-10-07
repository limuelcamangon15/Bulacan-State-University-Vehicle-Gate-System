 <?php
  session_start();
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
            width: 100%;
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
            height: 400px;
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
        <li><a href="dashboardOwner.php" class="active-nav"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="gatesLogsOwnerAccess.php"><i class="fas fa-door-open"></i> Gate Logs</a></li>
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
      <h1><i class="fas fa-chart-line"></i> Dashboard</h1>
    </div>

  <div class="dashboard-cards">
  <div class="card active-vehicles">
    <h2><i class="fas fa-car" style="color:rgb(3, 100, 67);"></i> My Vehicles</h2>
    <?php 
      $accountID = $_SESSION['account_id'];
      $result = $conn->query("SELECT COUNT(*) AS myTotal FROM vehicles WHERE OwnerID = $accountID");
      $row = $result->fetch_assoc();
      echo "<p><strong>{$row['myTotal']}</strong></p>";
    ?>
  </div>

  <div class="card gate-status">
    <h2><i class="fas fa-sign-in-alt" style="color:rgb(236, 235, 224);"></i> Entries This Month</h2>
    <?php
      $result = $conn->query("SELECT COUNT(*) AS Total FROM gatelogs WHERE OwnerID = $accountID AND AccessType ='entry' AND MONTH(Timestamp) = MONTH(CURRENT_DATE())");
      $row = $result->fetch_assoc();
      echo "<p><strong>{$row['Total']}</strong></p>";
    ?>
  </div>

  <div class="card vehicle-movements">
    <h2><i class="fas fa-sign-out-alt" style="color:rgb(187, 1, 1);"></i> Exits This Month</h2>
    <?php
      $result = $conn->query("SELECT COUNT(*) AS Total FROM gatelogs WHERE OwnerID = $accountID AND AccessType ='exit' AND MONTH(Timestamp) = MONTH(CURRENT_DATE())");
      $row = $result->fetch_assoc();
      echo "<p><strong>{$row['Total']}</strong></p>";
    ?>
  </div>

  <div class="card campus-status">
    <h2><i class="fas fa-clock" style="color:#facc15;"></i> Last Vehicle Used</h2>
    <?php
      $result = $conn->query("SELECT PlateNumber, Timestamp FROM gatelogs WHERE OwnerID = $accountID ORDER BY Timestamp DESC LIMIT 1");
      if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<p>{$row['PlateNumber']}<br><small>at {$row['Timestamp']}</small></p>";
      } else {
        echo "<p>No recent activity</p>";
      }
    ?>
  </div>
</div>


  <div class="analytics-section">
    <div class="chart-section">
      <h2>My Vehicle Movement Overview</h2>
      <canvas id="myUsageChart" width="900px" height="130px"></canvas>
    </div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            function getLast12Months() {
                const months = [];
                const today = new Date();
                today.setDate(1);
                for (let i = 11; i >= -1; i--) {
                    const d = new Date(today.getFullYear(), today.getMonth() - i, 1);
                    const monthStr = d.toISOString().slice(0, 7);
                    months.push(monthStr);
                }
                return months;
            }

            fetch('getVehicleChartData.php')
                .then(response => response.json())
                .then(data => {
                    const last12Months = getLast12Months();

                    const dataMap = {};
                    data.forEach(item => {
                        dataMap[item.month] = {
                            entries: item.entries,
                            exits: item.exits
                        };
                    });

                    const labels = [];
                    const entries = [];
                    const exits = [];

                    last12Months.forEach(month => {

                        const [year, monthNum] = month.split('-');
                        const monthName = new Date(year, monthNum - 1).toLocaleString('default', {
                            month: 'short',
                            year: 'numeric'
                        });
                        labels.push(monthName);

                        if (dataMap[month]) {
                            entries.push(dataMap[month].entries);
                            exits.push(dataMap[month].exits);
                        } else {
                            entries.push(0);
                            exits.push(0);
                        }
                    });

                    const ctx = document.getElementById('myUsageChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: 'Entries',
                                    data: entries,
                                    borderColor: 'rgb(150, 140, 7)',
                                    backgroundColor: 'rgba(75, 192, 192, 0.3)',
                                    fill: true,
                                    tension: 0.3,
                                    pointRadius: 4
                                },
                                {
                                    label: 'Exits',
                                    data: exits,
                                    borderColor: 'rgb(206, 38, 38)',
                                    backgroundColor: 'rgba(116, 6, 6, 0.77)',
                                    fill: true,
                                    tension: 0.3,
                                    pointRadius: 4
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { position: 'top' },
                                title: {
                                    display: true,
                                    text: 'Monthly Vehicle Movement Overview (Last 12 Months)'
                                }
                            },
                            scales: {
                                y: { 
                                  beginAtZero: true,
                                  title: { display: true, text: 'Vehicle Movements' },
                                  grid: { color: 'rgba(255, 255, 255, 0.2)' }
                                },
                                x: {
                                    title: { display: true, text: 'Month' },
                                    grid: { color: 'rgba(255, 255, 255, 0.2)' }
                                }
                            }
                        }
                    });
                })
                .catch(error => {
                    console.error('Error loading data:', error);
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