<?php 
session_start();
require_once('php/db.php');
if(isset($_COOKIE['user_name'])){
    $user_name = $_COOKIE['user_name'];
    
    $get_messages_date = mysqli_query($con,"SELECT * FROM `messages` WHERE `user_name`= '$user_name' GROUP BY date(`created_at`)");
    function get_messages($date,$con){
        $user_name = $_COOKIE['user_name'];
        return mysqli_query($con,"SELECT * FROM `messages` WHERE `user_name` = '$user_name' AND DATE(`created_at`) = '$date'");
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message Radio DJ'S</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">

</head>
<body>

    <!-- <div class="">
        <div class="">
        </div>
    </div> -->
    
    <div class="chat__body mx-auto">

        <?php if(!isset($_COOKIE['user_name'])){ ?>

            <div class="user__name text-center">
                <h5 class="mb-3">Message Radio DJ'S </h5>
                <div class="mb-3">
                    <input type="text" placeholder="Your Name" class="form-control user_name_input" id="user_name_input" required>
                </div>
                <div class="mb-3">
                    <button class="btn btn-success with_name_btn" type="button" disabled> Continue </button>
                </div>
            </div>
        <?php } ?>


        <div class="chat-box-container">

            <div class="caht-box-header">
                <p>Send Message</p>
            </div>

            <div class="chat_content">

                <?php
                    if(isset($_COOKIE['user_name'])){ 
                        if(mysqli_num_rows($get_messages_date) > 0){ ?>
                            
                            <?php while($get_message_date = mysqli_fetch_assoc($get_messages_date)){ ?>
                                <p class="mb-2 text-center"><?= date("M d, Y", strtotime($get_message_date['created_at'])); ?></p>
                                
                                <?php $msg_date = date("Y-m-d", strtotime($get_message_date['created_at'])); $get_messages = get_messages($msg_date,$con);
                                    while($get_message = mysqli_fetch_assoc($get_messages)){

                                        if($get_message['type'] == 'Msg'){ ?>

                                            <div class="chat_item">
                                                <span><?= $get_message['message'] ?></span>
                                                <span class="time"><?= date("h:i A", strtotime($get_message['created_at'])) ?></span>
                                            </div>
                                        
                                        <?php }elseif($get_message['type'] == 'Pic'){ ?>
                                        <div class="chat_item has_img">
                                            <?php if($get_message['message'] != null){ ?>
                                                <span><?= $get_message['message'] ?></span>
                                            <?php } ?>
                                            <img <?= ($get_message['message'] != null) ? 'style="margin:10px 0px"' : 'style="margin-bottom:10px"'; ?> src="uploads/img/<?= $get_message['picture']; ?>" alt="" width="130px">
                                            <span class="time"><?= date("h:i A", strtotime($get_message['created_at'])) ?></span>
                                        </div>
                                        <?php }else{ ?> 
                                        <div class="chat_item has_record">
                                            <?php if($get_message['message'] != null){ ?>
                                                <span><?= $get_message['message'] ?></span>
                                            <?php } ?>
                                            <audio <?= ($get_message['message'] != null) ? 'style="margin:10px 0px"' : 'style="margin-bottom:10px"'; ?> src="uploads/voices/<?= $get_message['audio'] ?>" controls></audio>
                                            <span class="time" style="margin-top:10px"><?= date("h:i A", strtotime($get_message['created_at'])) ?></span>
                                        </div>

                                 <?php  } } ?>

                            <?php }
                        }else{ ?>

                        <div class="empty_chat_text d-flex align-items-center justify-content-center">
                            <p>Start Conversation with Radio DJ'S</p>
                        </div>

                    <?php    }
                    } ?>

                

            </div>

            <div class="caht-box-footer">

                <div class="selected_content"> 
                    <img src="" id="output" width="130px">
                    <audio src="" id="audio_output" controls></audio>
                    <button type="button" class="remove_preview_btn">X</button>
                </div>
                <form id="chatForm">

                    <div class="input_box">
                        <textarea name="message" id="message" class="form-control mb-3" placeholder="Type Message" cols="30" rows="3"></textarea>
                    </div>
    
                    <div class="d-flex justify-content-between">
                        <div class="media_box d-flex align-items-center">
                            <img src="img/camera.svg" class="me-3 up_image" alt="Camera Icon" width="30px">
                            <input type="file" class="d-none" name="picture" id="image__file__input" onchange="loadFile(event)" accept="image/*">
                            <input type="file" class="d-none" name="audio" id="audio__file__input" onchange="loadAudio(event)" accept="audio/*">
    
                            <img src="img/audio_upload.svg" class="audioBtn" alt="Microphone Icon" width="27px">
                            
                        </div>
                        <div class="send_chat_btn">
                            <button type="button" class="btn btn-primary wid-150 submit_btn" disabled>Send</button>
                        </div>
                    </div>
                </form>

            </div>

        </div>

    </div>




    <script src="js/jquery.min.js"></script>
    
    <script>

        var loadFile = function(event) {
            var image = document.getElementById('output');
            image.src = URL.createObjectURL(event.target.files[0]);
            $('#audio_output').attr('src', '');
            $('#audio__file__input').val('');
            $('#audio_output').addClass('d-none');
            $('.selected_content').css({'display':'block'});
            $('.submit_btn').prop('disabled', false);
        };
        var loadAudio = function(event) {
            var audio_output = document.getElementById('audio_output');
            audio_output.src = URL.createObjectURL(event.target.files[0]);
            $('#output').attr('src', '');
            $('#image__file__input').val('');
            $('#audio_output').removeClass('d-none');
            $('.selected_content').css({'display':'block'});
            $('.submit_btn').prop('disabled', false);
            
        };
        $('.up_image').click(function(){
            $('#image__file__input').click();
        });
        $('.audioBtn').click(function(){
            $('#audio__file__input').click();
        });

        $('.remove_preview_btn').click(function(){
            $('.selected_content').css({'display':'none'});
            var length = $('#message').val().length;
            if(length < 2){
                $('.submit_btn').prop('disabled', true);
                $('#output').attr('src', '');
            }
            $('#image__file__input').val('');
            $('#audio__file__input').val('');

        });
        $('#message').keypress(function(e){
            $('.submit_btn').prop('disabled', false);
        });
        
        $('#message').keydown(function(e){
            if(e.keyCode == 8) {
                var length = $('#message').val().length;
                var img_check = $('#output').attr('src');
                if(length < 2){
                    if( img_check == ''){
                        $('.submit_btn').prop('disabled', true);
                    }
                }
            }
        });

        $('.user_name_input').keypress(function(){
            $('.with_name_btn').prop('disabled', false);
            // alert('hell');
        });

        $('#user_name_input').keydown(function(e){
            if(e.keyCode == 8) {
                var user_name_input = $('#user_name_input').val().length;
                if(user_name_input < 2){
                    $('.with_name_btn').prop('disabled', true);
                }
            }
        });
        
        $('.with_name_btn').click(function(){
            var user_name_input = $('#user_name_input').val()
            $.ajax({
                url: 'setUser.php',
                type: 'POST',
                data: {usr_name:user_name_input},
                success: function (response_data) {
                    // alert(response_data);
                    $("<div class='empty_chat_text d-flex align-items-center justify-content-center'><p>Start Conversation with Radio DJ'S</p></div>").appendTo('.chat_content');
                    $('.user__name ').css({'transform':'scaleY(0)'});
                }
            })
        })

        $('.submit_btn').click(function(){
            var message = $("#message").val();
            var sourcePicture = $("#image__file__input").prop("files")[0];
            var sourceVoice = $("#recordFile").attr("src");
            if(message.length < 0 && $("#image__file__input").attr('src') != ''){
                alert('Please type a message')
            }else{
                $("#chatForm").submit();
            }
        });
      
        $("#chatForm").submit(function(e) {
            e.preventDefault();    
            var formData = new FormData(this);
            $.ajax({
                url: 'audioUpload.php',
                type: 'POST',
                data: formData,
                success: function (response) {
                    var result = response.split('_');
                    if(result[0] == 'Msg'){
                        $("<div class='chat_item'><span>"+result[1]+"</span><span class='time'>"+result[4]+"</span></div>").appendTo($('.chat_content'));
                        
                    }else if(result[0] == 'Pic'){
                        if(result[1] == ''){
                            $("<div class='chat_item has_img'><img width='130px' src='uploads/img/"+result[2]+"'><span class='time'>"+result[4]+"</span></div>").appendTo($('.chat_content'));
                        }else{
                            $("<div class='chat_item has_img'><span>"+result[1]+"</span><img style='margin:10px 0px' width='130px' src='uploads/img/"+result[2]+"'><span class='time'>"+result[4]+"</span></div>").appendTo($('.chat_content'));
                        }
                        
                    }else{
                        if(result[1] == ''){
                            $("<div class='chat_item has_record'><audio src='uploads/voices/"+result[3]+"' controls></audio><span class='time'>"+result[4]+"</span></div>").appendTo($('.chat_content'));
                        }else{
                            $("<div class='chat_item has_record'><span>"+result[1]+"</span><audio style='margin:10px 0px' src='uploads/voices/"+result[3]+"' controls></audio><span class='time'>"+result[4]+"</span></div>").appendTo($('.chat_content'));
                        }
                    }
                    $('.selected_content').css({'display':'none'});
                    $("#message").val('');
                    $('#output').attr('src', '');
                    $('#image__file__input').val('');
                    $('.submit_btn').prop('disabled', true);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
    </script>

</body>

</html>