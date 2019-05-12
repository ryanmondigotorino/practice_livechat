<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use App\Models\BaseModel;

class Finder extends BaseModel{
    use SoftDeletes;
    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'image',
        'email',
        'address',
        'birthdate',
        'contact_num',
        'gender',
        'username',
        'password',
        'account_line',
        'account_status',
    ];

    public static function rules($input = null){
        $id = isset($input['id']) ? $input['id'] : null;
        $data['rules'] = [
            'firstname' => [ 'required','regex:/^[a-zA-Z]+$/u','min:3' ],
            'lastname' => [ 'required','regex:/^[a-zA-Z]+$/u','min:3' ],
            'email' => [ 'required', 'email', Rule::unique('admins')->ignore($id), Rule::unique('finders')->ignore($id)],
            'gender' => [ 'required'],
            'birthdate' => [ 'required'],
            'username' => [ 'required', Rule::unique('admins')->ignore($id), Rule::unique('finders')->ignore($id)],
        ];

        $data['messages'] = [
            'username.required' => 'Username is required.',
            'username.unique' => 'This Username is already in use..',
            'email.unique' => 'This Email is already in use.',
        ];

        return $data;
    }

    public static function getInstance($data){
        return ( isset($data['id']) ) ? Finder::find($data['id']) : new Finder() ;
    }
}
