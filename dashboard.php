<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_role'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['user_role'];
$name = $_SESSION['user_name'];
$email = $_SESSION['user_email'];

// Role-specific data
$adminData = [
    'totalDoctors' => 150,
    'totalPatients' => 1030,
    'pendingAppointments' => 28,
    'todaysAppointments' => 45
];

$doctorData = [
    'pendingAppointments' => 8,
    'todaysPatients' => 12,
    'totalPatients' => 124,
    'reviews' => 32
];

$patientData = [
    'upcomingAppointments' => 2,
    'prescriptions' => 5,
    'medicalTests' => 3,
    'lastCheckup' => '2025-04-08'
];

// Function to check access permission
function checkAccess($requiredRole, $currentRole) {
    // Admin has access to everything
    if ($currentRole === 'admin') return true;
    
    // Doctor has access to doctor and patient pages
    if ($currentRole === 'doctor' && ($requiredRole === 'doctor' || $requiredRole === 'patient')) return true;
    
    // Patient only has access to patient pages
    if ($currentRole === 'patient' && $requiredRole === 'patient') return true;
    
    return false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newUser = [
        'fullname' => $_POST['fullname'],
        'email' => $_POST['email'],
        'role' => $_POST['role'],
        'department' => $_POST['department'] ?? '',
        'status' => $_POST['status']
    ];

    // Contoh tampilan langsung (testing output)
    echo "<pre>";
    print_r($newUser);
    echo "</pre>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - WCHospital</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .dashboard-container {
            padding: 12rem 2rem 2rem;
        }
        .welcome-section {
            background: #f5f5f5;
            padding: 2rem;
            border-radius: .5rem;
            margin-bottom: 3rem;
        }
        .welcome-section h1 {
            font-size: 3rem;
            color: var(--black);
            margin-bottom: 1rem;
        }
        .welcome-section p {
            font-size: 1.6rem;
            color: var(--light-color);
        }
        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(25rem, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        .stat-card {
            background: #fff;
            border-radius: .5rem;
            box-shadow: var(--box-shadow);
            padding: 2rem;
            text-align: center;
        }
        .stat-card i {
            font-size: 3.5rem;
            color: var(--green);
            margin-bottom: 1rem;
        }
        .stat-card h3 {
            font-size: 2.5rem;
            color: var(--black);
            margin-bottom: .5rem;
        }
        .stat-card p {
            font-size: 1.4rem;
            color: var(--light-color);
        }
        .dashboard-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(30rem, 1fr));
            gap: 2rem;
        }
        .action-card {
            background: #fff;
            border-radius: .5rem;
            box-shadow: var(--box-shadow);
            padding: 2rem;
        }
        .action-card h3 {
            font-size: 2rem;
            color: var(--black);
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #eee;
            padding-bottom: 1rem;
        }
        .action-list {
            list-style: none;
        }
        .action-list li {
            font-size: 1.5rem;
            padding: 1rem 0;
            border-bottom: 1px solid #f5f5f5;
        }
        .action-list li:last-child {
            border-bottom: none;
        }
        .action-list li i {
            color: var(--green);
            margin-right: .5rem;
        }
        .logout-btn {
            margin-top: 2rem;
            text-align: center;
        }
        .logout-btn .btn {
            background: #e74c3c;
            color: #fff;
            border: none;
        }
        .logout-btn .btn:hover {
            background: #c0392b;
        }
        .logout-btn .btn:hover span {
            background: #e74c3c;
            color: #fff;
        }
        .role-tag {
            display: inline-block;
            padding: .3rem 1rem;
            border-radius: 2rem;
            font-size: 1.4rem;
            margin-left: 1rem;
            text-transform: uppercase;
        }
        .role-admin {
            background: #e74c3c;
            color: #fff;
        }
        .role-doctor {
            background: var(--green);
            color: #fff;
        }
        .role-patient {
            background: #3498db;
            color: #fff;
        }
    </style>
</head>
<body>

<!-- header section starts  -->
<header class="header">
    <a href="dashboard.php" class="logo"> <i class="fas fa-heartbeat"></i> <strong>WC</strong>medical </a>
    <nav class="navbar">
        <a href="dashboard.php">dashboard</a>
        <?php if (checkAccess('admin', $role)): ?>
        <a href="#">manage users</a>
        <a href="#">hospital stats</a>
        <?php endif; ?>
        <?php if (checkAccess('doctor', $role)): ?>
        <a href="#">patients</a>
        <a href="#">appointments</a>
        <?php endif; ?>
        <?php if (checkAccess('patient', $role)): ?>
        <a href="#">my appointments</a>
        <a href="#">medical records</a>
        <?php endif; ?>
        <a href="login.php?logout=1">logout</a>
    </nav>
    <div id="menu-btn" class="fas fa-bars"></div>
</header>
<!-- header section ends -->

<div class="dashboard-container">
    <div class="welcome-section">
        <h1>Welcome, <?php echo $name; ?>
            <?php if ($role === 'admin'): ?>
                <span class="role-tag role-admin">Administrator</span>
            <?php elseif ($role === 'doctor'): ?>
                <span class="role-tag role-doctor">Doctor</span>
            <?php else: ?>
                <span class="role-tag role-patient">Patient</span>
            <?php endif; ?>
        </h1>
        <p>This is your personalized WCHospital dashboard. Here you can manage your healthcare activities.</p>
    </div>

    <div class="dashboard-stats">
        <?php if (checkAccess('admin', $role)): ?>
            <!-- Admin Stats -->
            <div class="stat-card">
                <i class="fas fa-user-md"></i>
                <h3><?php echo $adminData['totalDoctors']; ?></h3>
                <p>Total Doctors</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-users"></i>
                <h3><?php echo $adminData['totalPatients']; ?></h3>
                <p>Registered Patients</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-calendar-check"></i>
                <h3><?php echo $adminData['pendingAppointments']; ?></h3>
                <p>Pending Appointments</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-clinic-medical"></i>
                <h3><?php echo $adminData['todaysAppointments']; ?></h3>
                <p>Today's Appointments</p>
            </div>
        <?php elseif (checkAccess('doctor', $role) && !checkAccess('admin', $role)): ?>
            <!-- Doctor Stats -->
            <div class="stat-card">
                <i class="fas fa-calendar-check"></i>
                <h3><?php echo $doctorData['pendingAppointments']; ?></h3>
                <p>Pending Appointments</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-user-injured"></i>
                <h3><?php echo $doctorData['todaysPatients']; ?></h3>
                <p>Today's Patients</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-users"></i>
                <h3><?php echo $doctorData['totalPatients']; ?></h3>
                <p>Your Patients</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-star"></i>
                <h3><?php echo $doctorData['reviews']; ?></h3>
                <p>Patient Reviews</p>
            </div>
        <?php else: ?>
            <!-- Patient Stats -->
            <div class="stat-card">
                <i class="fas fa-calendar"></i>
                <h3><?php echo $patientData['upcomingAppointments']; ?></h3>
                <p>Upcoming Appointments</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-prescription"></i>
                <h3><?php echo $patientData['prescriptions']; ?></h3>
                <p>Active Prescriptions</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-vial"></i>
                <h3><?php echo $patientData['medicalTests']; ?></h3>
                <p>Pending Medical Tests</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-calendar-check"></i>
                <h3><?php echo date('d M Y', strtotime($patientData['lastCheckup'])); ?></h3>
                <p>Last Checkup Date</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="dashboard-actions">
        <?php if (checkAccess('admin', $role)): ?>
            <!-- Admin Actions -->
            <div class="action-card">
                <h3>Quick Actions</h3>
                <ul class="action-list">
                    <li><i class="fas fa-plus-circle"></i> Add New Doctor</li>
                    <li><i class="fas fa-user-plus"></i> Register New Patient</li>
                    <li><i class="fas fa-calendar-plus"></i> Schedule Appointments</li>
                    <li><i class="fas fa-file-medical"></i> View Hospital Reports</li>
                    <li><i class="fas fa-cog"></i> System Settings</li>
                </ul>
            </div>
            <div class="action-card">
                <h3>Recent Notifications</h3>
                <div class="action-card">
    <h3>Add New User</h3>
    <form method="POST" class="user-form">
        <div class="form-group">
            <label>Full Name:</label>
            <input type="text" name="fullname" class="box" placeholder="Enter full name" required>
        </div>

        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" class="box" placeholder="Enter email" required>
        </div>

        <div class="form-group">
            <label>User Role:</label>
            <select name="role" class="box" id="roleSelect" required>
                <option value="">Select Role</option>
                <option value="admin">Administrator</option>
                <option value="doctor">Doctor</option>
                <option value="patient">Patient</option>
            </select>
        </div>

        <div class="form-group" id="departmentGroup" style="display: none;">
            <label>Department:</label>
            <select name="department" class="box">
                <option value="">Select Department</option>
                <option value="cardiology">Cardiology</option>
                <option value="pediatrics">Pediatrics</option>
                <option value="neurology">Neurology</option>
                <option value="orthopedics">Orthopedics</option>
            </select>
        </div>

        <div class="form-group">
            <label>Status:</label>
            <div class="radio-group">
                <label class="radio-label">
                    <input type="radio" name="status" value="active" checked> Active
                </label>
                <label class="radio-label">
                    <input type="radio" name="status" value="inactive"> Inactive
                </label>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn">Create User</button>
        </div>
    </form>
</div>

                <ul class="action-list">
                    <li><i class="fas fa-bell"></i> New doctor application received</li>
                    <li><i class="fas fa-bell"></i> Monthly revenue report is ready</li>
                    <li><i class="fas fa-bell"></i> System maintenance scheduled for tomorrow</li>
                    <li><i class="fas fa-bell"></i> 5 appointment requests need approval</li>
                </ul>
            </div>
        <?php elseif (checkAccess('doctor', $role) && !checkAccess('admin', $role)): ?>
            <!-- Doctor Actions -->
            <div class="action-card">
                <h3>Quick Actions</h3>
                <ul class="action-list">
                    <li><i class="fas fa-calendar-check"></i> View Today's Appointments</li>
                    <li><i class="fas fa-prescription"></i> Write New Prescription</li>
                    <li><i class="fas fa-notes-medical"></i> Update Patient Records</li>
                    <li><i class="fas fa-file-medical"></i> Order Medical Tests</li>
                    <li><i class="fas fa-user-md"></i> Update Availability Schedule</li>
                </ul>
            </div>
            <div class="action-card">
                <h3>Upcoming Appointments</h3>
                <ul class="action-list">
                    <li><i class="fas fa-user"></i> Budi Santoso - 09:30 AM Today</li>
                    <li><i class="fas fa-user"></i> Siti Rahayu - 11:15 AM Today</li>
                    <li><i class="fas fa-user"></i> Ahmad Dhani - 02:00 PM Today</li>
                    <li><i class="fas fa-user"></i> Dewi Fortuna - 04:30 PM Today</li>
                </ul>
            </div>
        <?php else: ?>
            <!-- Patient Actions -->
            <div class="action-card">
                <h3>Quick Actions</h3>
                <ul class="action-list">
                    <li><i class="fas fa-calendar-plus"></i> Book New Appointment</li>
                    <li><i class="fas fa-prescription"></i> View Prescriptions</li>
                    <li><i class="fas fa-file-medical"></i> Access Medical Records</li>
                    <li><i class="fas fa-comment-medical"></i> Contact Your Doctor</li>
                    <li><i class="fas fa-user-edit"></i> Update Personal Information</li>
                </ul>
            </div>
            <div class="action-card">
                <h3>Upcoming Appointments</h3>
                <ul class="action-list">
                    <li><i class="fas fa-calendar"></i> Dr. Ahmad Wijaya - April 20, 2025 (10:30 AM)</li>
                    <li><i class="fas fa-calendar"></i> Dr. Siti Rahayu - May 5, 2025 (01:15 PM)</li>
                </ul>
            </div>
        <?php endif; ?>
    </div>

    <div class="logout-btn">
        <a href="login.php?logout=1" class="btn">Logout <span class="fas fa-sign-out-alt"></span></a>
    </div>
</div>

<!-- footer section starts  -->
<section class="footer">
    <div class="credit"> created by <span>Riendra Z.R</span> | all rights reserved </div>
</section>
<!-- footer section ends -->

<script>
    document.getElementById('roleSelect').addEventListener('change', function() {
        const role = this.value;
        const doctorFields = document.getElementById('doctorFields');

        if (role === 'doctor') {
            doctorFields.style.display = 'block';
        } else {
            doctorFields.style.display = 'none';
        }
    });
</script>


</body>
</html>