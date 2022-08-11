<?php 
require_once('php/db.php');

$user_name = $_COOKIE['user_name'];
$message = $_POST['message'];
$picture = $_FILES['picture']['name'];
$audio = $_FILES['audio']['name'];
$type = 'Msg';


$created_at = date("Y-m-d h:i:s",time());

if($picture != null){
    $pic_ex = $file_ex = strtolower(pathinfo($picture,PATHINFO_EXTENSION));
    $file_name = time().'.'.$pic_ex;
    $location = 'uploads/img/';
    move_uploaded_file($_FILES['picture']['tmp_name'],$location.$file_name);
    $type = 'Pic';
}else{
    $file_name = '';
}
if($audio != null){
    $pic_audio_ex = $file_audio_ex = strtolower(pathinfo($audio,PATHINFO_EXTENSION));
    $file_audio_name = time().'.'.$pic_audio_ex;
    $location_audio = 'uploads/voices/';
    move_uploaded_file($_FILES['audio']['tmp_name'],$location_audio.$file_audio_name);
    $type = 'Audio';
}else{
    $file_audio_name = '';
}

$time = date("h:i A", strtotime($created_at));

$msg_insert_query = mysqli_query($con,"INSERT INTO `messages`(`type`,`user_name`, `message`, `picture`,`audio`,`created_at`) VALUES ('$type','$user_name','$message','$file_name','$file_audio_name','$created_at')");
if($msg_insert_query == true){
    echo $type.'_'.$message.'_'.$file_name.'_'.$file_audio_name.'_'.$time;
}else{
    echo "Server error for send message";
}


?>