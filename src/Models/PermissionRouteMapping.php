<?php

/**
 * Created by Hedi
 */

namespace Hedi\Sentinel\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PermissionRouteMapping
 *
 * @property int $id
 * @property int|null $id_saas_module_list
 * @property string|null $permission
 * @property string|null $route
 * @property string|null $method
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $mapping_active
 *
 * @package App\Models
 */
class PermissionRouteMapping extends Model
{
	protected $table = 'permission_route_mapping';

	protected $casts = [
		'id_saas_module_list' => 'int',
		'mapping_active' => 'int'
	];

	protected $fillable = [
		'id_saas_module_list',
		'permission',
		'route',
		'method',
		'mapping_active'
	];
}
