<div class="card p-0 card--widget h-100" id="top-buyer-container">
    <div class="card-header d-flex align-items-center border-0">
        <img src="{{asset('backend/images/icons/Icon_topbuyer.png')}}" alt="" class="card-title-icon img-fluid">
        <h5 class="card-title">
            Top 10 Buyers
        </h5>
    </div>
    <table class="table m-0">
        <thead>
        <tr class="text-uppercase">
            <th scope="col"></th>
            <th scope="col">Name</th>
            <th scope="col">Purchase (IDR)</th>
            <th scope="col">Transactions</th>
        </tr>
        </thead>
        <tbody>
            @foreach($top_buyers as $top_buyer)
            <tr>
                <th scope="row">
                    <div class="avatar avatar--xs mx-auto {{$top_buyer->gender}}">
                        {{get_initials($top_buyer->name)}}
                    </div>
                </th>
                <td>{{$top_buyer->name}}</td>
                <td>IDR {{number_format($top_buyer->amount,0,",",".")}}</td>
                <td class="text-center">{{$top_buyer->transactions}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
