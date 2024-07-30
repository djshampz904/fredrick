<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Goal - Cook Fitness</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Add New Goal</h1>
    <form action="process_add_goal.php" method="POST">
        <div class="mb-3">
            <label for="exercise_id" class="form-label">Exercise (optional)</label>
            <select class="form-control" id="exercise_id" name="exercise_id">
                <option value="">Select an exercise</option>
                <?php foreach ($grouped_exercises as $category => $exercises): ?>
                    <optgroup label="<?php echo htmlspecialchars($category); ?>">
                        <?php foreach ($exercises as $exercise): ?>
                            <option value="<?php echo $exercise['id']; ?>"><?php echo htmlspecialchars($exercise['name']); ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="goal_type" class="form-label">Goal Type</label>
            <select class="form-control" id="goal_type" name="goal_type" required>
                <option value="Weight">Weight</option>
                <option value="Reps">Reps</option>
                <option value="Duration">Duration</option>
                <option value="Distance">Distance</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="target_value" class="form-label">Target Value</label>
            <input type="number" step="0.01" class="form-control" id="target_value" name="target_value" required>
        </div>
        <div class="mb-3">
            <label for="current_value" class="form-label">Current Value</label>
            <input type="number" step="0.01" class="form-control" id="current_value" name="current_value" required>
        </div>
        <div class="mb-3">
            <label for="deadline" class="form-label">Deadline</label>
            <input type="date" class="form-control" id="deadline" name="deadline" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Goal</button>
    </form>
</div>
</body>
</html>