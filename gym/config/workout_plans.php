<?php
// Include database connection
include '../includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch form data
    $user_id = $_POST['user_id'];
    $date = $_POST['date'];
    $duration = $_POST['duration'];
    $calories_burned = $_POST['calories_burned'];
    $notes = $_POST['notes'];

    // Insert workout plan into the database
    $sql = "INSERT INTO workouts (user_id, date, duration, calories_burned, notes) VALUES (?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("issis", $user_id, $date, $duration, $calories_burned, $notes);
    $stmt->execute();
    $stmt->close();

    // Redirect to the same page after submission
    header("Location: workout_plans.php?success=1");
    exit();
}

// Fetch users for the dropdown
$users = $mysqli->query("SELECT id, username FROM users");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cook Fitness - Add Workout Plan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            color: #212529;
        }

        .sidebar {
            background-color: #e9ecef;
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: -250px;
            padding-top: 60px;
            transition: 0.3s;
            z-index: 1000;
        }

        .sidebar.active {
            left: 0;
        }

        .sidebar a {
            color: #212529;
            padding: 10px 15px;
            text-decoration: none;
            display: block;
        }

        .sidebar a:hover {
            background-color: #dee2e6;
        }

        .main-content {
            padding: 20px;
            transition: 0.3s;
        }

        .main-content.active {
            margin-left: 250px;
        }

        .form-container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .navbar {
            background-color: #f8f9fa !important;
        }

        #menu-toggle {
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .main-content.active {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
        <span id="menu-toggle" class="navbar-toggler-icon"></span>
        <a class="navbar-brand" href="#">Cook Fitness</a>
    </div>
</nav>

<div class="sidebar" id="sidebar">
    <button id="close-sidebar" class="btn btn-link text-dark position-absolute top-0 end-0 mt-2 me-2">
        <i class="fas fa-times"></i>
    </button>
    <a href="#"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="#"><i class="fas fa-chart-line"></i> Progress Tracker</a>
    <a href="#"><i class="fas fa-file-alt"></i> Reports</a>
    <a href="#"><i class="fas fa-utensils"></i> Nutrition Tracker</a>
    <a href="#"><i class="fas fa-trophy"></i> Challenges</a>
    <a href="#"><i class="fas fa-dumbbell"></i> Workout Plans</a>
    <a href="#"><i class="fas fa-cog"></i> Settings</a>
</div>

<div class="main-content" id="main-content">
    <h1 class="mb-4">Add Workout Plan</h1>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Workout plan added successfully!</div>
    <?php endif; ?>

    <div class="form-container">
        <form method="post" action="workout_plans.php">
            <div class="mb-3">
                <label for="user_id" class="form-label">User</label>
                <select class="form-select" id="user_id" name="user_id" required>
                    <?php while ($user = $users->fetch_assoc()): ?>
                        <option value="<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['username']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>
            <div class="mb-3">
                <label for="duration" class="form-label">Duration (mins)</label>
                <input type="number" class="form-control" id="duration" name="duration" required>
            </div>
            <div class="mb-3">
                <label for="calories_burned" class="form-label">Calories Burned</label>
                <input type="number" class="form-control" id="calories_burned" name="calories_burned" required>
            </div>
            <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea class="form-control" id="notes" name="notes"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Workout Plan</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Sidebar toggle
    document.getElementById('menu-toggle').addEventListener('click', function () {
        document.getElementById('sidebar').classList.toggle('active');
        document.getElementById('main-content').classList.toggle('active');
    });
</script>
</body>
</html>
