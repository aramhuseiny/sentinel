<?php

/**
 * Created by Hedi
 */

namespace Hedi\Sentinel\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**

 * @package Hedi\Sentinel\Models
 */
class UserScopes extends Model
{
	protected $table = 'user_scopes';
	protected $primaryKey = ['scope_id','user_id'];
	public $timestamps = false;

	protected $fillable = [
        'scope_id',
        'user_id',
	    'mapped_model_list'
    ];


    public function getAttribute($key)
    {
        $attribute = $this->getAttributes();
        $val = $attribute[$key];
        return $val;
	}
}
