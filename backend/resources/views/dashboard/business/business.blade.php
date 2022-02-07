<div class="container container--app pb-2">
    <div class="row">
        <div class="col-md-6">
            <div class="card p-0 card--widget">
                @include('dashboard.business.business_gender', ['chart' => $gender])
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-0 card--widget">
                @include('dashboard.business.business_company_type', ['chart' => $company_types])
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card p-0 card--widget">
                @include('dashboard.business.business_company_size', ['chart' => $company_sizes])
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-0 card--widget">
                @include('dashboard.business.ownership', ['chart' => $ownership])
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-0 card--widget">
                @include('dashboard.business.established_since', ['chart' => $established_since])
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card p-0 card--widget">
                @include('dashboard.business.location', ['chart' => $location])
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-0 card--widget">
                @include('dashboard.business.category', ['chart' => $category])
            </div>
        </div>
    </div>
    <div class="card p-0 card--widget mt-4">
        <div class="card-header d-flex align-items-center">
            <span class="Avatar border-0 mr-3" style="background: #00C187;">
                <i class="fas fa-trophy fa-fw"></i>
            </span>
            <h5 class="card-title">
                Leaderboard
            </h5>
        </div>
        <div class="card-body py-3 px-0">
            <table data-toggle="table"
                   data-classes="table table-borderless">
                <thead>
                <tr>
                    <th>NAMA</th>
                    <th data-sortable="true">VISITOR</th>
                    <th data-sortable="true">BUTTON CLICKS</th>
                    <th data-sortable="true">CONTACT CLICKS</th>
                    <th data-sortable="true">COUPON CLICKS</th>
                </tr>
                </thead>
                <tbody>
                @foreach($top5 as $business)
                    <tr>
                        <td>
                            {{ $business->name }}
                        </td>
                        <td>
                            {{ $business->visitors_count }}
                        </td>
                        <td>
                            {{ $business->button_clicks_count }}
                        </td>
                        <td>
                            {{ $business->contact_clicks_count }}
                        </td>
                        <td>
                            {{ $business->coupon_clicks_count }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $('[data-toggle=table]').bootstrapTable();
</script>
