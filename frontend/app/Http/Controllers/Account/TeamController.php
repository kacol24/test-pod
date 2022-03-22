<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\TeamInvitation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TeamController extends Controller
{
    public function index()
    {
        $members = Store::find(session(Store::SESSION_KEY)->id)->users;
        $invites = TeamInvitation::all();

        $data = [
            'members' => $members,
            'invites' => $invites,
        ];

        return view('account.myteam', $data);
    }

    public function invite(Request $request)
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
        $currentStore->teamInvitations()->create([
            'email'   => $request->email,
            'role_id' => $request->role_id,
        ]);

        // send invitation email

        return back()->withStatus($request->email);
    }

    public function destroyInvite($id)
    {
        $invite = TeamInvitation::findOrFail($id);
        $invite->delete();

        return back();
    }
}
