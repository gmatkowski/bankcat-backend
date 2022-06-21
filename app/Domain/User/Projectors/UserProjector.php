<?php
/**
 * User: gmatk
 * Date: 21.06.2022
 * Time: 10:01
 */

namespace App\Domain\User\Projectors;

use App\Application\Repositories\RoleRepository;
use App\Application\Repositories\UserRepository;
use App\Domain\User\Entities\User;
use App\Application\Events\User\UserCreated;
use App\Application\Events\User\UserRoleChanged;
use App\Application\Events\User\UserVerified;
use Illuminate\Support\Facades\DB;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;
use function event;

/**
 *
 */
class UserProjector extends Projector
{

    /**
     * @param UserRepository $userRepository
     * @param RoleRepository $roleRepository
     */
    public function __construct(private UserRepository $userRepository, private RoleRepository $roleRepository)
    {

    }

    /**
     * @param UserCreated $event
     */
    public function onUserCreated(UserCreated $event): void
    {
        DB::transaction(function () use ($event) {
            $user = new User();
            $user->id = $event->getDto()->getUuid();
            $user->email = $event->getDto()->getEmail();
            $user->password = $event->getDto()->getPassword();
            $user->first_name = $event->getDto()->getFirstName();
            $user->last_name = $event->getDto()->getLastName();
            $user->save();

            $roleId = $event->getDto()->getRoleId();

            if (!$roleId) {
                $role = $this->roleRepository->getDefaultRole();
                $roleId = $role?->getKey();
            }

            if ($roleId) {
                event(
                    new UserRoleChanged($user->getKey(), $roleId)
                );
            }
        });
    }

    /**
     * @param UserRoleChanged $event
     */
    public function onUserRoleChanged(UserRoleChanged $event): void
    {
        /**
         * @var User $user
         */
        $user = $this->userRepository->find($event->getUserId());
        $user->syncRoles([$event->getRoleId()]);
    }

    /**
     * @param UserVerified $event
     */
    public function onUserVerified(UserVerified $event): void
    {
        /**
         * @var User $user
         */
        $user = $this->userRepository->find($event->getUserId());
        if (!$user) {
            return;
        }

        $user->markEmailAsVerified();
    }

    /**
     *
     */
    public function onStartingEventReplay(): void
    {
        User::truncate();
    }
}
