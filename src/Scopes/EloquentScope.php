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

use Hedi\Sentinel\Roles\EloquentRole;
use IteratorAggregate;
use Illuminate\Database\Eloquent\Model;
use Hedi\Sentinel\Users\EloquentUser;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EloquentScope extends Model implements ScopeInterface
{
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
        'slug',
        'model_class',
        'scope_active'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

    /**
     * The Users model FQCN.
     *
     * @var string
     */
    protected static $usersModel = EloquentUser::class;

    /**
     * The Role model FQCN.
     *
     * @var string
     */
    protected static $rolesModel = EloquentRole::class;

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
        return $this->belongsToMany(static::$usersModel, 'role_users', 'scope_id', 'user_id')
            ->withPivot('role_id', 'scope_values')->withTimestamps();
    }

    /**
     * The Roles relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(static::$rolesModel, 'role_users', 'scope_id', 'role_id')
            ->withPivot('user_id', 'scope_values')->withTimestamps();
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
    public function getScopeSlug(): string
    {
        return $this->slug;
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
    public function getRoles(): IteratorAggregate
    {
        return $this->roles;
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
    public static function getRolesModel(): string
    {
        return static::$rolesModel;
    }

    /**
     * {@inheritdoc}
     */
    public static function setUsersModel(string $usersModel): void
    {
        static::$usersModel = $usersModel;
    }

    /**
     * {@inheritdoc}
     */
    public static function setRolesModel(string $rolesModel): void
    {
        static::$rolesModel = $rolesModel;
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

        return parent::__call($method, $parameters);
    }
}
