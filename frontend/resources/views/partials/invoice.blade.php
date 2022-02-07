<div class="row justify-content-between align-items-center mb-4">
    <div class="col-md-auto">
        <h1 class="page-title d-flex align-items-center">
            <span class="me-4">Order INV 20203364</span>
            <small class="font-size:12">
                <span class="badge badge-success d-inline-block p-0" style="width: 6px;height: 6px;"></span>
                Completed
            </small>
        </h1>
    </div>
    <div class="col-md-auto d-print-none">
        <a href="{{ route('orderdetail.print', 1) }}" class="btn btn-default font-size:12 fw-500"
           target="_blank">
            <i class="ri-printer-line align-middle me-2"></i>
            Print Invoice
        </a>
    </div>
</div>
<div class="p-0 card">
    <div class="card-header d-flex align-items-center">
        <i class="ri-file-list-line align-middle me-2"></i>
        <h5 class="card-title d-inline-block">
            Customer Details
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="font-size:14 w-100">
                <tr>
                    <th style="width: 150px;">Name</th>
                    <td>
                        Meriana Losert
                    </td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td>
                        Jalan Pemuda No. 18, Jakarta pusat, DKI Jakarta, Indonesia 18827
                    </td>
                </tr>
                <tr>
                    <th>Phone No.</th>
                    <td>
                        +62 8887 444 222
                    </td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>
                        losert@gmail.com
                    </td>
                </tr>
                <tr>
                    <th>Message</th>
                    <td class="text-color:green">
                        Please include a note: “Happy Birthday!”
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="p-0 mt-4 card">
    <div class="card-header d-flex align-items-center">
        <i class="ri-truck-line align-middle me-2"></i>
        <h5 class="card-title d-inline-block">
            Shipping
        </h5>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <table class="font-size:14">
                <tr>
                    <th style="width: 150px;">Courier</th>
                    <td>
                        SiCepat
                    </td>
                </tr>
                <tr>
                    <th>
                        Receipt Number
                    </th>
                    <td>
                        000900281834
                        <a href="javascript:void(0)"
                           class="text-decoration-none" @include('partials.data-copy', ['copyText' => '000900281834'])>
                            <i class="ri-file-copy-line ri-fw text-muted align-middle"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <th>
                        Shipment Date
                    </th>
                    <td>
                        11 Dec 2021, 21:50 WIB
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="p-0 mt-4 card">
    <div class="card-header d-flex align-items-center">
        <i class="ri-shopping-cart-line align-middle me-2"></i>
        <h5 class="card-title d-inline-block">
            Item List
        </h5>
    </div>
    <div class="p-0 card-body">
        <div class="table-responsive">
            <table class="table m-0 table-hover">
                <thead class="text-uppercase">
                <tr>
                    <th class="col-6">ITEMS</th>
                    <th class="text-center col-1">QTY</th>
                    <th class="text-center col-2">PRICE</th>
                    <th class="text-center col-3">SUBTOTAL</th>
                </tr>
                </thead>
                <tbody>
                @foreach(range(1, 4) as $detail)
                    <tr class="text-nowrap">
                        <td>
                            <div class="d-inline-flex">
                                <div class="d-flex">
                                    <img src="{{ asset('images/product-thumbnail.png') }}" alt=""
                                         style="border-radius:4px;width:40px;height:40px;">
                                </div>
                                <div class="d-block ms-3">
                                    <strong style="font-size: 14px;color: #3E3F42;line-height: 22px;">
                                        Donut worry be happy hoodie </strong><br>
                                    <span style="font-size: 12px;color: #3E3F42;line-height: 18px;">
                                                        Colour: Green
                                                    </span><br>
                                    <span style="font-size: 12px;color: #3E3F42;line-height: 18px;">
                                                        Size: Small
                                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="text-end">
                            2
                        </td>
                        <td class="text-end">
                            IDR 100,000
                        </td>
                        <td class="text-end">
                            IDR 200,000
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot class="font-size:14 text-nowrap">
                <tr class="text-end">
                    <th colspan="3" class="border-0 text-color:icon font-weight-normal">Subtotal</th>
                    <td class="border-0">
                        IDR 200,000
                    </td>
                </tr>
                <tr class="text-end">
                    <th colspan="3" class="border-0 text-color:icon font-weight-normal">Promotion</th>
                    <td class="border-0">
                        (-) IDR 10,000
                    </td>
                </tr>
                <tr class="text-end">
                    <th colspan="3" class="border-0 text-color:icon font-weight-normal">
                        Shipping (1kg)
                    </th>
                    <td class="border-0">
                        IDR 10,000
                    </td>
                </tr>
                <tr class="text-end">
                    <th colspan="3" class="border-0 font-weight-bold">Total</th>
                    <td class="border-0 font-weight-bold font-size:16">
                        IDR 200,000
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
