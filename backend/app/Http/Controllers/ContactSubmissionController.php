<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Resources\ContactSubmission;
use App\Models\ContactForm;
use Illuminate\Http\Request;

class ContactSubmissionController extends Controller
{
    public function index()
    {
        $data = [
            'page_title' => 'Contact Submissions',
            'active'     => 'contact_submissions',
        ];

        return view('contact_submissions/list', $data);
    }

    public function datatable(Request $request)
    {
        $search = $request->search;
        $sorting = 'updated_at';
        $order = $request->order;

        if ($request->has('sort')) {
            $sorting = $request->sort;
        }

        return ContactSubmission::collection(
            ContactForm::when(
                ! empty($search),
                function ($query) use ($search) {
                    return $query->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
                })->orderBy($sorting, $order)->paginate($request->limit)
        );
    }

    public function delete(Request $request)
    {
        if (is_array($request->input('ids'))) {
            ContactForm::whereIn('id', $request->input('ids'))->delete();
        } else {
            ContactForm::whereIn('id', json_decode($request->input('ids'), true))->delete();
        }

        if ($request->input('back_url')) {
            return redirect($request->input('back_url'));
        }
    }
}
