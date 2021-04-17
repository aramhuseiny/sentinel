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
class UserPositions extends Model
{
	protected $table = 'user_positions';
	protected $primaryKey = ['position_id','user_id'];
	public $timestamps = false;

	protected $fillable = [
        'position_id',
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
