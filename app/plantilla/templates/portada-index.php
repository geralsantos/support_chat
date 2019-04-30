<template id="portada-index">
    <div class="" >
    <h3>Click on Arrow button to see next screen</h3>

<div class="fabs">
<div class="chat">
  <div class="chat_header">
    <div class="chat_option">
    <div class="header_img">
      <img src="http://res.cloudinary.com/dqvwa7vpe/image/upload/v1496415051/avatar_ma6vug.jpg"/>
      </div>
      <span id="chat_head">Peruvian Airlines</span> <br> <span class="agent">Agent</span> <span class="online">(Online)</span>
     <span id="chat_fullscreen_loader" @click="chat_fullscreen_loader()" class="chat_fullscreen_loader"><i class="fullscreen zmdi zmdi-window-maximize"></i></span>

    </div>

  </div>
 
    <div id="chat_fullscreen" class="chat_conversion chat_converse" >
     
    <span class="chat_msg_item ">
        <ul class="tags">
          <li>Llamar a un asesor de ventas</li>
          <li></li>
          <li>Pants</li>
        </ul>
    </span>
  </div>
  <div class="fab_field">
    <a id="fab_camera" class="fab"><i class="zmdi zmdi-camera"></i></a>
    <a id="fab_send" class="fab" @click="enter()"><i class="zmdi zmdi-mail-send"></i></a>
    <textarea id="chatSend" name="chat_message" v-model="chat_input" @keyup.enter="enter()" placeholder="Escriba su mensaje" class="chat_field chat_message"></textarea>
  </div>
</div>
  <a id="prime" @click="toggleFab()" class="fab"><i class="prime zmdi zmdi-comment-outline"></i></a>
</div>
    	<!--<textarea id="chat_input2" v-model="chat_input2" placeholder="Deine Nachricht2..." @keyup.enter="enter2()" ></textarea>-->
	<!--<div id="chat_output2"></div>	-->
		<textarea id="chat_input" v-model="chat_input" placeholder="Deine Nachricht..." @keyup.enter="enter()" ></textarea>
	<div id="chat_output"></div>	
            <input type="hidden" id="session_id" v-model="session_id" value="<?php echo mt_rand(1,999); ?>">
    </div>

</template>
