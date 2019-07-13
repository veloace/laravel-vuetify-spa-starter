class AuthService {

    constructor() {
        this.booted = false;
        this.user = {//list out the object prototype (even though we don't have to) just to be clear.
            'is_authenticated': false,
            'name': null,
            'is_email_verified': false,
            'pending_notifications': 0,
            'unread_messages': 0,
            'preferences': {},
        };
    }
    login(username,password)
    {
        return  axios.post('/webAPI/login',{
            email:username,
            password:password,
        })
            .then((response)=>{
                this.user.is_authenticated=true;
                this.user.name=response.data.name;
                this.user.is_email_verified=response.data.is_email_verified;
                return {
                    'success':true,
                    'data':null,
                    'message':"Hi " + this.user.name + "! Welcome Back!"
                };
            })
            .catch((error)=>{
                let errorMessage = '';
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
                        errorMessage="We couldn't find an account with those credentials. Please check your email and password and try again.";
                        break;
                    default:
                        errorMessage="ERROR: Please try refreshing the page and trying again. Please contact us if the issue persists.";
                }

                return {

                    'success':false,
                    'data':null,
                    'errors':error.response.data.errors || null,
                    'message':errorMessage

                };
            })
    }


    logout()
    {
        axios.post('/webAPI/logout',{
        })
            .then(()=>{
                window.location.reload();
            })
            .catch((error)=>{
                window.location.reload();
            })
    }

    register(name, email, password, password_confirmation,recaptchaToken=null)
    {

      return  axios.post('/webAPI/register',{
            name:name,
            email:email,
            password:password,
            password_confirmation:password_confirmation,
            'recaptcha_token':recaptchaToken
        })
            .then((response)=>{
                this.user.is_authenticated=true;
                this.user.name=response.data.name;
                this.user.is_email_verified=response.data.is_email_verified;
                return {
                    'success':true,
                    'data':null,
                    'message':"Welcome to our app, " + this.user.name + "!"
                };
            })
            .catch((error)=>{
                let errorMessage = '';
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
                        errorMessage="Some of the information you provided is not correct. Please check the form and try again.";
                        break;
                    default:
                        errorMessage="ERROR: Please try refreshing the page and trying again. Please contact us if the issue persists.";
                }

                return {

                    'success':false,
                    'data':null,
                    'errors':error.response.data.errors || null,
                    'message':errorMessage

                };
            })
    }
    sendPasswordResetEmail(email)
    {
        return  axios.post('/webAPI/password/email',{
            email:email,
        })
            .then((response)=>{
                return {
                    'success':true,
                    'data':null,
                    'message':'A link was sent to your email ('+email+') with instructions on resetting your password.'
                };
            })
            .catch((error)=>{
                let errorMessage = '';
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
                        errorMessage="We couldn't find an account with those credentials. Please check the email address you entered and try again.";
                        break;
                    default:
                        errorMessage="ERROR: Please try refreshing the page and trying again. Please contact us if the issue persists.";
                }

                return {

                    'success':false,
                    'data':null,
                    'errors':error.response.data.errors || null,
                    'message':errorMessage

                };
            })
    }

    resetPassword(email,password,password_confirmation,token)
    {
        return  axios.post('/webAPI/password/reset',{
            email:email,
            password:password,
            password_confirmation:password_confirmation,
            token:token,
        })
            .then((response)=>{
                this.user.is_authenticated=true;
                this.user.name=response.data.name;
                this.user.is_email_verified=response.data.is_email_verified;
                return {
                    'success':true,
                    'data':null,
                    'message':'Your password has been reset!'
                };
            })
            .catch((error)=>{
                let errorMessage = '';
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
                        errorMessage="It looks like we ran into an error while trying to reset your password.";
                        break;
                    default:
                        errorMessage="ERROR: Please try refreshing the page and trying again. Please contact us if the issue persists.";
                }

                return {

                    'success':false,
                    'data':null,
                    'errors':null,
                    'message':errorMessage+" "+ error.response.data.error

                };
            })
    }

     boot()
    {
        return new Promise((resolve, reject) => {
            if (!this.booted) {
                //if this a
                window.axios.get('/webAPI/user')
                    .then((response) => {
                        this.booted = true;
                        this.user.is_authenticated = true;
                        this.user.name = response.data.name;
                        this.user.is_email_verified = response.data.is_email_verified;
                        resolve(this);
                    })
                    .catch(() => {
                        this.user.is_authenticated = false;
                        this.user.name = null;
                        this.booted = true;
                        resolve(this);
                    })
            }//if not booted
            else
            {
                resolve(this);
            }

        });
    }//boot

}

const auth  = new AuthService();//global auth service
export default auth;