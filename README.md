#Laravel Vuetify SPA Starter Pack

This is a complete starter pack to get you up and running with a Vue-based SPA (Single Page Application) including authentication, Google Analytics integration, optional ReCaptcha Integration, and an API interface to use Laravel route names from within the Vue code itself. 

This is designed to be a simple, easy-to-use package for most users and does not have much for state management (we don't use Vuex in this project.) If you are looking for a package for a large, complicated app, this may not be the right starter pack for you.

## Installation

Clone this repository to your project folder. Use [composer](https://getcomposer.org) to install the PHP/Laravel dependencies and [NPM](https://www.npmjs.com/) or [Yarn](https://yarnpkg.com/) to install the JavaScript dependencies.

```bash
pip install foobar
```

## Usage


###The .ENV file (for front- and backend)


#### Using ReCaptcha

By default, this starter pack will NOT protect the registration form with reCAPTCHA. However, all the scaffolding is in place so that you can enable [invisible reCAPTCHA](https://developers.google.com/recaptcha/docs/invisible) on the front- and back-end entirely through the .env file.

Change (or add) the variables as followed to the .env file to allow reCAPTCHA to work properly:

```dotenv
REQUIRE_RECAPTCHA=true#boolean value to determine whether or not to validate the reCaptcha on the registration request
MIX_RECAPTCHA=<your_site_key>#the site key from the Google developer console. Publicly visible when on your website as it is in the Vue code.
GOOGLE_RECAPTCHA_SECRET_KEY=<your_secret_key>#The secret server-side key that only you can see (not publicly visible)
```

###Registering New Users (ReCaptcha or not to ReCaptcha)

###Making an API call from Vue

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)