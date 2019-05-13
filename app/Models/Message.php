<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use App\Models\BaseModel;

class Message extends BaseModel{
    use SoftDeletes;
    protected $fillable = [
        'message_request_id',
        'from_id',
        'message'
    ];

    public static function rules($input = null){
        $id = isset($input['id']) ? $input['id'] : null;
        $data['rules'] = [
            'message_request_id' => [ 'required' ],
            'from_id' => [ 'required'],
        ];

        $data['messages'] = [
            'message_request_id.required' => 'message_request_id is required.',
            'from_id.required' => 'from_id is required.',
        ];

        return $data;
    }

    public static function getInstance($data){
        return ( isset($data['id']) ) ? Message::find($data['id']) : new Message() ;
    }
}
