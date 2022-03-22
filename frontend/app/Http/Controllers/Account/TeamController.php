<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\TeamInvitation;

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
}
