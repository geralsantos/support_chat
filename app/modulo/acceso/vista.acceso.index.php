<?php
/*
require_once ROOT . '/vendor/autoload.php';
$fb = new Facebook\Facebook([
  'app_id' => API_ID_FACEBOOK,
  'app_secret' => API_ID_SECRET_FACEBOOK,
  'default_graph_version' => 'v2.6',
  "persistent_data_handler"=>"session"
  ]);
$helper = $fb->getRedirectLoginHelper();
$permissions = ['email']; // optional
$loginUrl = $helper->getLoginUrl(LOGIN_URL_FACEBOOK."?tipo_acceso=login_facebook", $permissions);*/
 ?>
<div>
  <v-app dark="dark">
    <v-content>
      <v-container fill-height="fill-height">
        <v-layout align-center="align-center" justify-center="justify-center">
          <v-flex class="login-form text-xs-center"> 
            <div class="display-1 mb-3">
              <v-icon class="mr-2" large="large">work</v-icon> MyWorkspace
            </div>
            <v-card light="light">
              <v-card-text>
                <div class="subheading">
                  <template v-if="options.isLoggingIn">Log in to your customer portal</template>
                  <template v-else="v-else">Crate a new account</template>
                </div>
                <v-form action="<?php $this->url('index') ?>" id="loginForm" method="post">
                  <v-text-field v-if="!options.isLoggingIn" v-model="user.name" light="light" prepend-icon="person" label="Name"></v-text-field>
                  <v-text-field v-model="user.email" light="light" prepend-icon="email" name="usuario" label="Email" type="text"></v-text-field>
                  <v-text-field v-model="user.password" light="light" prepend-icon="lock" name="contrasena" label="Password" type="password"></v-text-field>
                  <v-checkbox v-if="options.isLoggingIn" v-model="options.shouldStayLoggedIn" light="light" label="Stay logged in?" hide-details="hide-details"></v-checkbox>
                  <v-btn v-if="options.isLoggingIn"  block="block" type="submit">Sign in</v-btn>
                  <v-btn v-else="v-else" block="block" type="submit" @click.prevent="options.isLoggingIn = true">Sign up</v-btn>
                </v-form>
              </v-card-text>
            </v-card>
            <div v-if="options.isLoggingIn">Don't have an account?
              <v-btn light="light" @click="options.isLoggingIn = false">Sign up</v-btn>
            </div>
          </v-flex>
        </v-layout>
      </v-container>
    </v-content>
    <v-footer app="app">
      <v-flex class="text-xs-center">© 2018. All rights reserved.</v-flex>
    </v-footer>
  </v-app>
</div>
