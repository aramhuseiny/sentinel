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

interface CheckpointInterface
{
    /**
     * Checkpoint after a user is logged in. Return false to deny persistence.
     *
     * @param \Hedi\Sentinel\Users\UserInterface $user
     *
     * @return bool
     */
    public function login(UserInterface $user): bool;

    /**
     * Checkpoint for when a user is currently stored in the session.
     *
     * @param \Hedi\Sentinel\Users\UserInterface $user
     *
     * @return bool
     */
    public function check(UserInterface $user): bool;

    /**
     * Checkpoint for when a failed login attempt is logged. User is not always
     * passed and the result of the method will not affect anything, as the
     * login failed.
     *
     * @param \Hedi\Sentinel\Users\UserInterface|null $user
     *
     * @return bool
     */
    public function fail(UserInterface $user = null): bool;
}
