<?php
    session_start();
    include("../connections/conn.php");

    $uemail = $_POST["useremail"];
    $pass = $_POST["passfield"];

    //Prepare and bind
    $checkstmt = $conn->prepare("SELECT * FROM users WHERE userEmail= ?");
        if ( false===$checkstmt ) {
            die('prepare() failed: ' . htmlspecialchars($conn->error));
            }
    $rc = $checkstmt->bind_param("s", $emailString);
    if ( false===$rc ) {
        die('bind_param() failed: ' . htmlspecialchars($checkstmt->error));
        }

    //Assign and execute
    $emailString = $uemail;
    $rc = $checkstmt->execute();
    if ( false===$rc ) {
        die('execute() failed: ' . htmlspecialchars($checkstmt->error));
    }
    $result = $checkstmt->get_result();
    $userdetails = $result->fetch_assoc(); 

    $num = $result->num_rows;

    if($num > 0){
        header("Location: ../signup.php?exists");
    }else{
        
        //Prepare and bind insert
        $insertstmt = $conn->prepare("INSERT INTO `users` (`UserID`, `userEmail`, `password`, `UserType`) VALUES (NULL, ?, MD5(?), '2')");
        if ( false===$insertstmt ) {
            die('prepare() failed: ' . htmlspecialchars($conn->error));
            }
        $rc = $insertstmt->bind_param("ss", $emailString, $passString);
        if ( false===$rc ) {
            die('bind_param() failed: ' . htmlspecialchars($insertstmt->error));
            }
        
        //Assign and execute
        $emailString = $uemail;
        $passString = $pass;
        $rc = $insertstmt->execute();
        if ( false===$rc ) {
            die('execute() failed: ' . htmlspecialchars($insertstmt->error));
        }

        header("Location: login.php?newacc");

    }
?>