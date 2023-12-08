<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * Our User Instance.
     * @var Authenticatable|User|Model|null
     */
    public Authenticatable|User|Model|null $user;

    /**
     * Auth Model.
     * @var string|Model|User
     */
    public string|Model|User $authModel = User::class;

    /**
     * Guard For Auth.
     * @var string
     */
    public string $guard = 'web';

    /**
     * Broker For Reset Passwords.
     * @var string
     */
    public string $broker = 'users';

    /**
     * Finds and Sets current user to User with username.
     *
     * @param string|Authenticatable|null $user
     * @param array $select
     * @return void
     */
    protected function setUserTo(string|Authenticatable|null $user, array $select = ['*']): void
    {
        // if Not Filled, do nothing.
        if (!filled($user)) {
            $this->user = null;
            return;
        }

        // if instance of our auth model then re-fetch it from DB.
        if ($user instanceof $this->authModel) {
            $this->user = $user->fresh()->makeVisibleIf($select !== ['*'], $select);
            return;
        }

        // Else Query It.
        $this->user = $this->authModel::query()
            ->select($select)
            ->when(
                value: is_email($user),
                callback: fn ($q) => $q->where('email', $user),
                default: fn ($q) => $q->hasPhone($user),
            )
            ->first();
    }
}
