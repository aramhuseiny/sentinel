<?php

/**
 * Created by Hedi
 */

namespace Hedi\Sentinel\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SaasModuleList
 *
 * @property int $id_saas_module_list
 *
 * @package App\Models
 */
class SaasModuleList extends Model
{
	protected $table = 'saas_module_list';
	protected $primaryKey = 'id_saas_module_list';
	public $timestamps = false;
}
