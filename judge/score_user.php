<?php
// Basic HTTP Authentication for Judge Page
$valid_username = 'judge1';
$valid_password = 'judgepass';

if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) ||
    $_SERVER['PHP_AUTH_USER'] !== $valid_username || $_SERVER['PHP_AUTH_PW'] !== $valid_password) {
    header('WWW-Authenticate: Basic realm="Judge Area"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Unauthorized: You must provide valid credentials to access this page.';
    exit;
}

// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Connect to DB
require_once('../includes/db.php');

$message = "";

// Helper function to sanitize text inputs
function sanitize_text($text) {
    return htmlspecialchars(trim($text), ENT_QUOTES, 'UTF-8');
}

// When form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judge_id = sanitize_text($_POST['judge_id'] ?? '');
    $user_id = sanitize_text($_POST['user_id'] ?? '');
    $score_raw = $_POST['score'] ?? '';

    if (!is_numeric($score_raw)) {
        $message = "❌ Score must be a numeric value.";
    } else {
        $score = floatval($score_raw);
        if ($judge_id === '' || $user_id === '') {
            $message = "❌ Judge ID and User ID cannot be empty.";
        } elseif ($score < 1 || $score > 100) {
            $message = "❌ Score must be between 1 and 100.";
        } else {
            $check = $mysqli->prepare("SELECT id FROM scores WHERE judge_id = ? AND user_id = ?");
            if (!$check) {
                $message = "❌ Database prepare error: " . $mysqli->error;
            } else {
                $check->bind_param("ss", $judge_id, $user_id);
                $check->execute();
                $result = $check->get_result();

                if ($result->num_rows > 0) {
                    $message = "❌ This judge has already scored this user.";
                } else {
                    $stmt = $mysqli->prepare("INSERT INTO scores (judge_id, user_id, points) VALUES (?, ?, ?)");
                    if (!$stmt) {
                        $message = "❌ Database prepare error: " . $mysqli->error;
                    } else {
                        $stmt->bind_param("ssd", $judge_id, $user_id, $score);
                        if ($stmt->execute()) {
                            $message = "✅ Score submitted successfully.";
                        } else {
                            $message = "❌ Database error: " . $stmt->error;
                        }
                        $stmt->close();
                    }
                }
                $check->close();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Score User</title>
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
            margin-left: -650px;
        }

        .home-button:hover {
            background-color: #333;
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

        .form-group input[type="text"],
        .form-group input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            border-color: #1a73e8;
        }

        .submit-button {
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

        .submit-button:hover {
            background-color: #0049b7;
        }

        .form-card p {
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="form-card">
    <a href="http://localhost/judging-system/" class="home-button">← Back to Home</a>
    <h2>Judge: Score User</h2>

    <?php if (!empty($message)): ?>
        <p><strong><?= $message ?></strong></p>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="judge_id">Judge ID:</label>
            <input type="text" id="judge_id" name="judge_id" required maxlength="50">
        </div>

        <div class="form-group">
            <label for="user_id">User ID:</label>
            <input type="text" id="user_id" name="user_id" required maxlength="50">
        </div>

        <div class="form-group">
            <label for="score">Score (1 - 100):</label>
            <input type="number" id="score" name="score" step="0.01" min="1" max="100" required>
        </div>

        <button type="submit" class="submit-button">Submit Score</button>
    </form>
</div>

</body>
</html>
