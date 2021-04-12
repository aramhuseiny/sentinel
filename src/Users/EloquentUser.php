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

namespace Hedi\Sentinel\Users;

use Hedi\Sentinel\Positions\EloquentPositions;
use Hedi\Sentinel\Positions\PositionableInterface;
use Hedi\Sentinel\Positions\PositionInterface;
use Hedi\Sentinel\Roles\RoleableInterface;
use Illuminate\Database\Eloquent\Collection;
use IteratorAggregate;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Hedi\Sentinel\Reminders\EloquentReminder;
use Hedi\Sentinel\Throttling\EloquentThrottle;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Hedi\Sentinel\Permissions\PermissibleTrait;
use Hedi\Sentinel\Activations\EloquentActivation;
use Hedi\Sentinel\Permissions\PermissibleInterface;
use Hedi\Sentinel\Permissions\PermissionsInterface;
use Hedi\Sentinel\Persistences\EloquentPersistence;
use Hedi\Sentinel\Persistences\PersistableInterface;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EloquentUser extends Model implements PermissibleInterface, PersistableInterface, PositionableInterface, UserInterface
{
    use PermissibleTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
//    protected $fillable = config('sentinel.userattributes');
    protected $fillable = [
        'email',
        'password',
        'last_name',
        'first_name',
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
     * {@inheritdoc}
     */
    protected $hidden = [
        'password',
    ];

    /**
     * {@inheritdoc}
     */
    protected $persistableKey = 'user_id';

    /**
     * {@inheritdoc}
     */
    protected $persistableRelationship = 'persistences';

    /**
     * Array of login column names.
     *
     * @var array
     */
//    protected $loginNames = config('sentinel.login_names');
    protected $loginNames = ['email','username'];

    /**
     * The Positions model FQCN.
     *
     * @var string
     */
    protected static $positionsModel = EloquentPositions::class;

    /**
     * The Persistences model FQCN.
     *
     * @var string
     */
    protected static $persistencesModel = EloquentPersistence::class;

    /**
     * The Activations model FQCN.
     *
     * @var string
     */
    protected static $activationsModel = EloquentActivation::class;

    /**
     * The Reminders model FQCN.
     *
     * @var string
     */
    protected static $remindersModel = EloquentReminder::class;

    /**
     * The Throttling model FQCN.
     *
     * @var string
     */
    protected static $throttlingModel = EloquentThrottle::class;

    /**
     * Returns the activations relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activations(): HasMany
    {
        return $this->hasMany(static::$activationsModel, 'user_id');
    }

    /**
     * Returns the persistences relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function persistences(): HasMany
    {
        return $this->hasMany(static::$persistencesModel, 'user_id');
    }

    /**
     * Returns the reminders relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reminders(): HasMany
    {
        return $this->hasMany(static::$remindersModel, 'user_id');
    }

    /**
     * Returns the roles relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function positions(): BelongsToMany
    {
        return $this->belongsToMany(static::$positionsModel, 'user_positions', 'user_id', 'position_id')->withTimestamps();
    }

    /**
     * Returns the roles relationship.
     *
     * @return Collection|\Illuminate\Support\Collection
     */
    public function roles()
    {
        $roles = [];
        $positions = $this->positions;
        foreach ( $positions as $position)
        {
            $roles[] = $position->getRoles();
        }

        return collect($roles);
//        return $this->belongsToMany(static::$positionsModel, 'role_users', 'user_id', 'role_id')->withTimestamps();
    }

    /**
     * Returns the throttle relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function throttle(): HasMany
    {
        return $this->hasMany(static::$throttlingModel, 'user_id');
    }

    /**
     * Returns an array of login column names.
     *
     * @return array
     */
    public function getLoginNames(): array
    {
        return $this->loginNames;
    }

    /**
     * {@inheritdoc}
     */
    public function generatePersistenceCode(): string
    {
        return Str::random(32);
    }

    /**
     * {@inheritdoc}
     */
    public function getUserId(): int
    {
        return $this->getKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getPersistableId(): string
    {
        return $this->getKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getPersistableKey(): string
    {
        return $this->persistableKey;
    }

    /**
     * {@inheritdoc}
     */
    public function setPersistableKey(string $key): void
    {
        $this->persistableKey = $key;
    }

    /**
     * {@inheritdoc}
     */
    public function getPersistableRelationship(): string
    {
        return $this->persistableRelationship;
    }

    /**
     * {@inheritdoc}
     */
    public function setPersistableRelationship(string $persistableRelationship): void
    {
        $this->persistableRelationship = $persistableRelationship;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserLogin(): string
    {
        return $this->getAttribute($this->getUserLoginName());
    }

    /**
     * {@inheritdoc}
     */
    public function getUserLoginName(): string
    {
        return reset($this->loginNames);
    }

    /**
     * {@inheritdoc}
     */
    public function getUserPassword(): string
    {
        return $this->password;
    }

    /**
     * Returns the roles model.
     *
     * @return string
     */
    public static function getPositionsModel(): string
    {
        return static::$positionsModel;
    }

    /**
     * Sets the roles model.
     *
     * @param string $positionsModel
     *
     * @return void
     */
    public static function setPositionsModel(string $positionsModel): void
    {
        static::$positionsModel = $positionsModel;
    }

    /**
     * Returns the persistences model.
     *
     * @return string
     */
    public static function getPersistencesModel()
    {
        return static::$persistencesModel;
    }

    /**
     * Sets the persistences model.
     *
     * @param string $persistencesModel
     *
     * @return void
     */
    public static function setPersistencesModel(string $persistencesModel): void
    {
        static::$persistencesModel = $persistencesModel;
    }

    /**
     * Returns the activations model.
     *
     * @return string
     */
    public static function getActivationsModel(): string
    {
        return static::$activationsModel;
    }

    /**
     * Sets the activations model.
     *
     * @param string $activationsModel
     *
     * @return void
     */
    public static function setActivationsModel(string $activationsModel): void
    {
        static::$activationsModel = $activationsModel;
    }

    /**
     * Returns the reminders model.
     *
     * @return string
     */
    public static function getRemindersModel(): string
    {
        return static::$remindersModel;
    }

    /**
     * Sets the reminders model.
     *
     * @param string $remindersModel
     *
     * @return void
     */
    public static function setRemindersModel(string $remindersModel): void
    {
        static::$remindersModel = $remindersModel;
    }

    /**
     * Returns the throttling model.
     *
     * @return string
     */
    public static function getThrottlingModel(): string
    {
        return static::$throttlingModel;
    }

    /**
     * Sets the throttling model.
     *
     * @param string $throttlingModel
     *
     * @return void
     */
    public static function setThrottlingModel(string $throttlingModel): void
    {
        static::$throttlingModel = $throttlingModel;
    }

    /**
     * {@inheritdoc}
     */
    public function delete()
    {
        $isSoftDeletable = property_exists($this, 'forceDeleting');

        $isSoftDeleted = $isSoftDeletable && ! $this->forceDeleting;

        if ($this->exists && ! $isSoftDeleted) {
            $this->activations()->delete();
            $this->persistences()->delete();
            $this->reminders()->delete();
            $this->positions()->detach();
            $this->throttle()->delete();
        }

        return parent::delete();
    }

    /**
     * Dynamically pass missing methods to the user.
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
     * Creates a permissions object.
     *
     * @return \Hedi\Sentinel\Permissions\PermissionsInterface
     */
    protected function createPermissions(): PermissionsInterface
    {
        $userPermissions = $this->getPermissions();

        $rolePermissions = [];

        foreach ( $this->positions as $position){
            foreach ($position->roles as $role) {
                $rolePermissions[] = $role->getPermissions();
            }
        }


        return new static::$permissionsClass($userPermissions, $rolePermissions);
    }

    public function getPositions(): IteratorAggregate
    {
        return $this->positions;
    }

    public function inPosition($position): bool
    {
        if ($position instanceof PositionInterface) {
            $positionId = $position->getPositionId();
        }

        foreach ($this->positions as $instance) {
            if ($position instanceof PositionInterface) {
                if ($instance->getPositionId() === $positionId) {
                    return true;
                }
            } else {
                if ($instance->getPositionId() == $position) {
                    return true;
                }
            }
        }

        return false;
    }

    public function inAnyPosition(array $positions): bool
    {
        foreach ($positions as $position) {
            if ($this->inPosition($position)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $role
     * @return bool
     */
    public function inRole( $role ) : bool
    {
        $has_role = false;
        $positions = $this->positions;

        foreach ( $positions as $position)
        {
            $has_role = $position->inRole($role);
            if($has_role == true ) {
                return true;
            }
        }
        return $has_role;
    }
}
