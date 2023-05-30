<?php

namespace App\Actions\Fortify;

use App\Models\AllowedEmail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        $allowed_emails = AllowedEmail::pluck('email')->toArray();

        Validator::make($input, [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(User::class)
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'editor' => ['boolean'],
        ])->validate();

        if($this->isHtwkAdress(request('email'))){

        } else {
            abort(402, 'Es sind nur HTWK-Adressen erlaubt!');
        }

        $role = 'enduser';

        if($input['editor']) {
            if(in_array( $input['email'], $allowed_emails)){
                $role = 'creator';
            } else{
                abort(402, 'Für ihre E-mail adresse sind keine Bearbeitungsrechte erlaubt!');
            }
        }

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role' => $role,
        ]);
    }

    private function isHtwkAdress($email){
        if(preg_match("/@htwk-leipzig\.de$/",$email) || preg_match("/@stud\.htwk-leipzig\.de$/",$email)){
            return true;
        } else {
            return false;
        }
    }
}
