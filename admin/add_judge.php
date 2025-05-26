<?php
// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Connect to database
require_once('../includes/db.php');

// Initialize message
$message = "";

// Helper function to sanitize input text
function sanitize_text($text) {
    return htmlspecialchars(trim($text), ENT_QUOTES, 'UTF-8');
}

// Handle form submission to add a judge
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_judge'])) {
    $username = sanitize_text($_POST['username'] ?? '');
    $display_name = sanitize_text($_POST['display_name'] ?? '');

    if (empty($username) || empty($display_name)) {
        $message = "❌ Both Username and Display Name are required.";
    } elseif (strlen($username) > 50) {
        $message = "❌ Username must be 50 characters or fewer.";
    } elseif (strlen($display_name) > 100) {
        $message = "❌ Display Name must be 100 characters or fewer.";
    } else {
        $stmt = $mysqli->prepare("INSERT INTO judges (username, display_name) VALUES (?, ?)");
        if (!$stmt) {
            $message = "❌ Database prepare error: " . $mysqli->error;
        } else {
            $stmt->bind_param("ss", $username, $display_name);
            if ($stmt->execute()) {
                $message = "✅ Judge added successfully.";
            } else {
                if ($mysqli->errno === 1062) {
                    $message = "❌ Username already exists. Please choose a different username.";
                } else {
                    $message = "❌ Database error: " . $stmt->error;
                }
            }
            $stmt->close();
        }
    }
}

// Handle Delete All Judges action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_judges'])) {
    if ($mysqli->query("DELETE FROM scores")) {
        if ($mysqli->query("TRUNCATE TABLE judges")) {
            $message = "✅ All judges and their scores deleted successfully.";
        } else {
            $message = "❌ Failed to delete judges: " . $mysqli->error;
        }
    } else {
        $message = "❌ Failed to delete scores: " . $mysqli->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Judge</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-card {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 800px;
            text-align: center;
            border: 2px solid #1a73e8;
        }

        .form-card h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .form-card form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 5px;
            color: #555;
        }

        .form-group input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .form-group input[type="text"]:focus {
            border-color: #1a73e8;
        }

        .form-card button {
            background-color: #1a73e8;
            color: #fff;
            border: none;
            padding: 14px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 300px;
            margin-left: 250px;
        }

        .form-card button:hover {
            background-color: #0049b7;
        }

        .form-card p {
            color: green;
            font-weight: bold;
        }

        .delete-btn {
            background-color: #e53935;
            margin-top: 10px;
        }

        .delete-btn:hover {
            background-color: #c62828;
        }

        .home-btn {
            background-color: #555;
            text-decoration: none;
            display: inline-block;
            padding: 10px 20px;
            border-radius: 10px;
            color: #fff;
            font-size: 14px;
            margin-bottom: 20px;
            transition: background-color 0.3s ease;
            margin-left: -650px;
        }

        .home-btn:hover {
            background-color: #333;
        }
    </style>
</head>
<body>

<div class="form-card">
    <a href="http://localhost/judging-system/" class="home-btn">← Back to Home</a>

    <h2>Add New Judge</h2>

    <?php if (!empty($message)) : ?>
        <p><strong><?= $message ?></strong></p>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" maxlength="50" required>
        </div>

        <div class="form-group">
            <label for="display_name">Display Name:</label>
            <input type="text" id="display_name" name="display_name" maxlength="100" required>
        </div>

        <button type="submit" name="add_judge">Add Judge</button>
    </form>

    <form method="POST" action="">
        <button type="submit" name="delete_judges" class="delete-btn">❌ Delete All Judges</button>
    </form>

</div>

</body>
</html>
