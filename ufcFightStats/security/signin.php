<?php
    session_start();
    include("../connections/conn.php");

    $uemail = $_POST["useremail"];
    $pass = $_POST["passfield"];

    //Prepare and bind
    $checkstmt = $conn->prepare("SELECT * FROM users WHERE userEmail= ? AND password=MD5(?)");
        if ( false===$checkstmt ) {
            die('prepare() failed: ' . htmlspecialchars($conn->error));
            }
    $rc = $checkstmt->bind_param("ss", $emailString, $passString);
    if ( false===$rc ) {
        die('bind_param() failed: ' . htmlspecialchars($checkstmt->error));
        }

    //Assign and execute
    $emailString = $uemail;
    $passString = $pass;
    $rc = $checkstmt->execute();
    if ( false===$rc ) {
        die('execute() failed: ' . htmlspecialchars($checkstmt->error));
    }
    $result = $checkstmt->get_result();
    $userdetails = $result->fetch_assoc(); 

    $num = $result->num_rows;

    if($num > 0){
        if($userdetails['UserType'] == 1){
            $_SESSION['fightsAdmin'] = $userdetails['UserID'];
            header("Location: ../admin.php");
        } elseif($userdetails['UserType'] == 2){
            $_SESSION['fightsUser'] = $userdetails['UserID'];
            header("Location: ../index.php");
        }
        
    }else{
        header("Location: login.php?invalid");
    }
?>