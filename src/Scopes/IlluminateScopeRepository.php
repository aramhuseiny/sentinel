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

use Cartalyst\Support\Traits\RepositoryTrait;

class IlluminateScopeRepository implements ScopeRepositoryInterface
{
    use RepositoryTrait;

    /**
     * The Eloquent role model FQCN.
     *
     * @var string
     */
    protected $model = EloquentScope::class;

    /**
     * Create a new Illuminate role repository.
     *
     * @param string $model
     *
     * @return void
     */
    public function __construct(string $model = null)
    {
        $this->model = $model;
    }

    /**
     * {@inheritdoc}
     */
    public function findById(int $id): ?ScopeInterface
    {
        return $this->createModel()->newQuery()->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function findBySlug(string $slug): ?ScopeInterface
    {
        return $this->createModel()->newQuery()->where('slug', $slug)->first();
    }

    /**
     * {@inheritdoc}
     */
    public function findByName(string $name): ?ScopeInterface
    {
        return $this->createModel()->newQuery()->where('name', $name)->first();
    }
}
