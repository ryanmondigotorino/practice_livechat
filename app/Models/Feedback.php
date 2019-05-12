<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use App\Models\BaseModel;

class Feedback extends BaseModel{
    use SoftDeletes;
    protected $fillable = [
        'finder_id',
        'content',
    ];

    public static function rules($input = null){
        $id = isset($input['id']) ? $input['id'] : null;
        $data['rules'] = [
            'finder_id' => [ 'required' ],
            'content' => [ 'required'],
        ];

        $data['messages'] = [
            'finder_id.required' => 'finder_id is required.',
            'content.required' => 'Content is required.',
        ];

        return $data;
    }

    public static function getInstance($data){
        return ( isset($data['id']) ) ? Feedback::find($data['id']) : new Feedback() ;
    }
}
