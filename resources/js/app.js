/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');
import auth from './utils/auth';
import 'babel-polyfill'
import VueRouter from 'vue-router';
import router  from './router';
import Vuetify from 'vuetify'
import VueAnalytics from 'vue-analytics'
import Vuelidate from 'vuelidate'
import InvisibleRecaptcha from 'vue-invisible-recaptcha';

window.Vue = require('vue');
let ga = process.env.MIX_GOOGLE_ANALYTICS_SITE;
let recaptcha = process.env.MIX_RECAPTCHA;
if(ga)
{
    window.Vue.use(VueAnalytics, {
        id: process.env.MIX_GOOGLE_ANALYTICS_SITE,
        checkDuplicatedScript: true,
        router
    });
}

if(recaptcha)
{
    Vue.component('invisible-recaptcha', InvisibleRecaptcha);
}

window.Vue.use(Vuetify);
window.Vue.use(VueRouter);
window.Vue.use(Vuelidate);

window.appName =process.env.MIX_APP_NAME;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

auth.boot()//make sure auth service is booted before we load vue or vue router
    .then(()=>{
        const app = new Vue({
            el: '#app',
            router,
            data: () => ({
                recaptcha:recaptcha,
                drawer: null,
                appName:window.appName,
                apiRoutes:window.apiRoutes,
                isLoading:false,
                notification:{
                    show:false,
                    content:null,
                    timeout:6000,
                    color:'primary'
                },
                auth:auth
            }),
            methods:{

                /**
                 * Global function to make an API call
                 *
                 * @param routeName the route name as it is in Laravel
                 * @param data nullable the data to send to the API
                 * @param displayLoader
                 * @param displayPopoverOnSuccess
                 * @param displayPopoverOnFail
                 * @returns {*}
                 * @param successMessage
                 * @param errorMessage
                 * @returns {*}
                 */
                makeAPICall(routeName, data=null, displayLoader=true, displayPopoverOnSuccess=true, displayPopoverOnFail=true, successMessage=null, errorMessage=null)
                {

                    if(displayLoader)
                    {//only display loader is calling method asked us to
                        this.isLoading = true;
                    }
                    //
                    let route = this.apiRoutes[routeName];
                    if(route)
                    {
                        //Laravel has to fake PUT, PATCH, AND DELETE using the _method field, the next 4 lines take care of that
                        let method = (route.method ==='GET') ? 'GET' : 'POST';
                        if(route.method==='PUT' || route.method==='PATCH'|| route.method==='DELETE')
                        {
                            data['_method'] =  route.method;
                        }

                        //lets make the API call, and return promise to caller
                        return axios({
                            method: method,
                            url: route.url,
                            data: data
                        })
                            .then((response)=> {
                                this.isLoading = false;
                                //
                                //display success message if required
                                if(displayPopoverOnSuccess)
                                {
                                    if(!successMessage)
                                    {
                                        successMessage = "All Done!";
                                    }
                                    this.displayNotification(successMessage,'green',5000)
                                }
                                return {
                                    'success':true,
                                    'data':response.status!==204 ? response.data:null
                                };
                            })
                            .catch((error)=>{
                                this.isLoading=false;

                                if(error.response)
                                {
                                    if (displayPopoverOnFail)
                                    {//only display error  if user request
                                        if(!errorMessage)
                                        {//if the user didn't specify the error message, choose one to display

                                            switch(error.response.status)
                                            {
                                                case 401:
                                                case 403:
                                                    errorMessage = "ERROR: You are not authorized to perform this action.";
                                                    break;
                                                case 404:
                                                    errorMessage="ERROR: Could not find the requested resource.";
                                                    break;
                                                case 419://bad CSRF token; solved by page refresh
                                                    errorMessage="ERROR: Please try refreshing the page and then try again.";
                                                    break;
                                                case 422://validation errors
                                                    errorMessage="Whoops! Looks like some of the data your provided is incorrect. Please check the form and try again.";
                                                    break;
                                                default:
                                                    errorMessage="ERROR: Please try refreshing the page and trying again. Please contact us if the issue persists.";
                                            }
                                        }

                                        this.displayNotification(errorMessage,'red',5000)

                                    }
                                    return {
                                        'success':false,
                                        'data':null,
                                        'errors':error.response.data.errors || null
                                    };
                                }
                                else
                                {
                                    throw 'Error occurred outside of API call!'+error;
                                }

                            })


                    }
                    else
                    {
                        this.isLoading=false;
                        throw "Route not found";
                    }
                },
                displayNotification(content, color='primary',timeout=6000)
                {
                    this.notification.content=content;
                    this.notification.color=color;
                    this.notification.timeout=timeout;
                    this.notification.show=true;

                }
            }
        });

    });

