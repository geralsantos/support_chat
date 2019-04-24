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
  <div class="chat_body chat_login">
      <a id="chat_first_screen" @click="hideChat(1)" class="fab"><i class="zmdi zmdi-arrow-right"></i></a>
      <p>We make it simple and seamless for businesses and people to talk to each other. Ask us anything</p>
  </div>
  <div id="chat_converse" class="chat_conversion chat_converse">
          <a id="chat_second_screen" @click="hideChat(2)" class="fab"><i class="zmdi zmdi-arrow-right"></i></a>
    <span class="chat_msg_item chat_msg_item_admin">
          <div class="chat_avatar">
             <img src="http://res.cloudinary.com/dqvwa7vpe/image/upload/v1496415051/avatar_ma6vug.jpg"/>
          </div>Hey there! Any question?</span>
    <span class="chat_msg_item chat_msg_item_user">
          Hello!</span>
          <span class="status">20m ago</span>
    <span class="chat_msg_item chat_msg_item_admin">
          <div class="chat_avatar">
             <img src="http://res.cloudinary.com/dqvwa7vpe/image/upload/v1496415051/avatar_ma6vug.jpg"/>
          </div>Hey! Would you like to talk sales, support, or anyone?</span>
    <span class="chat_msg_item chat_msg_item_user">
          Lorem Ipsum is simply dummy text of the printing and typesetting industry.</span>
           <span class="status2">Just now. Not seen yet</span>
  </div>
  <div id="chat_body" class="chat_body">
      <div class="chat_category">
        <a id="chat_third_screen" @click="hideChat(3)" class="fab"><i class="zmdi zmdi-arrow-right"></i></a>
      <p>What would you like to talk about?</p>
      <ul>
        <li>Tech</li>
        <li class="active">Sales</li>
        <li >Pricing</li>
        <li>other</li>
      </ul>
      </div>

  </div>
  <div id="chat_form" class="chat_converse chat_form">
  <a id="chat_fourth_screen" @click="hideChat(4)" class="fab"><i class="zmdi zmdi-arrow-right"></i></a>
    <span class="chat_msg_item chat_msg_item_admin">
          <div class="chat_avatar">
             <img src="http://res.cloudinary.com/dqvwa7vpe/image/upload/v1496415051/avatar_ma6vug.jpg"/>
          </div>Hey there! Any question?</span>
    <span class="chat_msg_item chat_msg_item_user">
          Hello!</span>
          <span class="status">20m ago</span>
    <span class="chat_msg_item chat_msg_item_admin">
          <div class="chat_avatar">
             <img src="http://res.cloudinary.com/dqvwa7vpe/image/upload/v1496415051/avatar_ma6vug.jpg"/>
          </div>Agent typically replies in a few hours. Don't miss their reply.
          <div>
            <br>
            <form class="get-notified">
                <label for="chat_log_email">Get notified by email</label>
                <input id="chat_log_email" placeholder="Enter your email"/>
                <i class="zmdi zmdi-chevron-right"></i>
            </form>
          </div></span>

      <span class="chat_msg_item chat_msg_item_admin">
          <div class="chat_avatar">
             <img src="http://res.cloudinary.com/dqvwa7vpe/image/upload/v1496415051/avatar_ma6vug.jpg"/>
          </div>Send message to agent.
          <div>
            <form class="message_form">
                <input placeholder="Your email"/>
                <input placeholder="Technical issue"/>
                <textarea rows="4" placeholder="Your message"></textarea>
                <button>Send</button> 
            </form>

      </div></span>   
  </div>
    <div id="chat_fullscreen" class="chat_conversion chat_converse">
    <span class="chat_msg_item chat_msg_item_admin">
          <div class="chat_avatar">
             <img src="http://res.cloudinary.com/dqvwa7vpe/image/upload/v1496415051/avatar_ma6vug.jpg"/>
          </div>Hey there! Any question?</span>
    <span class="chat_msg_item chat_msg_item_user">
          Hello!</span>
          <div class="status">20m ago</div>
    <span class="chat_msg_item chat_msg_item_admin">
          <div class="chat_avatar">
             <img src="http://res.cloudinary.com/dqvwa7vpe/image/upload/v1496415051/avatar_ma6vug.jpg"/>
          </div>Hey! Would you like to talk sales, support, or anyone?</span>
    <span class="chat_msg_item chat_msg_item_user">
          Lorem Ipsum is simply dummy text of the printing and typesetting industry.</span>
    <span class="chat_msg_item chat_msg_item_admin">
          <div class="chat_avatar">
             <img src="http://res.cloudinary.com/dqvwa7vpe/image/upload/v1496415051/avatar_ma6vug.jpg"/>
           </div>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</span>
    <span class="chat_msg_item chat_msg_item_user">
          Where can I get some?</span>
    <span class="chat_msg_item chat_msg_item_admin">
          <div class="chat_avatar">
             <img src="http://res.cloudinary.com/dqvwa7vpe/image/upload/v1496415051/avatar_ma6vug.jpg"/>
           </div>The standard chuck...</span>
    <span class="chat_msg_item chat_msg_item_user">
          There are many variations of passages of Lorem Ipsum available</span>
          <div class="status2">Just now, Not seen yet</div>
    <span class="chat_msg_item ">
        <ul class="tags">
          <li>Hats</li>
          <li>T-Shirts</li>
          <li>Pants</li>
        </ul>
    </span>
  </div>
  <div class="fab_field">
    <a id="fab_camera" class="fab"><i class="zmdi zmdi-camera"></i></a>
    <a id="fab_send" class="fab"><i class="zmdi zmdi-mail-send"></i></a>
    <textarea id="chatSend" name="chat_message" v-model="chat_input" @keyup.enter="enter()" placeholder="Escriba su mensaje aquí" class="chat_field chat_message"></textarea>
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
