<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Mail\TeamInvitation as MailInvitation;
use App\Models\Store;
use App\Models\TeamInvitation;
use App\Models\User;
use App\Scopes\CurrentStoreScope;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TeamInvitationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                Rule::unique('team_invitations')->where(function ($query) {
                    $query->where('store_id', session(Store::SESSION_KEY)->id);
                }),
            ],
        ]);

        $currentStore = Store::find(session(Store::SESSION_KEY)->id);
        $invitation = $currentStore->teamInvitations()->create([
            'email'   => $request->email,
            'role_id' => $request->role_id,
        ]);

        \Mail::to($request->email)->send(new MailInvitation($invitation));

        return back()->withStatus($request->email);
    }

    public function destroy($id)
    {
        $invite = TeamInvitation::findOrFail($id);
        $invite->delete();

        return back(303);
    }

    public function accept(Request $request, $invitationId)
    {
        $invitation = TeamInvitation::withoutGlobalScope(CurrentStoreScope::class)
                                    ->findOrFail($invitationId);

        $user = User::where('email', $invitation->email)->firstOrFail();

        \DB::beginTransaction();
        $invitation->store->users()->attach($user->id, [
            'role_id'   => $invitation->role_id,
            'joined_at' => now(),
        ]);
        $invitation->delete();
        \DB::commit();

        return redirect()->route('dashboard');
    }
}
