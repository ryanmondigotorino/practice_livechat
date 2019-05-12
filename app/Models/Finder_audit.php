<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use App\Models\BaseModel;

class Finder_audit extends BaseModel{
    use SoftDeletes;
    protected $fillable = [
        'finder_id',
        'action',
        'ip_address',
        'device',
        'browser',
        'operating_system',
    ];

    public static function rules($input = null){
        $id = isset($input['id']) ? $input['id'] : null;
        $data['rules'] = [
            'finder_id' => [ 'required' ],
            'action' => [ 'required'],
        ];

        $data['messages'] = [
            'finder_id.required' => 'finder_id is required.',
            'action.required' => 'action is required.',
        ];

        return $data;
    }

    public static function getInstance($data){
        return ( isset($data['id']) ) ? Finder_audit::find($data['id']) : new Finder_audit() ;
    }
}
