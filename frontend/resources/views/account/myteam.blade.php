@extends('layouts.layout')

@section('content')
    <div class="container"
         x-data="{
            delete_url: '',
            delete_email: '',
            delete_role: '',

            update_url: '',
            update_role: '',
            update_email: ''
         }">
        @if (session('success_delete'))
            <x-alert type="success" dismissible icon>
                {{ session('success_delete') }}
            </x-alert>
        @endif
        <div class="row">
            <div class="col-md-4">
                @include('partials.account-sidebar')
            </div>
            <div class="col-md-8">
                <div class="card p-0">
                    <div class="card-header p-4">
                        <h3 class="card-title">
                            My Members
                        </h3>
                        <small class="card-subtitle">
                            You can define roles for several users/admins in your account
                        </small>
                    </div>
                    <div class="list-group order-list">
                        @foreach($members as $member)
                            @continue($member->id == auth()->id())
                            <div class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <dl>
                                            <dt>
                                                {{ $member->name }}
                                            </dt>
                                            <dd>
                                                {{ $member->email }}
                                            </dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-4">
                                        <dl>
                                            <dt>
                                                {{ $member->role_name }}
                                            </dt>
                                            <dd>
                                                Last login:
                                                {{ optional($member->last_login_at)->format('d M Y, H:i A') ?? '-' }}
                                            </dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" disabled
                                                   checked>
                                            <label class="form-check-label">
                                                Active
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <a href="#modalEditMember" class="text-decoration-none text-color:black me-3"
                                           data-bs-toggle="modal"
                                           @click="update_role='{{ $member->role_id }}';update_url='{{ route('myteam.update', $member->id) }}'; update_email='{{ $member->email }}'">
                                            <i class="ri-edit-line ri-fw ri-lg"></i>
                                        </a>
                                        <a href="#modalDelete" class="text-decoration-none text-color:black"
                                           data-bs-toggle="modal"
                                           @click="delete_email='{{ $member->email }}'; delete_role='{{ $member->role_name }}'; delete_url='{{ route('myteam.destroy', $member->id) }}'">
                                            <i class="ri-delete-bin-line ri-fw ri-lg"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @foreach($invites as $invite)
                            <div class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <dl>
                                            <dt>
                                                -
                                            </dt>
                                            <dd>
                                                {{ $invite->email }}
                                            </dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-4">
                                        <dl>
                                            <dt>
                                                {{ $invite->role_name }}
                                            </dt>
                                            <dd>
                                                Invited:
                                                {{ $invite->created_at->format('d M Y, H:i A') ?? '-' }}
                                            </dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="d-flex align-items-center">
                                            <i class="ri-time-line ri-fw ri-lg align-middle me-2 text-color:blue"></i>
                                            <span class=" font-size:12 fw-500">
                                                Pending
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <a href="#modalDelete" class="text-decoration-none text-color:black"
                                           data-bs-toggle="modal"
                                           @click="delete_email='{{ $invite->email }}'; delete_role='{{ $invite->role_name }}';delete_url='{{ route('myteam.destroy_invite', $invite->id) }}'">
                                            <i class="ri-delete-bin-line ri-fw ri-lg"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="card-footer p-md-4 border-top-0">
                        <a href="#modalAddMember" data-bs-toggle="modal"
                           class="d-flex rounded text-decoration-none font-size:16 fw-500 justify-content-center align-items-center text-color:black"
                           style="height: 85px;border: 1px dashed #D8D8D8;">
                            <div style="width: 45px;height: 45px;background-color:rgba(22, 101, 216, .1);"
                                 class="rounded-circle d-flex align-items-center justify-content-center text-color:blue-1 me-3">
                                <i class="ri-add-line ri-fw align-middle"></i>
                            </div>
                            Add Member
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalEditMember" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm"
                 style="max-width: 385px">
                <form :action="update_url" method="post">
                    @csrf
                    @method('put')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">
                                Update User
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-4">
                                <label for="email_modal" class="text-uppercase text-color:black">
                                    Email Address
                                </label>
                                <input type="email" class="form-control" id="email_modal" name="email" required
                                       disabled x-model="update_email">
                            </div>
                            <div class="mb-4">
                                <label class="text-uppercase text-color:black">
                                    Assign A Role
                                </label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="role_id" required
                                           x-model="update_role"
                                           id="role_admin" value="{{ App\Models\User::ROLE_ID_ADMIN }}">
                                    <label class="form-check-label" for="role_admin">
                                        Admin
                                        <span class="d-block font-size:12 fw-400">
                                    Allowed to view all pages, but unable to edit Account Settings.
                                </span>
                                    </label>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="role_id" required
                                           x-model="update_role"
                                           id="role_designer" value="{{ App\Models\User::ROLE_ID_DESIGNER }}">
                                    <label class="form-check-label" for="role_designer">
                                        Designer
                                        <span class="d-block font-size:12 fw-400">
                                            Allowed to view and edit product pages only
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="role_id" required
                                           x-model="update_role"
                                           id="role_finance" value="{{ App\Models\User::ROLE_ID_FINANCE }}">
                                    <label class="form-check-label" for="role_finance">
                                        Finance
                                        <span class="d-block font-size:12 fw-400">
                                            Allowed to view order pages only
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary d-block w-100" :disabled="!email || !role">
                                Update
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center p-md-5">
                        <img src="{{ asset('images/inky-question-1-1.png') }}" alt="" style="max-width: 180px"
                             class="img-fluid mx-auto">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Are you sure you want to remove this user?
                        </h5>
                        <table class="mx-auto text-start font-size:12 my-3">
                            <tr>
                                <th class="pe-3">Email</th>
                                <td x-text="delete_email"></td>
                            </tr>
                            <tr>
                                <th class="pe-3">Role</th>
                                <td x-text="delete_role"></td>
                            </tr>
                        </table>
                        <hr>
                        <p class="m-0 font-size:12">
                            Once removed, they will have no access to this account unless you invite them again.
                        </p>
                        <div class="d-flex justify-content-center mt-3">
                            <form :action="delete_url" method="post">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn px-5 btn-primary me-2" data-bs-dismiss="modal">
                                    Remove
                                </button>
                            </form>
                            <button type="button" class="btn px-5 btn-outline-primary" data-bs-dismiss="modal">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalAddMember" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" style="max-width: 385px">
            <form action="{{ route('myteam.invite') }}" method="post">
                @csrf
                <div class="modal-content"
                     x-data="{
                        email: '',
                        role: ''
                     }">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Invite User
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-4">
                            <label for="email_modal" class="text-uppercase text-color:black">
                                Email Address
                            </label>
                            <input type="email" class="form-control" id="email_modal" name="email" required
                                   x-model="email">
                        </div>
                        <div class="mb-4">
                            <label class="text-uppercase text-color:black">
                                Assign A Role
                            </label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="role_id" required x-model="role"
                                       id="role_admin" value="{{ App\Models\User::ROLE_ID_ADMIN }}">
                                <label class="form-check-label" for="role_admin">
                                    Admin
                                    <span class="d-block font-size:12 fw-400">
                                    Allowed to view all pages, but unable to edit Account Settings.
                                </span>
                                </label>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="role_id" required x-model="role"
                                       id="role_designer" value="{{ App\Models\User::ROLE_ID_DESIGNER }}">
                                <label class="form-check-label" for="role_designer">
                                    Designer
                                    <span class="d-block font-size:12 fw-400">
                                    Allowed to view and edit product pages only
                                </span>
                                </label>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="role_id" required x-model="role"
                                       id="role_finance" value="{{ App\Models\User::ROLE_ID_FINANCE }}">
                                <label class="form-check-label" for="role_finance">
                                    Finance
                                    <span class="d-block font-size:12 fw-400">
                                    Allowed to view order pages only
                                </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary d-block w-100" :disabled="!email || !role">
                            Invite
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="modalSuccessAdd" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-sm modal-dialog-centered" style="max-width: 385px">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{ asset('images/inky-tew-message-1.png') }}" alt="" style="max-width: 180px"
                         class="img-fluid mx-auto">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Invitation sent!
                    </h5>
                    <p class="m-0 font-size:12">
                        An invitation was sent to <strong class="d-inline">{{ session('status') }}</strong>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary d-block w-100" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalFailed" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-sm modal-dialog-centered" style="max-width: 385px">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{ asset('images/inky-danger-1-2.png') }}" alt="" style="max-width: 180px"
                         class="img-fluid mx-auto">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Something's wrong
                    </h5>
                    <p class="m-0 font-size:12">
                        Uh-oh, we couldn’t send the invitation at the moment. Please check the email address or try
                        again later.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary d-block w-100" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var successModal = new bootstrap.Modal(document.getElementById('modalSuccessAdd'));
        var failedModal = new bootstrap.Modal(document.getElementById('modalFailed'));
        @if(session('status'))
        successModal.show();
        @endif
        @if($errors->any())
        failedModal.show();
        @endif
    </script>
@endpush
