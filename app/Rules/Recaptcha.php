<?php

namespace App\Rules;

use GuzzleHttp\Client;
use Illuminate\Contracts\Validation\Rule;

class Recaptcha implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //
        if(config('app.registration_requires_captcha'))
        {//check if the user has decided to use reCaptcha.
            //if they have, then we will check that for errors
            if(!empty($value))
            {
                return $this->validateRecaptcha($value);
            }

            return false;//default value
        }
        else
        {
            //recaptcha is not required, so return passing no matter what
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The Google Recaptcha was not valid or not present on the request.';
    }


    /**
     * @return bool
     * @param string $recaptcha
     * @return bool
     */
    protected function validateRecaptcha($recaptcha)
    {
        $grecaptchaClient = new Client(['base_uri' => 'https://www.google.com']);

        try {
            $gresponse = $grecaptchaClient->request('POST', '/recaptcha/api/siteverify', ['form_params' => [
                'secret' => config('app.RECAPTCHA_SECRET_KEY'),
                'response' => $recaptcha
            ]]);
            return (json_decode($gresponse->getBody())->success);
        } catch (\Exception $e) {
            return false;
        }
    }
}
