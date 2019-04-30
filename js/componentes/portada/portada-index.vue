<template>
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
<script>
export default {
    data:()=>({
      chat_input:'',
      //chat_input2:'',
      session_id: Math.floor(Math.random()*100),
      websocket_server:new WebSocket("ws://localhost:8000/"),
    }),
    created:function(){
      
    },
    mounted:function(){
        console.log()
        this.initWebSocket();
        this.hideChat(0);
        setTimeout(() => {
            this.toggleFab();
        }, 1000);
        //Toggle chat and links 
    },
    methods:{
        chat_fullscreen_loader(){
            document.querySelectorAll('.fullscreen').forEach(element => {
                element.classList.toggle('zmdi-window-maximize');
            });
            document.querySelectorAll('.fullscreen').forEach(element => {
                element.classList.toggle('zmdi-window-restore');
            });
            document.querySelectorAll('.chat').forEach(element => {
                element.classList.toggle('chat_fullscreen');
            });
            document.querySelectorAll('.fab').forEach(element => {
                element.classList.toggle('is-hide');
            });
            document.querySelectorAll('.header_img').forEach(element => {
                element.classList.toggle('change_img');
            });
            document.querySelectorAll('.img_container').forEach(element => {
                element.classList.toggle('change_img');
            });
            document.querySelectorAll('.chat_header').forEach(element => {
                element.classList.toggle('chat_header2');
            });
            document.querySelectorAll('.fab_field').forEach(element => {
                element.classList.toggle('fab_field2');
            });
            document.querySelectorAll('.chat_converse').forEach(element => {
                element.classList.toggle('chat_converse2');
            });
        },
        hideChat(hide) {
            switch (hide) {
              case 0:
                    document.querySelector('#chat_converse').style.display='none';
                    document.querySelector('#chat_body').style.display='none';
                    document.querySelector('#chat_form').style.display= 'none';
                    document.querySelector('.chat_login').style.display='block';
                    document.querySelector('.chat_fullscreen_loader').style.display='none';
                    document.querySelector('#chat_fullscreen').style.display= 'none';
                    break;
              case 1:
                    document.querySelector('#chat_converse').style.display='block';
                    document.querySelector('#chat_body').style.display='none';
                    document.querySelector('#chat_form').style.display='none';
                    document.querySelector('.chat_login').style.display='none';
                    document.querySelector('.chat_fullscreen_loader').style.display='block';
                    break;
              case 2:
                    document.querySelector('#chat_converse').style.display='none';
                    document.querySelector('#chat_body').style.display='block';
                    document.querySelector('#chat_form').style.display='none';
                    document.querySelector('.chat_login').style.display='none';
                    document.querySelector('.chat_fullscreen_loader').style.display='block';
                    break;
              case 3:
                    document.querySelector('#chat_converse').style.display='none';
                    document.querySelector('#chat_body').style.display='none';
                    document.querySelector('#chat_form').style.display='block';
                    document.querySelector('.chat_login').style.display='none';
                    document.querySelector('.chat_fullscreen_loader').style.display='block';
                    break;
              case 4:
                    document.querySelector('#chat_converse').style.display='none';
                    document.querySelector('#chat_body').style.display='none';
                    document.querySelector('#chat_form').style.display='none';
                    document.querySelector('.chat_login').style.display='none';
                    document.querySelector('.chat_fullscreen_loader').style.display='block';
                    document.querySelector('#chat_fullscreen').style.display='block';
                    break;
            }
        },
        toggleFab() {
            document.querySelectorAll('.prime').forEach(element => {
                element.classList.toggle('zmdi-comment-outline')
            });
            document.querySelectorAll('.prime').forEach(element => {
                element.classList.toggle('zmdi-close')
            });
            document.querySelectorAll('.prime').forEach(element => {
                element.classList.toggle('is-active')
            });
            document.querySelectorAll('.prime').forEach(element => {
                element.classList.toggle('is-visible');
            });
            document.querySelectorAll('#prime').forEach(element => {
                element.classList.toggle('is-float');
            });
            document.querySelectorAll('.chat').forEach(element => {
                element.classList.toggle('is-visible');
            });
            document.querySelectorAll('.fab').forEach(element => {
                element.classList.toggle('is-visible')
            }); 
        },
      enter(){
        alert(this.chat_input==="peruvian");
        if (this.chat_input==="peruvian") {
            alert(this.chat_input);
            this.websocket_server.send(
                JSON.stringify({
                    'type':'updateUserPeruvian',
                    'user_id':123,
                    'chat_msg':chat_msg
                })
            );
            return;
        }
        var chat_msg = this.chat_input;
        this.websocket_server.send(
            JSON.stringify({
                'type':'chat',
                'user_id':this.session_id,
                'chat_msg':chat_msg
            })
        );
        this.chat_input='';
    },
    /*enter2(){
        var chat_msg = this.chat_input2;
        this.websocket_server.send(
            JSON.stringify({
                'type':'chat2',
                'user_id':this.session_id,
                'chat_msg':chat_msg
            })
        );
        this.chat_input2='';
    },*/
    initWebSocket(){
        var self = this;
        this.websocket_server.onopen = function(e) {
            self.websocket_server.send(
                JSON.stringify({
                    'type':'socket',
                    'user_id':this.session_id
                })
            );
        };
        this.websocket_server.onerror = function(e) {
            // Errorhandling
        }
        this.websocket_server.onmessage = function(e)
        {
            var json = JSON.parse(e.data);
            switch(json.type) {
                case 'chat':
                    document.getElementById('chat_fullscreen').innerHTML += (json.msg);
                    break;
                /*case 'chat2':
                    document.getElementById('chat_output2').innerHTML += (json.msg);
                    break;*/
            }
        }
    },
    }
}
</script>
