<template>
    <v-form lazy-validation v-model="valid" ref="form" >
        <header class="text-xs-center">
            <h1>
                Password Management
            </h1>
        </header>
        <v-layout row wrap>
            <v-flex xs12 pa-4>
                <v-text-field
                        prepend-inner-icon="lock"
                        label="CURRENT Password"
                        v-model="old_password"
                        required
                        type="password"
                        :rules="required"
                        :error-messages="errors.old_password"
                        @change="errors.old_password=null"
                ></v-text-field>
            </v-flex>

            <v-flex xs12 pa-4>
                <v-text-field
                        prepend-inner-icon="lock"
                        label="NEW Password"
                        :rules="passwordRules"
                        v-model="new_password"
                        required
                        type="password"
                        :error-messages="errors.new_password"
                        @change="errors.new_password=null"
                ></v-text-field>
            </v-flex>

            <v-flex xs12 pa-4>
                <v-text-field
                        prepend-inner-icon="lock"
                        label="Confirm NEW Password"
                        :rules="passwordConfirmRules"
                        v-model="new_password_confirmation"
                        required
                        type="password"
                        :error-messages="errors.new_password_confirmation"
                        @change="errors.new_password_confirmation=null"
                ></v-text-field>
            </v-flex>

        </v-layout>

        <v-btn large block color="success" @click.native="changePassword" :disabled="$root.isLoading">
            <span>Change Password</span>
        </v-btn>
    </v-form>


</template>
<script>
    export default {
        data() {
            return {
                valid:false,
                old_password:null,
                new_password:null,
                new_password_confirmation:null,
                required:[
                    (v) => !!v || 'This field is required',
                ],
                passwordRules:[
                    (v) => !!v || 'This field is required',
                    (v) => v!==this.password || 'Your new password can\'t be the same as your old password.',
                ],
                passwordConfirmRules:[
                    (v) => !!v || 'This field is required',
                    (v)=> (v===this.new_password) || 'Password confirmation must match your NEW password.',
                ],
                errors:
                    {
                        old_password:null,
                        new_password:null,
                        new_password_confirmation:null,
                    },
            }
        },
        methods: {
            /**
             * Validates the form, then makes an API call (using our global API call function, $root.makeAPICall) to reset the user's password
             */
            changePassword()
            {
                if(this.$refs.form.validate()) {

                    this.$root.makeAPICall('changePassword',{
                        old_password:this.old_password,
                        new_password:this.new_password,
                        new_password_confirmation:this.new_password_confirmation
                    })
                        .then((result)=>{
                            if(result.success)
                            {//if the operation was successful, reset the form
                                this.$refs.form.reset();
                            }
                            else {
                                //it was not successful, so figure it ooout.
                                this.errors.old_password = result.errors.old_password;
                                this.errors.new_password = result.errors.new_password;
                                this.errors.new_password_confirmation = result.errors.new_password_confirmation;
                            }
                        });
                }
                else
                {
                    this.$root.displayNotification('Please check the form for errors and try again.','red');
                }
            },
        }
    }

</script>