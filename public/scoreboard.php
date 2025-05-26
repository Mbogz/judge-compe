<?php 
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('../includes/db.php');

// Handle Clear Scoreboard action
if (isset($_POST['clear_scores'])) {
    $mysqli->query("TRUNCATE TABLE scores"); // Truncate all scores
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch average scores grouped by user
$query = "
    SELECT 
        u.name AS user_name, 
        ROUND(AVG(s.points), 2) AS average_score,
        COUNT(s.id) AS votes
    FROM scores s
    JOIN users u ON s.user_id = u.id
    GROUP BY s.user_id, u.name
    ORDER BY average_score DESC
";

$result = $mysqli->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Scoreboard</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            margin: 0;
            padding: 30px;
        }

        .form-card {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 800px;
            border: 2px solid #1a73e8;
        }

        .form-card h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #1a73e8;
        }

        th, td {
            padding: 14px 18px;
            text-align: center;
            border-bottom: 1px solid #e0e0e0;
            border-right: 1px solid #1a73e8;
        }

        th:last-child, td:last-child {
            border-right: none;
        }

        th {
            background-color: #1a73e8;
            color: #fff;
            font-weight: 600;
        }

        tr[data-score] {
            background-color: #fff;
            transition: background-color 0.3s ease;
        }

        tr[data-score]:hover {
            background-color: #f9f9f9;
        }

        p {
            text-align: center;
            font-size: 16px;
            color: #555;
        }

        .button-bar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
        }

        .clear-btn {
            background-color: #e53935;
            color: #fff;
            padding: 10px 18px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
        }

        .clear-btn:hover {
            background-color: #d32f2f;
        }

        /* Home button styling */
        .home-button {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #555;
            color: #fff;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 500;
            transition: background-color 0.3s ease;
            margin-left: 0px;
        }

        .home-button:hover {
            background-color: #333;
        }
    </style>

    <script>
        function confirmClear() {
            return confirm("Are you sure you want to clear the scoreboard? This action cannot be undone.");
        }
    </script>
</head>
<body>

<div class="form-card">
    <!-- Back to home button -->
    <a href="http://localhost/judging-system/" class="home-button">← Back to Home</a>

    <h2>Scoreboard</h2>

    <!-- Clear button form -->
    <form method="post" onsubmit="return confirmClear();">
        <div class="button-bar">
            <button type="submit" name="clear_scores" class="clear-btn">Clear Scoreboard</button>
        </div>
    </form>

    <!-- Display scoreboard -->
    <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>User Name</th>
                    <th>Average Score</th>
                    <th>Votes</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr data-score="<?= htmlspecialchars($row['average_score']) ?>">
                        <td><?= htmlspecialchars($row['user_name']) ?></td>
                        <td><?= $row['average_score'] ?></td>
                        <td><?= $row['votes'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No scores submitted yet.</p>
    <?php endif; ?>

</div>

</body>
</html>
