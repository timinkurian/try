<?php
require "connect.php";
require "session.php";
//session_start();


$type = $_POST['type'];
// print_r($_POST);

// __halt_compiler();
switch($type){   
        case 'login':
            // echo $type;
            userLogin($conn);
            break;

            case 'reguser':
            regUser($conn);
            break;
        case 'userreg':
            userProfile($conn);
            break;
        case 'district':
            districtAdd($conn);
            break;
        case 'centerreg':
            centerRegistration($conn);
         break;
        default:
            break;
    }   

function regUser($conn){
    $email=$_POST['email'];
    $desg=$_POST['designation'];
    $pswd=md5($_POST['pswd']);
    $cpswd=md5($_POST['cpswd']);
    

        
    $sql = "SELECT * FROM `login` WHERE username='$email'";
    $res = mysqli_query($conn, $sql);

    if(mysqli_num_rows($res)>0){
    //print_r($count);
    //__halt_compiler();

    echo"<script> alert('Already exist');window.location ='../reguser.php';</script>";
    }
   
    else
    {
        $sql1="INSERT INTO login (username,password,usertype,status) VALUES ('$email','$pswd','$desg','2')";
        $res1 = mysqli_query($conn, $sql1);
       echo"<script> alert('Registration Successful');window.location ='../index.php';</script>";
    } 

            // print_r($sql1);
       

    

    //print_r($res);
   /* $s=mysqli_insert_id($conn);
    /header("location:../registration.php?id=$s");
    if($res1){
        $logid = mysqli_insert_id($conn);
        echo $logid;

        setSession('r_1', true);
        setSession('log_id', $logid);

        print_r(getSession('r_1'));
         
    }
*/
}

//user fns
function userLogin($conn){
    $uname = $_POST['username'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM `login` WHERE username='$uname' and password = '$password' and status != 0";

    $res = mysqli_query($conn, $sql);

    if($res){
        $result = mysqli_fetch_assoc($res);

        // print_r($result);
        $a=$result['usertype'];
    // print_r($a);
        if($a == 'admin')
        { 
            $id = $result['logid'];
            // print_r($id);
            $utype = $result['usertype'];
            setSession('logid', $id);
            setSession('utype', $id);
            // print_r($id);
            echo "<script>alert('Login Successfull');window.location='../adminhome.php';</script>";
    
        // header('location:user.php');

          //  while($result = $res->fetch_assoc()) {
            //echo('tes');
        
          //  }
        }
        else if($a=="user")
        { 
            $id = $result['logid'];
            $utype = $result['usertype'];
            $status=$result['status'];
            setSession('logid', $id);
            setSession('utype', $id);
            if($status=="2"){
                header('Location:../registration.php');
            }
            else{

            echo "<script>alert('Login Successfull');window.location='../user.php';</script>";

            }
        }

        else if($a=="servicecenter")
        { 
            $id = $result['logid'];
            $utype = $result['usertype'];
            $status=$result['status'];
            setSession('logid', $id);
            setSession('utype', $id);
            if($status=="2"){
                header('Location:../servicecenteradd.php');
            }
            else{

            echo "<script>alert('Login Successfull');window.location='../servicecenter.php';</script>";

            }
        }

        else
        {
            // echo $sql;
            echo "<script>alert('Invalid username or password');window.location='../index.php';</1script>";
                header("location:../index.php"); 
        }
    }
    /*else{
        echo "<script>alert('Something Went Wrong! Try again.');window.location='../index.php';</1script>";
             header("location:../index.php"); 
    }*/
}


function userProfile($conn){
    $fname=$_POST['fname'];
   // $mname=$_POST['mname'];
    $lname=$_POST['lname'];
    $mob=$_POST['mobno'];
    $dist=$_POST['district'];
    $place=$_POST['place'];
    $path=$_FILES['photo']['name'];
    $path = '../upload/'.$path;
    $img=$_FILES['photo']['name'];
    //print_r($img);
    $val=getSession('logid');

    $z="select * from login where logid='$val'";
    $r1=mysqli_query($conn,$z);
    $row=mysqli_fetch_array($r1);
    $email=$row[1];
    //print_r($email);

    $sql= "INSERT INTO `user` (`logid`,`fname`,`lname`,`email`,`mobile`,`district`,`place`,`photo`) VALUES ('$val','$fname','$lname','$email','$mob','$dist','$place','$path')";
    $r2=mysqli_query($conn,$sql);
    move_uploaded_file($_FILES['photo']['tmp_name'],'upload/' . $_FILES['photo']['name']);
    $sql2="UPDATE `login` SET `status`=1 where `logid`=$val";
    mysqli_query($conn,$sql2);
    echo "<script>alert('Profile updated successfully');window.location=../user.php;</script>";
    /* if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }*/
        
}

function districtAdd($conn){//adding district
    $dname=$_POST['dname'];
   
    $sql="INSERT INTO district (district) VALUES ('$dname')";
    mysqli_query($conn,$sql);
    echo "<script>alert('registered successfully');window.location='../districtadd.php';</script>";   
   
}

function centerRegistration($conn){
    $cname=$_POST['cname'];
    $licno=$_POST['licno'];
    $type=$_POST['types'];
    $brand=$_POST['brand'];
    $mob=$_POST['mobno'];
    $dist=$_POST['district'];
    $place=$_POST['place'];
   /* $cert=$_FILES['certificates']['name'];
    $cert = '../certificate/'.$cert;
    $img=$_FILES['certificates']['name'];
*/

    $cert=$_FILES['certificate']['name'];
    $cert = '../upload/'.$cert;
    $img=$_FILES['certificate']['name'];



    $val=getSession('logid');

    $z="select * from login where logid='$val'";
    $r1=mysqli_query($conn,$z);
    $row=mysqli_fetch_array($r1);
    $email=$row[1];
    //print_r($email);

    $sql= "INSERT INTO `servicecenter`(`logid`, `centername`, `licenceno`, `type`, `brand`, `district`, `place`, `certificate`, `mobile`) VALUES ('$val','$cname','$licno','$type','$brand','$dist','$place','$cert','$mob')";
    print_r($sql);
    $r2=mysqli_query($conn,$sql);
    //move_uploaded_file($_FILES['certificate']['tmp_name'],'upload/' . $_FILES['certifiacte']['name']);

    move_uploaded_file($_FILES['certificate']['tmp_name'],'upload/' . $_FILES['certificate']['name']);

    $sql2="UPDATE `login` SET `status`=0 where `logid`=$val";
    mysqli_query($conn,$sql2);
    echo "<script>alert('Updated successfully...!! Wait for Approvel');window.location='../index.php';</script>";
    /* if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }*/
         
}

