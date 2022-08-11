<?php
session_start();
require_once('php/db.php');
$all_messages = mysqli_query($con,"SELECT * FROM `messages` ORDER BY `id` DESC");

if(isset($_GET['id']) && isset($_GET['type']) && isset($_GET['img_file']) && isset($_GET['audio_file'])){
    $id = $_GET['id'];
    $type = $_GET['type'];
    if($type == 'Pic'){
        $img_file = $_GET['img_file'];
        unlink('uploads/img/'.$img_file);
    }elseif($type == 'Audio'){
        $audio_file = $_GET['audio_file'];
        unlink('uploads/voices/'.$audio_file);

    }
    $remove_query = mysqli_query($con,"DELETE FROM `messages` WHERE `id` = '$id'");
    if($remove_query == true){
        header("Location: messages.php?deleted");
    }else{
        header("Location: messages.php?error");
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Radio DJ'S All Messages</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">

</head>
<body>

    <div class="container">
        <div class="col-lg-12">
            <h5 class="text-center my-4">Received Messages ( Admin Panel )</h5>

            <?php if(isset($_GET['deleted'])){ ?> 
                <div class="alert alert-danger">Messages Deleted</div>
            <?php } ?>
            <?php if(isset($_GET['error'])){ ?> 
                <div class="alert alert-warning">Action Error! Try latter</div>
            <?php } ?>

            <div class="table-responsive">
                <table class="table text-center">
                    <tbody>
                    <?php $sl=1; while($all_message = mysqli_fetch_assoc($all_messages)){ ?>

                        <tr>
                            <td style="width: 130px;">
                                <span class="table_primary_sell">AANRAL</span>
                                
                            </td>
                            <td style="width: 150px;">
                                <span class="table_picture_sell">Picture</span>
                                
                            </td>
                            <td style="width: 450px;">
                                <span class="table_msg_sell">Message</span>
                                
                            </td>
                            <td style="width: 250px;">
                                <span class="table_audio_sell">Audio</span> 
                            </td>
                            <td style="width: 130px;">
                                <span class="table_optics_sell">Opties</span>
                                
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 130px;">
                                <span class="table_primary_sell"><?= $sl; ?></span>
                            </td>
                            <td class="table_picture_sell" style="width: 150px;">
                                <?php if($all_message['type'] == 'Pic'){ ?>
                                    <img src="./uploads/img/<?= $all_message['picture']; ?>" width="100px" alt="">
                                <?php } ?>
                            </td>
                            <td style="width: 450px;">
                                <span class="table_msg_sell">
                                    <?= $all_message['message'] ?>
                                    <?php
                                        $names = explode('_',$all_message['user_name']);
                                        $usr_name = end($names);                          
                                    ?>
                                    <br><b><?= $usr_name; ?></b>
                                    <br><small class="admin_time"><?= date("d M, Y h:i A", strtotime($all_message['created_at'])); ?></small>
                                </span>
                            </td>
                            <td style="width: 250px;"> 
                                <span class="table_audio_sell">
                                    <?php if($all_message['type'] == 'Audio'){ ?>
                                        <audio style="width: 250px;" src="uploads/voices/<?= $all_message['audio']; ?>" controls></audio>
                                    <?php } ?>
                                </span> 
                            </td>
                            <td class="rmv_row" style="width: 130px;">
                                <a href="<?= $_SERVER['PHP_SELF'] ?>?id=<?= $all_message['id']; ?>&type=<?= $all_message['type']; ?>&img_file=<?= $all_message['picture']; ?>&audio_file=<?= $all_message['audio']; ?>"><span class="table_remove_sell"><img src="./img/remove.svg" width="10px"> Remove</span></a>
                            </td>
                        </tr>

                    <?php $sl++; } ?>
                    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
</body>
</html>