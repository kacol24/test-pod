<div class="modal fade" id="tokopediaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="row g-0 m-0">
                    <div class="col-md">
                        <img src="{{ asset('images/tokopedia-connect-banner.jpeg') }}"
                             class="img-fluid w-100">
                    </div>
                    <div class="col-md-4 position-relative">
                        <button type="button" class="btn-close position-absolute"
                                data-bs-dismiss="modal" aria-label="Close"
                                style="top: 15px;right: 15px;">
                        </button>
                        <div class="p-3 h-100 w-100">
                            <h3 class="modal-title">
                                Tokopedia Store
                            </h3>
                            <div class="tab-content" id="myTabContent"
                                 x-data="{
                                    tab: 0
                                }">
                                <div class="tab-pane fade" id="home" role="tabpanel"
                                     :class="tab === 0 ? 'show active' : ''">
                                    <ol class="mb-4">
                                        <li>
                                            Log in to your Tokopedia Seller account.
                                            Don’t have a Seller account?
                                        </li>
                                        <li>
                                            Check your store name right beside the profile picture in your app.
                                        </li>
                                        <li>
                                            Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.
                                        </li>
                                        <li>
                                            Velit officia consequat duis enim velit mollit. Exercitation veniam
                                            consequat sunt nostrud amet et ulam mastodus.
                                        </li>
                                        <li>
                                            Velit officia consequat duis enim velit mollit. Exercitation veniam
                                            consequat sunt nostrud amet et ulam mastodus.
                                        </li>
                                    </ol>
                                    <hr>
                                    <strong>
                                        <small>
                                            Please make sure you’ve read and follow the instruction before connecting
                                            for a smooth integration.
                                        </small>
                                    </strong>
                                    <a href="#" class="btn btn-primary mt-3"
                                       @click.prevent="tab = 1">
                                        Got it, continue
                                    </a>
                                    <div class="text-center my-3">
                                        Or new to Tokopedia?
                                    </div>
                                    <a href="" class="btn btn-dark w-100">
                                        Create account
                                    </a>
                                </div>
                                <div class="tab-pane fade" id="profile"
                                     :class="tab === 1 ? 'show active' : ''">
                                    <form action="{{ route('stores.store') }}" method="post">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="storename" class="text-uppercase text-color:black">
                                                Enter Store Domain Name
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text pe-0">tokopedia.com/</span>
                                                <input type="text" class="form-control ps-0" name="storename" id="storename"
                                                       placeholder="storename">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <button class="btn btn-primary w-100" type="submit">
                                                Connect
                                            </button>
                                        </div>
                                    </form>
                                    <hr>
                                    <a href="#" class="text-decoration-none"
                                       @click.prevent="tab = 0">
                                        <i class="fas fa-arrow-left"></i>
                                        Back
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
