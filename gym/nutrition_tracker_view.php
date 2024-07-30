<?php
require_once 'config/nutrition_tracker.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cook Fitness - Nutrition Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="styles/dash.css" rel="stylesheet">
    <link href="styles/nutrition_tracker.css" rel="stylesheet">
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
    <a href="dashboard_view.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="nutrition_tracker_view.php"><i class="fas fa-utensils"></i> Nutrition Tracker</a>
    <a href="add_workout.php"><i class="fas fa-dumbbell"></i> Add workout</a>
    <a href="settings_view.php"><i class="fas fa-cog"></i> Settings</a>
    <a href="config/logout.php"><i class="fas fa-sign-out-alt"></i> Log Out</a>
</div>

<div class="main-content" id="main-content">
    <h1 class="mb-4">Nutrition Tracker <i class="fas fa-carrot"></i></h1>
    <?php
    if (isset($_SESSION['success_message'])) {
        echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
        unset($_SESSION['success_message']);
    }
    if (isset($_SESSION['error_message'])) {
        echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
        unset($_SESSION['error_message']);
    }
    ?>

    <div class="row">
        <div class="col-md-6">
            <div class="nutrition-summary">
                <h2>Today's Summary <i class="fas fa-calendar-day"></i></h2>
                <div class="macro-progress">
                    <div class="macro-item">
                        <i class="fas fa-fire"></i> Calories
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar"
                                 style="width: <?php echo min(($total_calories / $user_goals['calorie_goal']) * 100, 100); ?>%"
                                 aria-valuenow="<?php echo $total_calories; ?>" aria-valuemin="0"
                                 aria-valuemax="<?php echo $user_goals['calorie_goal']; ?>"></div>
                        </div>
                        <span><?php echo $total_calories; ?> / <?php echo $user_goals['calorie_goal']; ?> kcal</span>
                    </div>
                    <div class="macro-item">
                        <i class="fas fa-drumstick-bite"></i> Protein
                        <div class="progress">
                            <div class="progress-bar bg-info" role="progressbar"
                                 style="width: <?php echo min(($total_protein / $user_goals['protein_goal']) * 100, 100); ?>%"
                                 aria-valuenow="<?php echo $total_protein; ?>" aria-valuemin="0"
                                 aria-valuemax="<?php echo $user_goals['protein_goal']; ?>"></div>
                        </div>
                        <span><?php echo $total_protein; ?>g / <?php echo $user_goals['protein_goal']; ?>g</span>
                    </div>
                    <div class="macro-item">
                        <i class="fas fa-bread-slice"></i> Carbs
                        <div class="progress">
                            <div class="progress-bar bg-warning" role="progressbar"
                                 style="width: <?php echo min(($total_carbs / $user_goals['carb_goal']) * 100, 100); ?>%"
                                 aria-valuenow="<?php echo $total_carbs; ?>" aria-valuemin="0"
                                 aria-valuemax="<?php echo $user_goals['carb_goal']; ?>"></div>
                        </div>
                        <span><?php echo $total_carbs; ?>g / <?php echo $user_goals['carb_goal']; ?>g</span>
                    </div>
                    <div class="macro-item">
                        <i class="fas fa-cheese"></i> Fats
                        <div class="progress">
                            <div class="progress-bar bg-danger" role="progressbar"
                                 style="width: <?php echo min(($total_fats / $user_goals['fat_goal']) * 100, 100); ?>%"
                                 aria-valuenow="<?php echo $total_fats; ?>" aria-valuemin="0"
                                 aria-valuemax="<?php echo $user_goals['fat_goal']; ?>"></div>
                        </div>
                        <span><?php echo $total_fats; ?>g / <?php echo $user_goals['fat_goal']; ?>g</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="nutrition-chart">
                <h2>Macronutrient Breakdown <i class="fas fa-chart-pie"></i></h2>
                <canvas id="macroChart"></canvas>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <h2>Today's Food Log <i class="fas fa-utensils"></i></h2>
            <div class="food-log">
                <?php foreach ($nutrition_logs as $log): ?>
                    <div class="food-item">
                        <div class="meal-type">
                            <?php
                            switch ($log['meal_type']) {
                                case 'Breakfast':
                                    echo '<i class="fas fa-sun"></i> ';
                                    break;
                                case 'Lunch':
                                    echo '<i class="fas fa-cloud-sun"></i> ';
                                    break;
                                case 'Dinner':
                                    echo '<i class="fas fa-moon"></i> ';
                                    break;
                                case 'Snack':
                                    echo '<i class="fas fa-cookie-bite"></i> ';
                                    break;
                            }
                            echo $log['meal_type'];
                            ?>
                        </div>
                        <div class="food-name"><?php echo $log['food_item']; ?></div>
                        <div class="food-macros">
                            <span><i class="fas fa-fire"></i> <?php echo $log['calories']; ?> kcal</span>
                            <span><i class="fas fa-drumstick-bite"></i> <?php echo $log['protein']; ?>g</span>
                            <span><i class="fas fa-bread-slice"></i> <?php echo $log['carbohydrates']; ?>g</span>
                            <span><i class="fas fa-cheese"></i> <?php echo $log['fats']; ?>g</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <h2>Add Food <i class="fas fa-plus-circle"></i></h2>
            <form id="addFoodForm" action="config/add_food.php" method="POST">
                <div class="mb-3">
                    <label for="meal_type" class="form-label">Meal Type</label>
                    <select class="form-select" id="meal_type" name="meal_type" required>
                        <option value="Breakfast">Breakfast</option>
                        <option value="Lunch">Lunch</option>
                        <option value="Dinner">Dinner</option>
                        <option value="Snack">Snack</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="food_item" class="form-label">Food Item</label>
                    <input type="text" class="form-control" id="food_item" name="food_item" required>
                </div>
                <div class="mb-3">
                    <label for="calories" class="form-label">Calories</label>
                    <input type="number" class="form-control" id="calories" name="calories" required>
                </div>
                <div class="mb-3">
                    <label for="protein" class="form-label">Protein (g)</label>
                    <input type="number" class="form-control" id="protein" name="protein" step="0.1" required>
                </div>
                <div class="mb-3">
                    <label for="carbohydrates" class="form-label">Carbohydrates (g)</label>
                    <input type="number" class="form-control" id="carbohydrates" name="carbohydrates" step="0.1"
                           required>
                </div>
                <div class="mb-3">
                    <label for="fats" class="form-label">Fats (g)</label>
                    <input type="number" class="form-control" id="fats" name="fats" step="0.1" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Food</button>
            </form>
        </div>
    </div>
</div>

<!-- Add Food Modal -->
<div class="modal fade" id="addFoodModal" tabindex="-1" aria-labelledby="addFoodModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFoodModalLabel">Add Food Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Add your food input form here -->
                <form id="addFoodForm">
                    <!-- Form fields for food item, calories, macros, etc. -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveFoodBtn">Save Food</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Sidebar toggle
    document.getElementById('menu-toggle').addEventListener('click', function () {
        document.getElementById('sidebar').classList.toggle('active');
        document.getElementById('main-content').classList.toggle('active');
    });

    // Close sidebar
    document.getElementById('close-sidebar').addEventListener('click', function () {
        document.getElementById('sidebar').classList.toggle('active');
        document.getElementById('main-content').classList.toggle('active');
    });

    // Macronutrient Breakdown Chart
    var macroCtx = document.getElementById('macroChart').getContext('2d');
    var macroChart = new Chart(macroCtx, {
        type: 'doughnut',
        data: {
            labels: ['Protein', 'Carbs', 'Fats'],
            datasets: [{
                data: [<?php echo $total_protein; ?>, <?php echo $total_carbs; ?>, <?php echo $total_fats; ?>],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(255, 99, 132, 0.8)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
</script>
</body>
</html>