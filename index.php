<?php
$con=  mysqli_connect("localhost", "root", "","students") or die("not connected");

if(isset($_POST['add'])){
    $name=$_POST['name'];
    $sql="insert into student (name) values('$name');";
    mysqli_query($con, $sql);}

if(isset($_POST['update'])){
    $id=$_POST['id'];
    $name=$_POST['name'];
    $sql="update student set name= '$name' where id='$id';";
    mysqli_query($con, $sql);}

if(isset($_POST['delete'])){
    $id=$_POST['id'];
    $sql="delete from student where id='$id';";
    mysqli_query($con, $sql);}


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internet Application</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-color: #f4f4f9;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #555;
        }
        form {
            margin-bottom: 20px;
        }
        form label {
            display: block;
            margin: 10px 0 5px;
        }
        form input, form select, form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        form button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
        }
        form button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Users</h1>
        
        <form id="exampleForm" method="post">
            <label for="id">ID:</label>
            <input type="number" id="id" name="id" placeholder="Enter Id">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" placeholder="Enter Name">
            <button  name="add">Add</button>
            <button  name="update" >Update</button>
            <button  name="delete" >Delete</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                </tr>
            </thead>
            <tbody>
<?php $sql1="select * from student";
$result=mysqli_query($con, $sql1);
while($row= mysqli_fetch_assoc($result)){ ?>
                <tr>
                  <td><?php echo $row["id"] ?></td>
                  <td><?php echo $row["name"] ?></td>			
             </tr>
<?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
