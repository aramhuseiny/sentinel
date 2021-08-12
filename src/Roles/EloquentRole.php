<?php

/*
 * Part of the Sentinel package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Sentinel
 * @version    5.1.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011-2020, Cartalyst LLC
 * @link       https://cartalyst.com
 */

namespace Hedi\Sentinel\Roles;

use Hedi\Sentinel\Scopes\EloquentScopes;
use IteratorAggregate;
use Illuminate\Database\Eloquent\Model;
use Hedi\Sentinel\Users\EloquentUser;
use Hedi\Sentinel\Permissions\PermissibleTrait;
use Hedi\Sentinel\Permissions\PermissibleInterface;
use Hedi\Sentinel\Permissions\PermissionsInterface;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EloquentRole extends Model implements PermissibleInterface, RoleInterface
{
    use PermissibleTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'permissions',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'permissions' => 'json',
    ];

    /**
     * The Scopes model FQCN.
     *
     * @var string
     */
    protected static $scopesModel = EloquentScopes::class;

    /**
     * {@inheritdoc}
     */
    public function delete()
    {
        if ($this->exists && (! method_exists(static::class, 'isForceDeleting') || $this->isForceDeleting())) {
            $this->users()->detach();
        }

        return parent::delete();
    }

    /**
     * The Users relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    /*public function users(): BelongsToMany
    {
        return $this->belongsToMany(static::$usersModel, 'role_users', 'role_id', 'user_id')->withTimestamps();
    }*/
    /**
     * The Users relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function scopes(): BelongsToMany
    {
        return $this->belongsToMany(static::$scopesModel, 'role_scopes', 'role_id', 'scope_id')->withTimestamps();
    }

    /**
     * {@inheritdoc}
     */
    public function getRoleId(): int
    {
        return $this->getKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getRoleSlug(): string
    {
        return $this->slug;
    }

    /**
     * Dynamically pass missing methods to the permissions.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $methods = ['hasAccess', 'hasAnyAccess'];

        if (in_array($method, $methods)) {
            $permissions = $this->getPermissionsInstance();

            return call_user_func_array([$permissions, $method], $parameters);
        }

        return parent::__call($method, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    protected function createPermissions(): PermissionsInterface
    {
        return new static::$permissionsClass($this->getPermissions());
    }

    /**
     * {@inheritdoc}
     */
    public function getScopes(): IteratorAggregate
    {
        return $this->scopes;
    }

    /**
     * {@inheritdoc}
     */
    public static function getScopesModel(): string
    {
        return static::$scopesModel;
    }

    /**
     * {@inheritdoc}
     */
    public static function setScopesModel(string $scopesModel): void
    {
        static::$scopesModel = $scopesModel;
    }
}
