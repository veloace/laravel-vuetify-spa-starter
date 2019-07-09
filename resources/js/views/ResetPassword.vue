<template>

    <v-form lazy-validation v-model="valid" ref="form" >
        <header class="text-xs-center">
            <h1>
                Reset Your Password
            </h1>
            <p>
            </p>

        </header>


        <v-layout row wrap>
            <v-flex xs12  pa-4>
                <p>{{errors.token}}</p>
            </v-flex>
            <v-flex xs12  pa-4>
                <v-text-field
                        :rules="emailRules"
                        label="Email Address"
                        v-model="user.email"
                        :error-messages="errors.email"
                        required
                        @change="errors.email=null"
                ></v-text-field>
            </v-flex>
        </v-layout>
        <v-layout row wrap>
            <v-flex xs12  pa-4>
                <v-text-field
                        label="Password"
                        :rules="passwordRules"
                        v-model="user.password"
                        required
                        type="password"
                        :error-messages="errors.password"
                        @change="errors.password=null"
                ></v-text-field>
            </v-flex>
            <v-flex xs12  pa-4>
                <v-text-field
                        :rules="passwordConfirmRules"
                        label="Confirm Password"
                        v-model="user.password_confirmation"
                        required
                        type="password"
                        :error-messages="errors.password_confirmation"
                        @change="errors.password_confirmation=null"
                ></v-text-field>
            </v-flex>
        </v-layout>
        <v-btn large block color="success" @click.native="resetPassword" :disabled="$root.isLoading">
            <span>Reset Password</span>
        </v-btn>
    </v-form>


</template>
<script>
    export default {
        data() {
            return {
                valid:false,
                emailRules: [
                    (v) => !!v || 'Email is required',
                    (v) => v && v.length <= 175 || 'Email must be less than 175 characters'
                ],
                passwordRules:[
                    (v) => !!v || 'A Password is required',
                ],
                passwordConfirmRules: [
                    (v) => !!v || 'You must confirm your password',
                    (v) => v && v === this.user.password || 'Passwords Must Match'
                ],
                errors:
                    {
                        email:null,
                        password:null,
                        password_confirmation:null,
                        token:null
                    },
                user:{
                    email:null,
                    password:null,
                    password_confirmation:null,
                    token:null

                },
            }
        },
        methods: {
            resetPassword() {
                if(this.$refs.form.validate())
                {
                    this.$root.isLoading=true;
                    this.$root.auth.resetPassword(this.user.email, this.user.password, this.user.password_confirmation, this.token)
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
           if (this.$route.query.token)
           {//make sure there is a token oresent
               this.token = this.$route.query.token;
           }
           else
           {//redirect if no token
               this.$router.push({ name: 'forgotPassword' })
           }

        },
    }

</script>