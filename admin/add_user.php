<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('../includes/db.php');

$message = "";

// Handle Add User submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $name = trim($_POST['name'] ?? '');

    if (!empty($name)) {
        $check = $mysqli->prepare("SELECT id FROM users WHERE name = ?");
        $check->bind_param("s", $name);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "❌ User name already exists.";
        } else {
            $stmt = $mysqli->prepare("INSERT INTO users (name) VALUES (?)");
            if ($stmt) {
                $stmt->bind_param("s", $name);
                if ($stmt->execute()) {
                    $message = "✅ User added successfully.";
                } else {
                    $message = "❌ Database error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $message = "❌ Failed to prepare statement.";
            }
        }
        $check->close();
    } else {
        $message = "❌ Name is required.";
    }
}

// Handle Delete All Users action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_users'])) {
    if ($mysqli->query("TRUNCATE TABLE users")) {
        $message = "✅ All users deleted successfully.";
    } else {
        $message = "❌ Failed to delete users: " . $mysqli->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add User</title>
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
            padding: 10px 16px;
            border-radius: 8px;
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

    <h2>Add New User (Participant)</h2>

    <?php if (!empty($message)) : ?>
        <p><strong><?= htmlspecialchars($message) ?></strong></p>
    <?php endif; ?>

    <!-- Add User Form -->
    <form method="POST" action="">
        <div class="form-group">
            <label for="name">User Name:</label>
            <input type="text" id="name" name="name" required>
        </div>

        <button type="submit" name="add_user">Add User</button>
    </form>

    <!-- Delete All Users Form -->
    <form method="POST" action="">
        <button type="submit" name="delete_users" class="delete-btn">❌ Delete All Users</button>
    </form>

</div>

</body>
</html>
