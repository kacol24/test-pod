<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\StoreUser;
use App\Models\TeamInvitation;
use Illuminate\Http\Request;

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

    public function update(Request $request, $id)
    {
        $storeUser = StoreUser::where('user_id', $id);
        $storeUser->update([
            'role_id' => $request->role_id,
        ]);

        return back(303)->with('success_delete', 'Success update team member.');
    }

    public function destroy($id)
    {
        $store = Store::find(session(Store::SESSION_KEY)->id);
        $store->users()->detach($id);

        return back(303)->with('success_delete', 'User removed from team member.');
    }
}
