<?php
  error_reporting(E_ALL);
  ini_set('display_errors', '1');

  $msg = "Connected";
  $color = "grey";

  if (array_key_exists('i', $_GET)) {
    if ($_GET["i"] == "add") {
      $msg = "User added";
      $color = "green";
    } elseif ($_GET["i"] == "rem") {
      $msg = "User removed";
      $color = "red";
    }
  }

  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "test";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  } else {
    echo '<div id="connectionStatus" class="'.$color.'">'.$msg.'</div>';
  }

  if (!empty($_GET)) {
    if (array_key_exists('id', $_GET)) {
      $id = $_GET["id"];
      $sql = "DELETE FROM test WHERE id = '".$id."'";

      if ($conn->query($sql) === TRUE) {
          //echo '<div id="connectionStatus">User created</div>';
      } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
      }
      header('location:/?i=rem');
    } elseif (array_key_exists('firstname', $_GET) && array_key_exists('lastname', $_GET)) {
      $firstname = $_GET["firstname"];
      $lastname = $_GET["lastname"];

      $sql = "INSERT INTO test (firstname, lastname) VALUES ('".$firstname."','".$lastname."')";

      if ($conn->query($sql) === TRUE) {
          //echo '<div id="connectionStatus" class="red">User deleted</div>';
      } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
      }
      header('location:/?i=add');
    }
  }

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Data Test Page</title>
    <script   src="https://code.jquery.com/jquery-3.1.0.min.js" integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s="   crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.min.js"></script>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <style>
      body {
        background:#61afef;
      }
      #header {
          background:#fff url('http://images1.andersonautogroup.com/header-background.jpg') no-repeat;
          color:#fff;
          border-radius:5px;
          padding:30px 0 30px 20px;
          margin:20px auto;
      }
      #main {
        padding:20px;
        border:1px solid #ddd;
        border-radius:5px;
        overflow:hidden;
      }
      #connectionStatus {
        position:absolute;
        padding:3px 10px;
        color:#fff;
        right:0;
        top:0;
      }
      .left {
        border-right:1px solid #ddd;
      }
      #userContainer {
        border:1px solid #ddd;
        border-bottom:0px;
        display:inline-block;
      }
      #userList {
        display:inline-block;
        overflow:auto;
        padding:0;
        margin:0;
        border-bottom:1px solid #ddd;
      }
      #userList li {
        display:inline-block;
        border-right:1px solid #ddd;
        margin:0;
        padding:5px 10px;
        width:100px;
      }
      #userList li:first-child,
      #userList li:last-child {
        width:40px;
      }
      #userList li:last-child {
        border-right:0px;
      }
      .glyphicon-remove-circle {
        color:#e05848;
      }
      .red { background:#e05848; }
      .green { background:#98be63; }
      .grey { background:#ddd; }

      input.error,
      input.error:focus {
        border:1px solid red;
      }
      h1,h3 {
        margin:0;
      }
      h3 {
        margin-left:47px;
        font-weight:normal;
      }
      .container {
        box-shadow:1px 1px 10px rgba(0,0,0,.1);
        background:#fff;
      }
    </style>

    <script>
      $(document).ready(function() {
        $('#form1').validate({
          // parameters
        });
      })
    </script>

  </head>
  <body>

    <form name="form1" id="form1" method="GET" action="">
      <div id="header" class="container">
        <h1><span class="glyphicon glyphicon-equalizer"></span> Form Insert/Delete Test</h1>
        <h3>PHP, jQuery, CSS</h3>
      </div>
      <div id="main" class="container">
        <div class="row">
          <div class="col-md-4 left">
            <h2>Add Users</h2>
            <div class="form-group">
              <label for="firstname">First Name:</label>
              <input type="text" id="firstname" name="firstname" class="form-control required">
            </div>
            <div class="form-group">
              <label for="lastname">Last Name:</label>
              <input type="text" id="lastname" name="lastname" class="form-control required">
            </div>
            <button type="submit" class="btn btn-default">Add User</button>
          </div>
          <div class="col-md-8 right">
              <h2>User List</h2>
              <?
                $sql = "SELECT id, firstname, lastname FROM test";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                  echo '<div id="userContainer">';
                  while($row = $result->fetch_assoc()) {
                      echo '<ul id="userList">';
                      echo '<li>'.$row["id"].'</li>';
                      echo '<li>'.$row["firstname"].'</li>';
                      echo '<li>'.$row["lastname"].'</li>';
                      echo '<li><a href="/?id='.$row["id"].'"><span class="glyphicon glyphicon-remove-circle"></span></a></li>';
                      echo '</ul><br />';
                  }
                  echo '</div>';
                } else {
                    echo "0 results";
                }
              ?>
          </div>
        </div>
      </div>
    </form>
  </body>
</html>

<?
  $conn->close();
?>
