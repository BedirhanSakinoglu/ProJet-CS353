<?php
session_start();
require_once "config.php";


if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === FALSE){
    header("location: login.php");
} else if(!isset($_SESSION['loggedin'])){
    header("location: login.php");
}

$id = $_SESSION['user_id'];

if (isset($_POST['positive'])){
    $package_id= $_POST['positive'];
    /*
        1) package status "on branch" olacak +++
        2) submit_pack relationundaki branch_ID yeni branch_ID ile değişecek
        3) assign_to_employee yeni employee_ID ile değişecek
    */
    $query_transfer_package_status = "UPDATE package SET status = 'on branch' WHERE package_ID = '$package_id' ";
    mysqli_query($mysqli, $query_transfer_package_status);

    $query_branch_id = "SELECT branch_ID FROM works WHERE employee_ID = '$id'";
    $result_branch_id  = mysqli_query($mysqli, $query_branch_id);
    $row = $result_branch_id->fetch_assoc();
    $branch_ID = $row['branch_ID'];

    $query_submit_pack = "UPDATE submit_pack SET branch_ID = '$branch_ID' WHERE package_ID = '$package_id'";
    mysqli_query($mysqli, $query_submit_pack);

    $query_assign_to_employee = "UPDATE assign_to_employee SET employee_ID = '$id' WHERE package_ID = '$package_id'";
    mysqli_query($mysqli, $query_assign_to_employee);

    echo "<script>
            if(confirm('Package Accepted' )){document.location.href='employeeDashboard.php'};
            </script>";
}
if (isset($_POST['negative'])){
    $pid= $_POST['negative'];
    $query_transfer_package_status = "UPDATE package SET status = 'on branch' WHERE package_ID = '$pid' ";
    mysqli_query($mysqli, $query_transfer_package_status);
    echo "<script>
            if(confirm('Package Rejected' )){document.location.href='employeeDashboard.php'};
            </script>";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>View Packages</title>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
    <meta name="generator" content="Web Page Maker (unregistered version)">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <style>
        /* Fonts Form Google Font ::- https://fonts.google.com/  -:: */
        @import url('https://fonts.googleapis.com/css?family=Abel|Abril+Fatface|Alegreya|Arima+Madurai|Dancing+Script|Dosis|Merriweather|Oleo+Script|Overlock|PT+Serif|Pacifico|Playball|Playfair+Display|Share|Unica+One|Vibur');
        @import url('https://fonts.googleapis.com/css?family=Google+Sans:100,300,400,500,700,900,100i,300i,400i,500i,700i,900i');
        /* End Fonts */
        /* Start Global rules */
        /* End Global rules */
        * {
            padding: 0;
            margin: 0;

        }
        h1 {
            font-size: 75px;
            text-align: left;
            background-size: 100% auto !important;
            font-family: 'Google Sans';
            color: #1c4894;
            padding-bottom: 3vh;
        }
        /* Start body rules */
        body {
            background-image: linear-gradient(-225deg, #ffffff 0%, #EFF9FF 100%);
            background-image: linear-gradient(to top, #ffffff 0%,#EFF9FF 100%);
            background-attachment: fixed;
            background-repeat: no-repeat;

            font-family: 'Dubai Light';

            /* background-image: linear-gradient(to top, #d9afd9 0%, #97d9e1 100%); */
        }

        .confirm-button-n {
            color: #ffffff;

            background: #D11B1B;
            width: 80%;
            height: 80%;
            outline: none;
            border: none;
            cursor: pointer;
            text-align: center;
            transition: all 0.2s linear;
            letter-spacing: 0.05em;
        }
        .confirm-button-p {
            color: #ffffff;

            background: #1EA204;
            width: 80%;
            height: 80%;
            outline: none;
            border: none;
            cursor: pointer;
            text-align: center;
            transition: all 0.2s linear;
            letter-spacing: 0.05em;
        }

        #packages {
            border-collapse: collapse;
            width: 100%;
        }

        #packages td, #customers th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #packages tr:nth-child(even){background-color: #f2f2f2;}

        #packages tr:hover {background-color: #ddd;}

        #packages th {
            padding-top: 12px;
            padding-bottom: 12px;
            padding-left: 8px;
            text-align: left;
            background-color: #3aafff;
            color: white;
        }

        .banner-container{
            display: grid;
            background-color: #3aafff;
            height: 8vh;
            grid-template-columns: 1fr 1fr 1fr;
            column-gap: 5vh;
        }

        .banner-item{

        }

        .left{
            text-align: left;
        }

        .left:hover{
            cursor: pointer;
        }

        .middle{
            text-align: center;
        }

        .right{
            text-align: right;
            margin-right: 3vh;
        }

        .grid-container{
            row-gap: 5vh;
            margin-right: 3vh;
            margin-left: 3vh;
            margin-top: 7vh;
        }

        .grid-item {
            background-color: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(0, 0, 0, 0.8);
            padding: 20px;
            font-size: 30px;
            text-align: center;
            box-shadow: 0 5px 20px hsla(205, 75%, 36%, 0.31);
        }

        p {
            font-family: 'Google Sans';
            margin-bottom: 5vh;
            font-size: 2vh;
        }

        h2 {
            font-family: 'Google Sans';
            font-size: 5vh;
            color: white;
            margin-left: 3vh;
            padding-top: 1vh;
        }

        h3{
            font-family: 'Google Sans';
            font-size: 3vh;
            color: rgb(23, 103, 161);
        }
        /* End body rules */

        /* buttons  */
        .banner-button {
            display: inline-block;
            color: #fff;

            width: 30vh;
            height: 5vh;

            background: #377095;
            border-radius: 5px;

            outline: none;
            border: none;
            margin-top: 1vh;
            cursor: pointer;
            text-align: center;
            transition: all 0.2s linear;


            letter-spacing: 0.05em;
        }

        .send-button{
            display: inline-block;
            color: #fff;

            width: 25vh;
            height: 15vh;

            background: #4e8bb4;
            border-radius: 5px;

            outline: none;
            border: none;

            cursor: pointer;
            text-align: center;
            font-size: 2vh;
            transition: all 0.2s linear;
            letter-spacing: 0.05em;
        }

        /* buttons hover */
        button:hover {
            background: #29436c;
            box-shadow: none;
        }
    </style>

</head>
<body>
<div class="banner-container">
    <div class="banner-item left" onclick="location.href='employeeDashboard.php';"><h2>ProJet</h2></div>
    <div class="banner-item middle"><button class="banner-button" onclick="location.href='employeeDashboard.php';">Home</button> <button class="banner-button" onclick="location.href='employeeProfile.php';">My Profile</button></div>
    <div class="banner-item right"><button class="banner-button" onclick="location.href='logout.php';">Logout</button></div>
</div>
<div class="grid-container">
    <h1>New Packages</h1>
    <h3>All of the packages assigned to you are listed below:</h3>
    <table id="packages">
        <tr>
            <th>Package ID</th>
            <th>Sender Branch</th>
            <th>Delivery Date</th>
            <th >Delivery Address</th>
            <th style="background-color: white;"></th>
        </tr>
        <form method="post">
            <?php
            $query = "SELECT DISTINCT t.package_ID, t.branch_ID, p.delivery_time, p.delivery_address
                        FROM transfer_pack t, package p, works w
                        WHERE t.package_ID = p.package_ID AND t.branch_ID = w.branch_ID AND w.employee_ID = '$id' AND p.status = 'on transfer'";
            $packages = $mysqli->query($query) or die('Error in query: ' . $mysqli->error);
            if($packages->num_rows > 0)
            {
                while($row = $packages->fetch_assoc()){
                    $sender_branch_query = "SELECT b.address
                        FROM transfer_pack t, package p, works w, branch b
                        WHERE t.package_ID = p.package_ID AND w.employee_ID = t.employee_ID AND p.status = 'on transfer' AND b.branch_ID = w.branch_ID";
                    $pid = $row['package_ID'];
                    $delivery_time = $row['delivery_time'];
                    $delivery_address = $row['delivery_address'];

                    $result = $mysqli->query($sender_branch_query) or die('Error in query: ' . $mysqli->error);
                    $row = $result->fetch_assoc();
                    $sender_address = $row['address'];

                    echo sprintf("<tr> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> 
                                 <td style='text-align: center; border-width: 0px; padding: 0px'><button class='confirm-button-p' type='submit' name='positive' value='$pid'>Accept</button></td><td style='text-align: center; border-width: 0px; padding: 0px'><button class='confirm-button-n' type='submit' name='negative' value='$pid'>Reject</button></td></tr>", $pid, $sender_address, $delivery_time, $delivery_address);

                }
            }
            ?>
        </form>
    </table>
</div>
</body>
</html>

