<template>

        <v-form lazy-validation v-model="valid" ref="form" >
            <header class="text-xs-center">
                <h1>
                    Sign Up For {{$root.appName}}
                </h1>
                <p>Sign up now to see how we can help you make your life much easier!
                </p>

            </header>


            <v-layout row wrap>
                <v-flex xs12 sm6 pa-4>
                    <v-text-field
                            :rules="nameRules"
                            label="Full Name"
                            v-model="newUser.name"
                            required
                            :error-messages="errors.name"
                            @change="errors.name=null"
                    ></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 pa-4>
                    <v-text-field
                            :rules="emailRules"
                            label="Email Address"
                            v-model="newUser.email"
                            :error-messages="errors.email"
                            required
                            @change="errors.email=null"
                    ></v-text-field>
                </v-flex>
            </v-layout>
            <v-layout row wrap>
                <v-flex xs12 sm6 pa-4>
                    <v-text-field
                            label="Password"
                            :rules="passwordRules"
                            v-model="newUser.password"
                            required
                            type="password"
                            :error-messages="errors.password"
                            @change="errors.password=null"
                    ></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 pa-4>
                    <v-text-field
                            :rules="passwordConfirmRules"
                            label="Confirm Password"
                            v-model="newUser.password_confirmation"
                            required
                            type="password"
                            :error-messages="errors.password_confirmation"
                            @change="errors.password_confirmation=null"
                    ></v-text-field>
                </v-flex>
            </v-layout>

        <p>
            By clicking "Register Now", you will create an account with {{$root.appName}} and certify that you agree to our <a href="/terms" target="_blank" >Terms and Conditions</a>
        </p>
        <v-btn large block color="success" @click.native="register" :disabled="$root.isLoading">
            <span>Register Now</span>
        </v-btn>

        <p>
            {{$root.appName}} wants to protect your privacy and keep you safe on the internet. Our privacy and cookie policies are available below.

        </p>
        <ul>
            <li class="ml-4"><a href="/cookies" target="_blank">Cookie Policy</a></li>
            <li class="ml-4"><a href="/privacy" target="_blank">Privacy Policy</a></li>
        </ul>

        </v-form>


</template>
<script>
    export default {
        data() {
            return {
                valid:false,
                nameRules: [
                    (v) => !!v || 'Your Name is required',
                    (v) => v && v.length <= 150 || 'Must be less than 150 characters'
                ],
                emailRules: [
                    (v) => !!v || 'Email is required',
                    (v) => v && v.length <= 175 || 'Email must be less than 175 characters'
                ],
                passwordRules:[
                    (v) => !!v || 'A Password is required',
                ],
                passwordConfirmRules: [
                    (v) => !!v || 'You must confirm your password',
                    (v) => v && v === this.newUser.password || 'Passwords Must Match'
                ],
                errors:
                    {
                        email:null,
                        password:null,
                        password_confirmation:null,
                        name:null
                    },
                legalDocument:null,
                showLegalDocument:false,
                legalDocumentIsLoading:false,
                newUser:{
                    email:null,
                    name:null,
                    password:null,
                    password_confirmation:null,

                },
            }
        },
        methods: {
            register() {
                if(this.$refs.form.validate())
                {
                this.$root.isLoading=true;
                    this.$root.auth.register(this.newUser.name,this.newUser.email, this.newUser.password, this.newUser.password_confirmation)
                        .then((result)=>{
                            if(result.success)
                            {//if successful
                                this.$root.isLoading=false;
                                this.$root.displayNotification(result.message,'success');
                                this.$router.push({name:'home'});
                            }
                            else
                            {//not successful
                                this.$root.isLoading=false;
                                //manually set errors
                                this.$root.displayNotification(result.message,'red');
                                this.errors.email=result.errors.email;
                                this.errors.password=result.errors.password;
                                this.errors.password_confirmation=result.errors.password_confirmation;
                                this.errors.name=result.errors.name;
                            }

                        })
                }
                else
                {
                    this.$root.displayNotification('Please check the registration form for errors and try again. Remember, all fields are required.','red');
                }
            },

        },
        mounted()
        {

        },
        created(){


        }
    }

</script>