<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="{{ mix('/css/app.css') }}" type="text/css">
        <title>{{config('app.name')}}</title>

        <link rel="apple-touch-icon" sizes="180x180" href="/img/fav/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/img/fav/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/img/fav/favicon-16x16.png">
        <link rel="manifest" href="/img/fav/site.webmanifest">
        <link rel="shortcut icon" href="/img/fav/favicon.ico">

        <meta name="theme-color" content="#ffffff">


        <meta name="apple-mobile-web-app-title" content="{{config('app.name')}}">
        <meta name="application-name" content="{{config('app.name')}}">
        <meta name="mobile-web-app-capable" content="yes">

        <!-- Add to home screen for Safari on iOS -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="apple-mobile-web-app-title" content="{{config('app.name')}}">
        <link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon.png">
        <!-- Add to home screen for Windows -->

        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="msapplication-config" content="/img/fav/browserconfig.xml">

        <base href="/">
        <script>
            window.Laravel = {!! $token!!};
            window.apiRoutes = {!! $routes!!};
        </script>


    </head>
    <body>

    <div id="app">
        <div v-if="!auth.booted" style="background-color: #212121;position: absolute;z-index:100000;top:0;bottom: 0;left: 0;right:0;">
            <img style="display:block;margin:auto; padding-top: 30vh" src="data:image/png;base64,{{base64_encode(file_get_contents(public_path().'/img/logo.png'))}}">
            <p style="text-align: center;color:white;font-size: 25px;">{{config('app.name')}} Is Loading...Please Wait</p>
        </div>
        <v-app>
            <v-navigation-drawer app fixed v-model="drawer">
                <v-toolbar flat dark  class="blue darken-4">
                    <v-list class="pa-0" >
                        <v-list-tile two-line class="title pt-2 pb-3" avatar>

                            <v-list-tile-avatar>
                                <img src="/img/logo.png">
                            </v-list-tile-avatar>
                            <v-list-tile-content>
                                <v-list-tile-title>@{{$root.appName}}</v-list-tile-title>
                                <p>
                                    <span v-if="$root.auth.user.is_authenticated">Hi, @{{$root.auth.user.name}}!</span>
                                    <span v-else>Hello, Guest!</span>
                                </p>
                            </v-list-tile-content>
                        </v-list-tile>


                    </v-list>
                </v-toolbar>


                <v-list class="pt-0" dense>
                    <v-list-tile :to="{name:'home'}">
                        <v-list-tile-action>
                            <v-icon>dashboard</v-icon>
                        </v-list-tile-action>

                        <v-list-tile-content>
                            <v-list-tile-title>Home</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>

                </v-list>
                <v-list class="pt-0 grey darken-3 white--text" dense class="blue">
                    <v-list-tile dark v-if="!$root.auth.user.is_authenticated" :to="{name:'login'}">
                        <v-list-tile-action>
                            <v-icon>account_circle</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>Login</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>

                    <v-list-tile dark v-if="!$root.auth.user.is_authenticated" :to="{name:'register'}">
                        <v-list-tile-action>
                            <v-icon>person_add</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>Register</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                    <v-list-tile dark v-else @click="$root.auth.logout">
                        <v-list-tile-action>
                            <v-icon>logout</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>Logout</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>

                    <v-list-group v-if="$root.auth.user.is_authenticated">

                        <template v-slot:activator>

                            <v-list-tile dark>
                                <v-list-tile-action>
                                    <v-icon>account_circle</v-icon>
                                </v-list-tile-action>
                                <v-list-tile-content>

                                <v-list-tile-title>Your Account</v-list-tile-title>
                                </v-list-tile-content>
                            </v-list-tile>
                        </template>


                        <v-list-tile :to="{name:'changePassword'}" dark>
                            <v-list-tile-action>
                                <v-icon>lock</v-icon>
                            </v-list-tile-action>

                            <v-list-tile-content>
                                <v-list-tile-title>Change Password</v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>


                    </v-list-group>



                </v-list>
            </v-navigation-drawer>
            <v-toolbar app>
                <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
            </v-toolbar>
            <v-content>
                <v-container>
                    <router-view>

                    </router-view>
                </v-container>
            </v-content>
            <v-footer app color="#1c4888">

                <span class="white--text pa-1">&copy; 2019 {{config('app.name')}}</span>
            </v-footer>

            <v-dialog v-model="isLoading" persistent width="300">
                <v-card color="primary" dark>
                    <v-card-text>
                        Loading...Please Wait.
                        <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                    </v-card-text>
                </v-card>
            </v-dialog>

            <v-snackbar v-model="notification.show"  :timeout="notification.timeout" :color = "notification.color" :multi-line="true">@{{notification.content}}
                <v-btn color="white" flat @click="notification.show = false">
                    Close
                </v-btn>
            </v-snackbar>
        </v-app>
    </div>
    <script src="{{ mix('/js/app.js') }}"></script>

    </body>
</html>
