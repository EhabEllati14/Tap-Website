<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Final Project</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
        }
        h1 {
            color: #1e3c72;
            text-align: center;
            margin-bottom: 20px;
        }
        label, span {
            font-size: 16px;
        }
        table {
            width: 50%;
            margin: 20px auto;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #1e3c72;
            color: #fff;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        a {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            text-align: center;
            background-color: #1e3c72;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        a:hover {
            background-color: #155684;
        }
    </style>
</head>
<body>
    <h1>Welcome To My Final Project</h1>
    <label>Name: </label>
    <span><b>Ehab Ellati</b></span><br>
    <label>Student ID: </label>
    <span><b>1211567</b></span>

    <h1>Team Members</h1>
    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Password</th>
                 <th>Type</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>fadi1122</td>
                <td>fadi12345</td>
                <td>Manager</td>
            </tr>
            <tr>
                <td>ehab123</td>
                <td>ehab12345</td>
                <td>Project Leader</td>
            </tr>
            <tr>
                <td>ghazal123</td>
                <td>ghazal12345</td>
                <td>Team Member</td>
            </tr>
              <tr>
                <td>jody123</td>
                <td>jody12345</td>
                <td>Team Member</td>
            </tr>
        </tbody>
    </table>

    <h1>Main Page Of My Project</h1>
    <a href='dashboard.php'>Click Here</a>
</body>
</html>
