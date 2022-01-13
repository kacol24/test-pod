@extends('layouts.layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                @include('partials.account-sidebar')
            </div>
            <div class="col-md-8">
                <div class="card p-0">
                    <div class="card-header p-4">
                        <h3 class="card-title">
                            My Shipments
                        </h3>
                        <small class="card-subtitle">
                            Teams let you provide other people access to your account so they can help you scale your
                            Teespring business. This page lets you edit your team, manage your team members and assign
                            roles.
                        </small>
                    </div>
                    <div class="list-group order-list">
                        <div class="list-group-item list-group-item-action">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <dl>
                                        <dt>James Suhary</dt>
                                        <dd>jamessuhary@gmail.com</dd>
                                    </dl>
                                </div>
                                <div class="col-md-4">
                                    <dl>
                                        <dt>Super Admin</dt>
                                        <dd>Last login 08 Sep 2018, 14:00 PM</dd>
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
                                    <a href="" class="text-decoration-none text-color:black me-3">
                                        <i class="ri-edit-line ri-fw ri-lg"></i>
                                    </a>
                                    <a href="#modalDelete" class="text-decoration-none text-color:black"
                                       data-bs-toggle="modal">
                                        <i class="ri-delete-bin-line ri-fw ri-lg"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item list-group-item-action">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <dl>
                                        <dt>James Suhary</dt>
                                        <dd>jamessuhary@gmail.com</dd>
                                    </dl>
                                </div>
                                <div class="col-md-4">
                                    <dl>
                                        <dt>Super Admin</dt>
                                        <dd>Last login 08 Sep 2018, 14:00 PM</dd>
                                    </dl>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                               id="flexSwitchCheckDefault">
                                        <label class="form-check-label" for="flexSwitchCheckDefault">
                                            Active
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2 text-end">
                                    <a href="" class="text-decoration-none text-color:black me-3">
                                        <i class="ri-edit-line ri-fw ri-lg"></i>
                                    </a>
                                    <a href="#modalDelete" class="text-decoration-none text-color:black"
                                       data-bs-toggle="modal">
                                        <i class="ri-delete-bin-line ri-fw ri-lg"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer p-md-4">
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
    </div>
    <div class="modal fade" id="modalAddMember" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" style="max-width: 385px">
            <div class="modal-content">
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
                        <input type="email" class="form-control" id="email_modal">
                    </div>
                    <div class="mb-4">
                        <label class="text-uppercase text-color:black">
                            Assign A Role
                        </label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                            <label class="form-check-label" for="flexRadioDefault1">
                                Admin
                                <span class="d-block font-size:12 fw-400">
                                    Allowed to view all pages, but unable to edit Account Settings.
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2">
                            <label class="form-check-label" for="flexRadioDefault2">
                                Designer
                                <span class="d-block font-size:12 fw-400">
                                    Allowed to view and edit product pages only
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault3">
                            <label class="form-check-label" for="flexRadioDefault3">
                                Finance
                                <span class="d-block font-size:12 fw-400">
                                    Allowed to view order pages only
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary d-block w-100"
                            data-bs-toggle="modal" data-bs-target="#modalSuccessAdd">
                        Invite
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalSuccessAdd" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                        An invitation was sent to <strong class="d-inline">dimas@gmail.com</strong>
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
                            <th class="pe-3">Name</th>
                            <td>
                                Dimas Arya
                            </td>
                        </tr>
                        <tr>
                            <th class="pe-3">Email</th>
                            <td>
                                dimas@gmail.com
                            </td>
                        </tr>
                        <tr>
                            <th class="pe-3">Role</th>
                            <td>Admin</td>
                        </tr>
                    </table>
                    <hr>
                    <p class="m-0 font-size:12">
                        Once removed, they will have no access to this account unless you invite them again.
                    </p>
                    <div class="d-flex justify-content-center mt-3">
                        <button type="button" class="btn px-5 btn-primary me-2" data-bs-dismiss="modal">
                            Remove
                        </button>
                        <button type="button" class="btn px-5 btn-outline-primary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
