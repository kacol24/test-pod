<div class="card-header d-flex align-items-center border-0">
    <img src="{{asset('backend/images/icons/icon_topcities.png')}}" alt="" class="card-title-icon img-fluid">
    <h5 class="card-title">
        Top 10 Cities
    </h5>
</div>
<table class="table m-0">
    <thead>
    <tr class="text-uppercase">
        <th scope="col">City</th>
        <th scope="col" class="text-right">Transactions</th>
    </tr>
    </thead>
    <tbody>
        @foreach($top_cities as $top_city)
        <tr>
            <td>{{$top_city->city}}</td>
            <td class="text-right">{{$top_city->transactions}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
