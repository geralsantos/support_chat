(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
"use strict";

var portada_index = {
  template: '#portada_index',
  data: function data() {
    return {
      chat_input: '',
      //chat_input2:'',
      session_id: Math.floor(Math.random() * 100),
      websocket_server: new WebSocket("ws://localhost:8000/")
    };
  },
  created: function created() {},
  mounted: function mounted() {
    var _this = this;

    console.log();
    this.initWebSocket();
    this.hideChat(0);
    setTimeout(function () {
      _this.toggleFab();
    }, 1000); //Toggle chat and links 
  },
  methods: {
    chat_fullscreen_loader: function chat_fullscreen_loader() {
      document.querySelectorAll('.fullscreen').forEach(function (element) {
        element.classList.toggle('zmdi-window-maximize');
      });
      document.querySelectorAll('.fullscreen').forEach(function (element) {
        element.classList.toggle('zmdi-window-restore');
      });
      document.querySelectorAll('.chat').forEach(function (element) {
        element.classList.toggle('chat_fullscreen');
      });
      document.querySelectorAll('.fab').forEach(function (element) {
        element.classList.toggle('is-hide');
      });
      document.querySelectorAll('.header_img').forEach(function (element) {
        element.classList.toggle('change_img');
      });
      document.querySelectorAll('.img_container').forEach(function (element) {
        element.classList.toggle('change_img');
      });
      document.querySelectorAll('.chat_header').forEach(function (element) {
        element.classList.toggle('chat_header2');
      });
      document.querySelectorAll('.fab_field').forEach(function (element) {
        element.classList.toggle('fab_field2');
      });
      document.querySelectorAll('.chat_converse').forEach(function (element) {
        element.classList.toggle('chat_converse2');
      });
    },
    hideChat: function hideChat(hide) {
      switch (hide) {
        case 0:
          document.querySelector('#chat_converse').style.display = 'none';
          document.querySelector('#chat_body').style.display = 'none';
          document.querySelector('#chat_form').style.display = 'none';
          document.querySelector('.chat_login').style.display = 'block';
          document.querySelector('.chat_fullscreen_loader').style.display = 'none';
          document.querySelector('#chat_fullscreen').style.display = 'none';
          break;

        case 1:
          document.querySelector('#chat_converse').style.display = 'block';
          document.querySelector('#chat_body').style.display = 'none';
          document.querySelector('#chat_form').style.display = 'none';
          document.querySelector('.chat_login').style.display = 'none';
          document.querySelector('.chat_fullscreen_loader').style.display = 'block';
          break;

        case 2:
          document.querySelector('#chat_converse').style.display = 'none';
          document.querySelector('#chat_body').style.display = 'block';
          document.querySelector('#chat_form').style.display = 'none';
          document.querySelector('.chat_login').style.display = 'none';
          document.querySelector('.chat_fullscreen_loader').style.display = 'block';
          break;

        case 3:
          document.querySelector('#chat_converse').style.display = 'none';
          document.querySelector('#chat_body').style.display = 'none';
          document.querySelector('#chat_form').style.display = 'block';
          document.querySelector('.chat_login').style.display = 'none';
          document.querySelector('.chat_fullscreen_loader').style.display = 'block';
          break;

        case 4:
          document.querySelector('#chat_converse').style.display = 'none';
          document.querySelector('#chat_body').style.display = 'none';
          document.querySelector('#chat_form').style.display = 'none';
          document.querySelector('.chat_login').style.display = 'none';
          document.querySelector('.chat_fullscreen_loader').style.display = 'block';
          document.querySelector('#chat_fullscreen').style.display = 'block';
          break;
      }
    },
    toggleFab: function toggleFab() {
      document.querySelectorAll('.prime').forEach(function (element) {
        element.classList.toggle('zmdi-comment-outline');
      });
      document.querySelectorAll('.prime').forEach(function (element) {
        element.classList.toggle('zmdi-close');
      });
      document.querySelectorAll('.prime').forEach(function (element) {
        element.classList.toggle('is-active');
      });
      document.querySelectorAll('.prime').forEach(function (element) {
        element.classList.toggle('is-visible');
      });
      document.querySelectorAll('#prime').forEach(function (element) {
        element.classList.toggle('is-float');
      });
      document.querySelectorAll('.chat').forEach(function (element) {
        element.classList.toggle('is-visible');
      });
      document.querySelectorAll('.fab').forEach(function (element) {
        element.classList.toggle('is-visible');
      });
    },
    enter: function enter() {
      alert(this.chat_input === "peruvian");

      if (this.chat_input === "peruvian") {
        alert(this.chat_input);
        this.websocket_server.send(JSON.stringify({
          'type': 'updateUserPeruvian',
          'user_id': 123,
          'chat_msg': chat_msg
        }));
        return;
      }

      var chat_msg = this.chat_input;
      this.websocket_server.send(JSON.stringify({
        'type': 'chat',
        'user_id': this.session_id,
        'chat_msg': chat_msg
      }));
      this.chat_input = '';
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
    initWebSocket: function initWebSocket() {
      var self = this;

      this.websocket_server.onopen = function (e) {
        self.websocket_server.send(JSON.stringify({
          'type': 'socket',
          'user_id': this.session_id
        }));
      };

      this.websocket_server.onerror = function (e) {// Errorhandling
      };

      this.websocket_server.onmessage = function (e) {
        var json = JSON.parse(e.data);

        switch (json.type) {
          case 'chat':
            document.getElementById('chat_fullscreen').innerHTML += json.msg;
            break;

          /*case 'chat2':
              document.getElementById('chat_output2').innerHTML += (json.msg);
              break;*/
        }
      };
    }
  }
};

},{}],2:[function(require,module,exports){
"use strict";

var _readingTime = _interopRequireDefault(require("reading-time"));

function _interopRequireDefault(obj) {
  return obj && obj.__esModule ? obj : {
    "default": obj
  };
}

window.calcRT = function (ev) {
  var stats = (0, _readingTime["default"])(ev.value).text;
  document.getElementById("readingTime").innerText = stats;
}; //Vue.http.options.emulateJSON=true; // http client


Vue.use(VueRouter); //Vue.http.options.emulateJSON=true; // http client

var router = new VueRouter({
  mode: 'hash',
  routes: [{
    path: '/portada-index',
    component: require('../../componentes/portada/portada-index.js')["default"]
  }]
});
var appVue = new Vue({
  el: '#vue_app',

  /* container vue */
  router: router,
  data: function data() {
    return {
      title_modulo: null,
      sidenavopen: "true",
      drawer: true,
      mini: true,
      right: null,
      rowsmodulos: []
    };
  },
  created: function created() {},
  mounted: function mounted() {
    this.listar_menu();
  },
  watch: {},
  methods: {
    listar_menu: function listar_menu() {
      console.log("menu-rincipal-portada-index");
      var self = this;
      axios.get('list_modulos?view').then(function (response) {
        console.log(response.data);
        self.rowsmodulos = response.data;
      });
    },
    toggleMenu: function toggleMenu() {
      this.menuVisible = !this.menuVisible;
    }
  }
});

},{"../../componentes/portada/portada-index.js":1,"reading-time":3}],3:[function(require,module,exports){
module.exports = require('./lib/reading-time');

},{"./lib/reading-time":4}],4:[function(require,module,exports){
/*!
 * reading-time
 * Copyright (c) Nicolas Gryman <ngryman@gmail.com>
 * MIT Licensed
 */

'use strict'

function ansiWordBound(c) {
  return (
    (' ' === c) ||
    ('\n' === c) ||
    ('\r' === c) ||
    ('\t' === c)
  )
}

function readingTime(text, options) {
  var words = 0, start = 0, end = text.length - 1, wordBound, i

  options = options || {}

  // use default values if necessary
  options.wordsPerMinute = options.wordsPerMinute || 200

  // use provided function if available
  wordBound = options.wordBound || ansiWordBound

  // fetch bounds
  while (wordBound(text[start])) start++
  while (wordBound(text[end])) end--

  // calculate the number of words
  for (i = start; i <= end;) {
    for (; i <= end && !wordBound(text[i]); i++) ;
    words++
    for (; i <= end && wordBound(text[i]); i++) ;
  }

  // reading time stats
  var minutes = words / options.wordsPerMinute
  var time = minutes * 60 * 1000
  var displayed = Math.ceil(minutes.toFixed(2))

  return {
    text: displayed + ' min read',
    minutes: minutes,
    time: time,
    words: words
  }
}

/**
 * Export
 */
module.exports = readingTime

},{}]},{},[2]);
