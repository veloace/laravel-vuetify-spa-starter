<template>

    <v-form lazy-validation v-model="valid" ref="form" >
        <header class="text-xs-center">
            <h1>
                Forgot Your Password?
            </h1>
            <p>Please enter the email address used to sign up for {{$root.appName}}. If you have an account with us, we will send an email to your inbox with instructions on how to reset your password.</p>
        </header>
        <v-layout row wrap>
            <v-flex xs12 pa-4>
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
        </v-layout>

        <v-btn large block color="success" @click.native="resetPassword" :disabled="$root.isLoading">
            <span>Reset Password</span>
        </v-btn>
        <p class="text-xs-center"> <v-btn :to="{name:'login'}" color="success" flat >Back to Login</v-btn></p>

    </v-form>


</template>
<script>
    export default {
        data() {
            return {
                valid:false,
                email:null,
                emailRules: [
                    (v) => !!v || 'Email is required',
                ],
                errors:
                    {
                        email:null,
                    },
            }
        },
        methods: {
            resetPassword() {
                if(this.$refs.form.validate())
                {
                    this.$root.isLoading=true;
                    this.$root.auth.sendPasswordResetEmail(this.email)
                        .then((result)=>{
                            if(result.success)
                            {//if successful
                                this.$root.isLoading=false;
                                this.$root.displayNotification(result.message,'success');
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