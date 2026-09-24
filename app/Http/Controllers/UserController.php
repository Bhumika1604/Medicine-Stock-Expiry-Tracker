<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Admin-only user management. Gated by the 'role:admin' middleware on its
 * routes in routes/web.php — this controller does not need to re-check
 * roles itself, but destroy()/toggleStatus() add a couple of safety checks
 * (can't lock yourself out / delete the last admin).
 */
class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::query()
            ->when($request->get('search'), fn ($q, $term) => $q->where(fn ($w) => $w
                ->where('name', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%")))
            ->when($request->get('role'), fn ($q, $role) => $q->where('role', $role))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        return view('users.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        User::create($request->safe()->only(['name', 'email', 'role']) + [
            'password' => $request->validated('password'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    public function update(StoreUserRequest $request, User $user): RedirectResponse
    {
        if ($user->id === auth()->id() && $request->input('role') !== User::ROLE_ADMIN) {
            return back()->with('error', 'You cannot remove your own admin role.')->withInput();
        }

        $data = $request->safe()->only(['name', 'email', 'role']);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->filled('password')) {
            $data['password'] = $request->validated('password');
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Defensive check: only reachable if a future code path allows a
        // non-self-admin deletion to bring the admin count to zero. Under the
        // current 'role:admin' route gate this can't happen via the UI (the
        // acting user is always an admin distinct from the target here), but
        // it's kept as a safety net against regressions.
        if ($user->isAdmin() && User::where('role', User::ROLE_ADMIN)->count() <= 1) {
            return back()->with('error', 'Cannot delete the last remaining admin account.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
