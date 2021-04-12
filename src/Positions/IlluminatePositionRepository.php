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

namespace Hedi\Sentinel\Positions;

use Cartalyst\Support\Traits\RepositoryTrait;

class IlluminatePositionRepository implements PositionRepositoryInterface
{
    use RepositoryTrait;

    /**
     * The Eloquent position model FQCN.
     *
     * @var string
     */
    protected $model = EloquentPositions::class;

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
    public function findById(int $id): ?PositionInterface
    {
        return $this->createModel()->newQuery()->find($id);
    }


    /**
     * {@inheritdoc}
     */
    public function findByName(string $name): ?PositionInterface
    {
        return $this->createModel()->newQuery()->where('name', $name)->first();
    }
}
