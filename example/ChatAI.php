<!--
/**
* @author Reza J. https://www.facebook.com/ppabcd <rezajuliandri20@gmail.com>
* @license Kyla-AI (c) 2017
*/
-->
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Live Chat</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Droid+Sans:400,700">
   <style media="screen">
   /* CSS Document */

   /* ---------- GENERAL ---------- */

   body {
      background: #e9e9e9;
      color: #9a9a9a;
      font: 100%/1.5em "Droid Sans", sans-serif;
      margin: 0;
   }

   a { text-decoration: none; }

   fieldset {
      border: 0;
      margin: 0;
      padding: 0;
   }

   h4, h5 {
      line-height: 1.5em;
      margin: 0;
   }

   hr {
      background: #e9e9e9;
      border: 0;
      -moz-box-sizing: content-box;
      -o-box-sizing : content-box;
      -webkit-box-sizing : content-box;
      box-sizing: content-box;
      height: 1px;
      margin: 0;
      min-height: 1px;
   }

   img {
      border: 0;
      display: block;
      height: auto;
      max-width: 100%;
   }

   input {
      border: 0;
      color: inherit;
      font-family: inherit;
      font-size: 100%;
      line-height: normal;
      margin: 0;
   }

   p { margin: 0; }

   .clearfix { *zoom: 1; } /* For IE 6/7 */
   .clearfix:before, .clearfix:after {
      content: "";
      display: table;
   }
   .clearfix:after { clear: both; }

   /* ---------- LIVE-CHAT ---------- */

   #live-chat {
      bottom: 0;
      font-size: 12px;
      right: 24px;
      position: fixed;
      width: 300px;
   }

   #live-chat header {
      background: #293239;
      border-radius: 5px 5px 0 0;
      color: #fff;
      cursor: pointer;
      padding: 8px 20px;
   }

   #live-chat h4:before {
      background: #1a8a34;
      border-radius: 50%;
      content: "";
      display: inline-block;
      height: 8px;
      margin: 0 8px 0 0;
      width: 8px;
   }

   #live-chat h4 {
      font-size: 12px;
   }

   #live-chat h5 {
      font-size: 10px;
   }

   #live-chat .form {
      padding: 24px;
   }

   #live-chat input[type="text"] {
      border: 1px solid #ccc;
      border-radius: 3px;
      padding: 8px;
      outline: none;
      width: 210px;
   }
	.voice {
		width : 30px;
		height : 30px;
		border-radius: 50%;
		text-align: center;
		background-color: #fff;
		padding: 8px;
		outline: none;
		margin-left: 10px;
		border : 3px solid #f0f0f0;
	}
	.voice.active {
		transition: border-width 0.1s ease-in-out;
		border : 3px solid #fc5555;
		background-position: center;
		background-repeat: no-repeat;

	}
   input.blue {
      border : 1px solid rgba(0, 175, 244, 0.6) !important;
   }
   .chat-message-counter {
      background: #e62727;
      border: 1px solid #fff;
      border-radius: 50%;
      display: none;
      font-size: 12px;
      font-weight: bold;
      height: 28px;
      left: 0;
      line-height: 28px;
      margin: -15px 0 0 -15px;
      position: absolute;
      text-align: center;
      top: 0;
      width: 28px;
   }

   .chat-close {
      background: #1b2126;
      border-radius: 50%;
      color: #fff;
      display: block;
      float: right;
      font-size: 10px;
      height: 16px;
      line-height: 16px;
      margin: 2px 0 0 0;
      text-align: center;
      width: 16px;
   }

   .chat {
      background: #fff;
   }

   .chat-history {
      height: 252px;
      padding: 8px 24px;
      overflow-y: scroll;
   }

   .chat-message {
      margin: 16px 0;
   }

   .chat-message img {
      border-radius: 50%;
      float: left;
   }

   .chat-message-content {
      margin-left: 56px;
   }

   .chat-time {
      float: right;
      font-size: 10px;
   }

   .chat-feedback {
      font-style: italic;
      margin: 0 0 0 80px;
   }
   </style>
</head>
<body>
	<div id="live-chat">
		<header class="clearfix">
			<a href="#" class="chat-close">x</a>
			<h4>Kyla</h4>
			<span class="chat-message-counter">0</span>
		</header>
		<div class="chat">
			<div class="chat-history" id="chat-history">
				<div class="chat-message clearfix">
					<img src="rsz_1kyla.png" alt="" width="32" height="32">
					<div class="chat-message-content clearfix">
						<span class="chat-time"></span>
						<h5>Kyla</h5>
						<p>Selamat datang di chat kyla.</p>
					</div> <!-- end chat-message-content -->
				</div> <!-- end chat-message -->
				<hr>
            <div id="content">

            </div>
			</div> <!-- end chat-history -->
			<p class="chat-feedback">Kyla is typing…</p>
			<div class="form">
            <fieldset>
					<input type="text" placeholder="Masukkan pesan…" autofocus id="chating"><button type="button" name="voice" class="voice active" onclick="toggleStartStop()"><img src="https://cdn2.iconfinder.com/data/icons/metro-uinvert-dock/256/Microphone_1.png" alt="" style="width : 10x; height : 10px;"></button>
               <input type="text" placeholder="Masukkan jawaban pertanyaan tadi" value="" class="blue" id="jawaban">
					<input type="hidden" name="kalimat" class="kalimat">
				</fieldset>
			</div>
		</div> <!-- end chat -->
	</div> <!-- end live-chat -->
	<script src="./jquery.min.js"></script>
	<script type="text/javascript" src="https://code.responsivevoice.org/responsivevoice.js"></script>
   <script type="text/javascript">
	//Speech Recognition
	var recognizing;
	var end = "";
	var recognition = new webkitSpeechRecognition();
	recognition.lang = "id-ID";
	recognition.continuous = true;
	reset();
	recognition.onend = reset();

	recognition.onresult = function (event) {
		for (var i = event.resultIndex; i < event.results.length; ++i) {
			if (event.results[i].isFinal) {
				end += event.results[i][0].transcript;
			}
		}
		if(end != null){
			$("#chating").val(end);
		}
	}
	function reset(){
		recognizing = false;
		end = "";
		$(".voice").removeClass("active");
	}

	function toggleStartStop() {
		if (recognizing) {
			recognition.stop();
			reset();
		} else {
			recognition.start();
			recognizing = true;
			$(".voice").addClass("active");
		}
	}
	//End Speech Recognition
   $(document).ready(function(){

      $(".chat-feedback").hide();
      $('.chat-message-counter').hide();
      $("#jawaban").hide();
      responsiveVoice.speak("Welcome to kayla chat.","US English Female",{rate:0.7});
      //responsiveVoice.speak("Welcome to kayla chat.","US English Female",{pitch: 0,rate:0.7,volume:1});
      //responsiveVoice.speak("Dzięki za wszystko","Polish Female",{pitch: 0,rate:0.7,volume:1});
      function speak(data){
         responsiveVoice.speak(data,"US English Female",{rate:1});
      }
      function scroll(){
         $("#chat-history").scrollTop($("#chat-history")[0].scrollHeight);
      }
      (function() {

         $('#live-chat header').on('click', function() {

            $('.chat').slideToggle(300, 'swing');
         });

      }) ();
      function load_chat(){
         setTimeout(function(){
            $.ajax({
               type: 'GET',
               url: 'json.php?chat',
               data: { get_param: 'value' },
               dataType: 'json',
               success: function (data) {
                  $.each(data, function(index, element) {
                     //var username = element.username;
                     //var username = username.capitalize();
                     //alert(username);
                     var time = element.time;
                     time = time.split(" ");
                     time = time[1].split(":");
                     time = time[0]+":"+time[1];
                     var img = "rsz_reza_a.png";
                     if(element.username == "Kyla"){
                        var img = "rsz_1kyla.png";
                     }
                     var chat = "<div class=\"chat-message clearfix\"><img src=\""+img+"\" alt=\"\" width=\"32\" height=\"32\"><div class=\"chat-message-content clearfix\"><span class=\"chat-time\">"+time+"</span><h5>"+element.username+"</h5><p>"+element.content+"</p></div> <!-- end chat-message-content --></div> <!-- end chat-message --><hr>";
                        $('#content').append(chat);
                     });
                     scroll();
                  }
               });
            },100);
         }
         load_chat();
         $("#chating").on('keyup',function(e){
            if($(this).val() != ""){
               if (e.keyCode === 13) {
                  $(".chat-feedback").show();
                  var jawaban = $(this).val();
                  $.ajax({
                   type: 'POST',
                   url: 'process.php',
                   data: { chat: jawaban },
                   dataType:'html',
                   success: function (data) {
                     $(".chat-feedback").hide();
                     var data = data.split(" | ");
                     console.log(data);
                     if(data[1] == "1"){
                        $("#chating").hide();
								$(".voice").hide();
                        $("#jawaban").show();
                     }
                     $(".kalimat").val(data[0]);
                     $('#content').empty();
                     $("#chating").val('');
                     $("#jawaban").val('');
                     load_chat();
                     speak(data[2]);
                     }
                  });
               }
            }
         });
         $("#jawaban").on('keyup',function(e){
            if($(this).val() != ""){
               if(e.keyCode === 13){
                  $(".chat-feedback").show();
                  var jawaban = $(this).val();
                  var kalimat = $(".kalimat").val();
                  if(jawaban == "batal"){
                     $("#chating").show();
							$(".voice").show();
                     $("#jawaban").hide();
                     $("#chating").val('');
                     $("#jawaban").val('');
                     $(".chat-feedback").hide();
                  }
                  else {
                     if(kalimat != null){
                        $.ajax({
                           type: 'POST',
                           url: 'process.php',
                           data: { respon: jawaban, kalimat: kalimat },
                           dataType:'html',
                           success: function (data) {
                              $(".chat-feedback").hide();
                              $(".kalimat").val('');
                              $("#chating").show();
										$(".voice").show();
                              $("#jawaban").hide();
                              $("#chating").val('');
                              $("#jawaban").val('');
                              load_chat();
                              speak(data);
                              console.log(data);
                           }
                        });
                     }
                  }
               }
            }
         });
   });
   </script>
</body>
</html>
