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
 * @version    6.0.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011-2022, Cartalyst LLC
 * @link       https://cartalyst.com
 */

namespace Hedi\Sentinel\Checkpoints;

use Hedi\Sentinel\Users\UserInterface;
use Hedi\Sentinel\Activations\ActivationRepositoryInterface;

class ActivationCheckpoint implements CheckpointInterface
{
    use AuthenticatedCheckpoint;

    /**
     * The Activations repository instance.
     *
     * @var \Hedi\Sentinel\Activations\ActivationRepositoryInterface
     */
    protected $activations;

    /**
     * Constructor.
     *
     * @param \Hedi\Sentinel\Activations\ActivationRepositoryInterface $activations
     *
     * @return void
     */
    public function __construct(ActivationRepositoryInterface $activations)
    {
        $this->activations = $activations;
    }

    /**
     * {@inheritdoc}
     */
    public function login(UserInterface $user): bool
    {
        return $this->checkActivation($user);
    }

    /**
     * {@inheritdoc}
     */
    public function check(UserInterface $user): bool
    {
        return $this->checkActivation($user);
    }

    /**
     * Checks the activation status of the given user.
     *
     * @param \Hedi\Sentinel\Users\UserInterface $user
     *
     * @throws \Hedi\Sentinel\Checkpoints\NotActivatedException
     *
     * @return bool
     */
    protected function checkActivation(UserInterface $user): bool
    {
        $completed = $this->activations->completed($user);

        if (! $completed) {
            $exception = new NotActivatedException('Your account has not been activated yet.');

            $exception->setUser($user);

            throw $exception;
        }

        return true;
    }
}
