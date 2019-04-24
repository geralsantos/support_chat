var portada_index = {
    template: '#portada-index',
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
