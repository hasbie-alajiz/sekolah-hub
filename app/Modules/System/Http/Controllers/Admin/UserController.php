<?php

declare(strict_types=1);

namespace App\Modules\System\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\System\Actions\CreateUserAction;
use App\Modules\System\Actions\UpdateUserAction;
use App\Modules\System\Actions\DeleteUserAction;
use App\Modules\System\Http\Requests\StoreUserRequest;
use App\Modules\System\Http\Requests\UpdateUserRequest;
use Spatie\Permission\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        private CreateUserAction $createUserAction,
        private UpdateUserAction $updateUserAction,
        private DeleteUserAction $deleteUserAction
    ) {
    }

    public function index(): View
    {
        Gate::authorize('viewAny', User::class);

        $users = User::with('roles')->paginate(10);

        return view('system::admin.users.index', compact('users'));
    }

    public function create(): View
    {
        Gate::authorize('create', User::class);

        $roles = Role::all();

        return view('system::admin.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->createUserAction->execute($request->validated());

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $user = User::findOrFail($id);
        Gate::authorize('update', $user);

        $roles = Role::all();

        return view('system::admin.users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, int $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        Gate::authorize('update', $user);

        $this->updateUserAction->execute($user, $request->validated());

        return redirect()->route('admin.users.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        Gate::authorize('delete', $user);

        $this->deleteUserAction->execute($user);

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
