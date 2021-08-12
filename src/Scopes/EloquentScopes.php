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

namespace Hedi\Sentinel\Scopes;

use Hedi\Sentinel\Permissions\PermissibleInterface;
use Hedi\Sentinel\Permissions\PermissionsInterface;
use Hedi\Sentinel\Roles\EloquentRole;
use Hedi\Sentinel\Roles\RoleableInterface;
use Hedi\Sentinel\Roles\RoleInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use IteratorAggregate;
use Illuminate\Database\Eloquent\Model;
use Hedi\Sentinel\Users\EloquentUser;
use Hedi\Sentinel\Permissions\PermissibleTrait;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EloquentScopes extends Model implements PermissibleInterface, ScopeInterface, RoleableInterface
{

    use PermissibleTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'scopes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'mapped_model',
        'parent',
    ];


    /**
     * The Users model FQCN.
     *
     * @var string
     */
    protected static $usersModel = EloquentUser::class;


    /**
     * The Roles model FQCN.
     *
     * @var string
     */
    protected static $rolesModel = EloquentRole::class;

    /**
     * The scopes model FQCN.
     *
     * @var string
     */
    protected static $scopeModel = EloquentScopes::class;

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
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(static::$usersModel, 'user_scopes', 'scope_id', 'user_id')->withTimestamps();
    }

    /**
     * {@inheritdoc}
     */
    public function getScopeId(): int
    {
        return $this->getKey();
    }


    /**
     * {@inheritdoc}
     */
    public function getUsers(): IteratorAggregate
    {
        return $this->users;
    }

    /**
     * {@inheritdoc}
     */
    public static function getUsersModel(): string
    {
        return static::$usersModel;
    }

    /**
     * {@inheritdoc}
     */
    public static function setUsersModel(string $usersModel): void
    {
        static::$usersModel = $usersModel;
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
    public function getRoles(): IteratorAggregate
    {
        return $this->roles;
    }


    /**
     * Returns the roles relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(static::$rolesModel, 'role_scopes', 'scope_id', 'role_id')->withTimestamps();
    }

    /**
     * {@inheritdoc}
     */
    public function inRole($role): bool
    {
        if ($role instanceof RoleInterface) {
            $roleId = $role->getRoleId();
        }

        foreach ($this->roles as $instance) {
            if ($role instanceof RoleInterface) {
                if ($instance->getRoleId() === $roleId) {
                    return true;
                }
            } else {
                if ($instance->getRoleId() == $role || $instance->getRoleSlug() == $role) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function inAnyRole(array $roles): bool
    {
        foreach ($roles as $role) {
            if ($this->inRole($role)) {
                return true;
            }
        }

        return false;
    }

    public function getParent(): HasOne
    {
        return $this->hasOne( EloquentScopes::$scopeModel, 'id', 'parent');

    }

    protected function createPermissions(): PermissionsInterface
    {
        $scopePermissions = $this->getPermissions();

        $rolePermissions = [];

        foreach ($this->roles as $role) {
            $rolePermissions[] = $role->getPermissions();
        }

        return new static::$permissionsClass($scopePermissions, $rolePermissions);
    }
}
