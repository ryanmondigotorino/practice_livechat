<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use App\Models\BaseModel;

class Message_request extends BaseModel{
    use SoftDeletes;
    protected $fillable = [
        'from_id',
        'to_id',
        'status'
    ];

    public static function rules($input = null){
        $id = isset($input['id']) ? $input['id'] : null;
        $data['rules'] = [
            'from_id' => [ 'required' ],
            'to_id' => [ 'required'],
            'status' => [ 'required'],
        ];

        $data['messages'] = [
            'from_id.required' => 'from_id is required.',
            'to_id.required' => 'to_id is required.',
        ];

        return $data;
    }

    public static function getInstance($data){
        return ( isset($data['id']) ) ? Message_request::find($data['id']) : new Message_request() ;
    }
}
