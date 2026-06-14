<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "todo_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection Failed");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $task_name = trim($_POST['task_name']);

    if (!empty($task_name)) {

        $stmt = $conn->prepare("INSERT INTO tasks (task_name) VALUES (?)");
        $stmt->bind_param("s", $task_name);
        $stmt->execute();
        $stmt->close();

        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Task</title>

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial;
        }

        body{
            background:#f4f4f4;
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
        }

        .container{
            background:white;
            padding:30px;
            border-radius:10px;
            width:400px;
            box-shadow:0 0 10px rgba(0,0,0,.2);
        }

        h2{
            text-align:center;
            margin-bottom:20px;
        }

        input{
            width:100%;
            padding:10px;
            margin-bottom:15px;
            border:1px solid #ccc;
            border-radius:5px;
        }

        button{
            width:100%;
            padding:10px;
            border:none;
            background:#28a745;
            color:white;
            border-radius:5px;
            cursor:pointer;
        }

        button:hover{
            background:#218838;
        }

        table{
            width:100%;
            margin-top:20px;
            border-collapse:collapse;
        }

        table th,
        table td{
            border:1px solid #ddd;
            padding:8px;
            text-align:center;
        }

        table th{
            background:#28a745;
            color:white;
        }
    </style>
</head>
<body>

<div class="container">

    <h2>Add Task</h2>

    <form method="POST">
        <input type="text" name="task_name" placeholder="Enter Task Name" required>
        <button type="submit">Add Task</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Task Name</th>
        </tr>

        <?php
        $result = $conn->query("SELECT * FROM tasks ORDER BY id DESC");

        while($row = $result->fetch_assoc()){
            echo "
            <tr>
                <td>{$row['id']}</td>
                <td>{$row['task_name']}</td>
            </tr>";
        }
        ?>
    </table>

</div>

</body>
</html>