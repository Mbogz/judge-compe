<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Judge Scoring System</title>
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

        .form-card h1 {
            color: #333;
            margin-bottom: 20px;
        }

        .inner-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            align-items: center;
        }

        .inner-form a {
            display: block;
            background-color:rgb(93, 152, 230);
            color: #fff;
            text-decoration: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            transition: background-color 0.3s ease;
            width: 300px;
        }

        .inner-form a:hover {
            background-color:rgb(140, 234, 173);
        }

        .inner-form a span {
            margin-right: 8px;
        }
    </style>
</head>
<body>

<div class="form-card">
    <h1>Welcome to the Judge Scoring System</h1>

    <form class="inner-form">
        <a href="admin/add_judge.php"><span>➕</span>Add a Judge</a>
        <a href="admin/add_user.php"><span>➕</span>Add a User</a>
        <a href="judge/score_user.php"><span>📝</span>Score a User</a>
        <a href="public/scoreboard.php"><span>📊</span>View Scoreboard</a>
    </form>
</div>

</body>
</html>
