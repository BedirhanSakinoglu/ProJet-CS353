<?php
session_start();
require_once "config.php";


if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === FALSE){
    header("location: login.php");
} else if(!isset($_SESSION['loggedin'])){
    header("location: login.php");
}

$id = $_SESSION['user_id'];

if (isset($_POST['confirm'])){
    $confirm_package_id = $_POST['confirm'];
    $confirm_query = ("UPDATE package SET status='delivered' WHERE package_ID = '$confirm_package_id' ");
    mysqli_query($mysqli, $confirm_query);
    //header("location: customerPackagesPage.php");
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Customer Dashboard</title>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
    <meta name="generator" content="Web Page Maker (unregistered version)">
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
            text-align: center;
            background-size: 100% auto !important;
            font-family: 'Google Sans';
            color: #3e403f;
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

        .middle{
            text-align: center;
        }

        .right{
            text-align: right;
            margin-right: 3vh;
        }

        .grid-container{
            display: grid;
            grid-template-columns: 1fr;
            grid-template-rows: 8vh 4vh 4vh 4vh 4vh;
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
    <div class="banner-item left"><h2>ProJet</h2></div>
    <div class="banner-item middle"><button class="banner-button">Home</button> <button class="banner-button">My Profile</button></div>
    <div class="banner-item right"><button class="banner-button">Logout</button></div>
</div>
<div class="grid-container">
    <h1>My Packages</h1>
    <table id="packages">
        <tr>
            <th>Package ID</th>
            <th>Date Sent</th>
            <th>Delivery Date</th>
            <th>Sender</th>
            <th>Receiver</th>
            <th>Status</th>
            <th>Delivery Address</th>
            <th>Confirm Package</th>
        </tr>
        <form method="post">
            <?php
            $query1 = "SELECT p.package_ID, p.send_time, p.delivery_time, st.sender_ID, st.taker_ID, p.status, p.delivery_address
                        FROM package p, send_to st
                        WHERE st.sender_ID = '$id' AND p.package_ID = st.package_ID";
            $query2 = "SELECT p.package_ID, p.send_time, p.delivery_time, st.sender_ID, st.taker_ID, p.status, p.delivery_address
                        FROM package p, send_to st
                        WHERE st.taker_ID = '$id' AND p.package_ID = st.package_ID";
            $packages1 = $mysqli->query($query1) or die('Error in query: ' . $mysqli->error);
            $packages2 = $mysqli->query($query2) or die('Error in query: ' . $mysqli->error);
            if($packages1->num_rows > 0)
            {
                while($row = $packages1->fetch_assoc()){
                    $sender_id = $row['sender_ID'];
                    $query_sender_name = "SELECT u.username FROM user u WHERE u.user_ID = '$sender_id'";
                    $result_sender_name = $mysqli->query($query_sender_name) or die('Error in query: ' . $mysqli->error);
                    $sender_name = $result_sender_name->fetch_assoc();

                    $taker_id = $row['taker_ID'];
                    $query_taker_name = "SELECT u.username FROM user u WHERE u.user_ID = '$taker_id'";
                    $result_taker_name = $mysqli->query($query_taker_name) or die('Error in query: ' . $mysqli->error);
                    $taker_name = $result_taker_name->fetch_assoc();

                    echo sprintf("<tr> <td>%s</td> <td>%s</td> <td>%s</td>  <td>%s</td> <td>%s</td> <td>%s</td>  <td>%s</td>
                                </tr>", $row['package_ID'], $row['send_time'], $row['delivery_time'], $sender_name['username'], $taker_name['username'], $row['status'], $row['delivery_address']);
                }
            }

            if($packages2->num_rows > 0)
            {
                while($row = $packages2->fetch_assoc()){
                    $pid = $row['package_ID'];
                    $sender_id = $row['sender_ID'];
                    $query_sender_name = "SELECT u.username FROM user u WHERE u.user_ID = '$sender_id'";
                    $result_sender_name = $mysqli->query($query_sender_name) or die('Error in query: ' . $mysqli->error);
                    $sender_name = $result_sender_name->fetch_assoc();

                    $taker_id = $row['taker_ID'];
                    $query_taker_name = "SELECT u.username FROM user u WHERE u.user_ID = '$taker_id'";
                    $result_taker_name = $mysqli->query($query_taker_name) or die('Error in query: ' . $mysqli->error);
                    $taker_name = $result_taker_name->fetch_assoc();

                    if($row['status'] != 'delivered') {
                        echo sprintf("<tr> <td>%s</td> <td>%s</td> <td>%s</td>  <td>%s</td> <td>%s</td> <td>%s</td>  <td>%s</td>
                                 <td><button type='submit' name='confirm' value='$pid'>Confirm</button></td></tr>", $row['package_ID'], $row['send_time'], $row['delivery_time'], $sender_name['username'], $taker_name['username'], $row['status'], $row['delivery_address']);
                    }
                    else{
                        echo sprintf("<tr> <td>%s</td> <td>%s</td> <td>%s</td>  <td>%s</td> <td>%s</td> <td>%s</td>  <td>%s</td>
                                 </tr>", $row['package_ID'], $row['send_time'], $row['delivery_time'], $sender_name['username'], $taker_name['username'], $row['status'], $row['delivery_address']);
                    }
                }
            }
            ?>
        </form>
    </table>
</div>

</body>
</html>