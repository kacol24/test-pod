<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\Store;
use App\Models\StoreReferral;
use App\Models\TeamInvitation;
use App\Models\User;
use App\Scopes\CurrentStoreScope;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    public function invited($inviteId)
    {
        $invitation = TeamInvitation::withoutGlobalScope(CurrentStoreScope::class)
                                    ->findOrFail($inviteId);

        return view('auth.register_invited', compact('invitation'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  RegisterRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request)
    {
        \DB::beginTransaction();
        $user = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'password'    => Hash::make($request->password),
            'description' => $request->description,
            'what_to_do'  => $request->what_to_do,
        ]);
        $store = Store::create([
            'storename' => $request->username,
            'ref_code'  => Store::generateRefCode(),
        ]);
        $store->users()->attach($user->id, [
            'role_id' => User::ROLE_ID_SUPER_ADMIN,
        ]);
        if (session(StoreReferral::REF_SESSION_KEY)) {
            $upline = Store::where('ref_code', session(StoreReferral::REF_SESSION_KEY))->first();
            if ($upline->downlines->count() < Store::MAX_DOWNLINE) {
                StoreReferral::create([
                    'ref_id'     => $upline->id,
                    'store_id'   => $store->id,
                    'expired_at' => today()->addDays(StoreReferral::EXPIRED_THRESHOLD),
                ]);
            }
        }
        \DB::commit();

        event(new Registered($user));

        Auth::login($user, $remember = true);
        auth()->user()->update([
            'last_login_at' => now(),
        ]);
        session([Store::SESSION_KEY => $store]);
        session()->forget(StoreReferral::REF_SESSION_KEY);

        return redirect()->route('verification.notice');
    }

    public function storeInvited(Request $request, $inviteId)
    {
        $invitation = TeamInvitation::withoutGlobalScope(CurrentStoreScope::class)
                                    ->findOrFail($inviteId);

        \DB::beginTransaction();
        $user = User::create([
            'name'        => $request->name,
            'email'       => $invitation->email,
            'phone'       => $request->phone,
            'password'    => Hash::make($request->password),
            'description' => $request->description,
            'what_to_do'  => $request->what_to_do,
        ]);
        $invitation->store->users()->attach($user->id, [
            'role_id'   => $invitation->role_id,
            'joined_at' => now(),
        ]);
        $invitation->delete();

        event(new Registered($user));

        Auth::login($user, $remember = true);
        auth()->user()->update([
            'last_login_at' => now(),
        ]);
        session([Store::SESSION_KEY => $invitation->store]);

        \DB::commit();

        return redirect()->route('verification.notice');
    }
}
