<template>

    <v-form lazy-validation v-model="valid" ref="form" >
        <header class="text-xs-center">
            <h1>
                Login To {{$root.appName}}
            </h1>
        </header>
        <v-layout row wrap>

            <v-flex xs12 sm6 pa-4>
                <v-text-field
                        :rules="emailRules"
                        prepend-inner-icon="email"
                        label="Email Address"
                        v-model="email"
                        :error-messages="errors.email"
                        required
                        @change="errors.email=null"
                ></v-text-field>
            </v-flex>
            <v-flex xs12 sm6 pa-4>
                <v-text-field
                        prepend-inner-icon="lock"
                        label="Password"
                        :rules="passwordRules"
                        v-model="password"
                        required
                        type="password"
                        :error-messages="errors.password"
                        @change="errors.password=null"
                ></v-text-field>
            </v-flex>
        </v-layout>

        <v-btn large block color="success" @click.native="login" :disabled="$root.isLoading">
            <span>Login</span>
        </v-btn>
        <p class="text-xs-center">Forgot your password? <v-btn color="warning" flat :to="{name:'forgotPassword'}">Reset Your Password</v-btn></p>
        <p class="text-xs-center">Don't have an account yet? <v-btn :to="{name:'register'}" color="success" flat >Create an Account</v-btn></p>

    </v-form>


</template>
<script>
    export default {
        data() {
            return {
                valid:false,
                password:null,
                email:null,
                emailRules: [
                    (v) => !!v || 'Email is required',
                ],
                passwordRules:[
                    (v) => !!v || 'A Password is required',
                ],
                errors:
                    {
                        email:null,
                        password:null,
                    },
            }
        },
        methods: {
            login() {
                if(this.$refs.form.validate())
                {
                    this.$root.isLoading=true;
                    this.$root.auth.login(this.email, this.password)
                        .then((result)=>{
                            if(result.success)
                            {//if successful
                                this.$root.isLoading=false;
                                this.$root.displayNotification(result.message,'success');
                                this.$router.push({name:'home'});
                            }
                            else
                            {//not successful
                                //manually set errors
                                this.$root.isLoading=false;
                                this.$root.displayNotification(result.message,'red');
                                this.errors.email=result.errors.email;
                                this.errors.password=result.errors.password;
                            }//else

                        })
                }
                else
                {
                    this.$root.displayNotification('Please check the login form for errors and try again.','red');
                }
            },

        }
    }

</script>