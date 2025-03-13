<?php
$username = "root";
$password = "";
$db_name = "user";
$host = "localhost";

$con = mysqli_connect($host, $username, $password, $db_name);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$result = mysqli_query($con, "SELECT * FROM user");


if (isset($_POST['btnadd'])) {
    $name = $_POST['name'];
    $sql = "INSERT INTO user (name) VALUES ('$name')";
    mysqli_query($con, $sql);
    header("location: index.php");
}

if (isset($_POST['btnupdate'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $sql = "UPDATE user SET name='$name' WHERE id=$id";
    mysqli_query($con, $sql);
    header("location: index.php");
}

if (isset($_POST['btndelete'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM user WHERE id=$id";
    mysqli_query($con, $sql);
    header("location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 300px;
        }
        input {
            width: 100%;
            padding: 8px;
            margin: 5px 0 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px;
            margin-right: 5px;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background: #007bff;
            color: white;
        }
    </style>
</head>
<body>

    <form method="POST">
        <label for="id">ID:</label>
        <input type="number" id="id" name="id" >

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" >

        <button type="submit" name="btnadd">Add</button>
        <button type="submit" name="btnupdate">Update</button>
        <button type="submit" name="btndelete">Delete</button>
    </form> 

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
        </tr>
        <?php 
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "</tr>";
        }
        ?>
    </table> 

</body>
</html>
