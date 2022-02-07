@extends('layouts.layout')

@section('page_header')
    <div class="container container--app">
        <div class="row justify-content-between">
            <div class="col-auto d-flex align-items-center">
                <h1 class="page-title m-0">
                    Business Overview
                </h1>
            </div>
            <div class="col">
                <section class="dashboard-filter"
                         x-data="DashboardFilter()"
                         x-init="$watch('currentFilter', function() { $dispatch('filter-date-changed') })"
                         @filter-date-changed.window="$nextTick(function(){ onFilterChanged() })">
                    <div class="d-flex align-items-center justify-content-end">
                        <label class="filter-label m-0 d-none d-md-block">
                            OUTLET:
                        </label>
                        <div class="dropdown ml-md-3">
                            <button class="btn btn-default dropdown-toggle" type="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-store fa-fw"></i>
                                Senayan City
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </div>
                        </div>
                        <button type="button" data-toggle="modal" data-target="#filter_modal"
                                class="btn btn-default ml-3">
                            <i class="fas fa-fw fa-sliders-h fa-rotate-90 mr-0"></i>
                        </button>
                        <div class="d-none d-md-flex align-items-center">
                            <div class="border-right mx-4" style="height: 39px"></div>
                            <ul class="nav nav-pills">
                                <li class="nav-item">
                                    <a class="btn btn-default rounded-pill btn-sm px-3" href="#"
                                       :class="{ 'text-color:blue font-weight-bold': currentFilter == 'week' }"
                                       @click="thisWeek()">
                                        This Week
                                    </a>
                                </li>
                                <li class="nav-item mx-3">
                                    <a class="btn btn-default rounded-pill btn-sm px-3" href="#"
                                       :class="{ 'text-color:blue font-weight-bold': currentFilter == 'month' }"
                                       @click="thisMonth()">
                                        This Month
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="btn btn-default rounded-pill btn-sm px-3" href="#"
                                       :class="{ 'text-color:blue font-weight-bold': currentFilter == 'year' }"
                                       @click="thisYear()">
                                        This Year
                                    </a>
                                </li>
                            </ul>
                            <div class="border-right mx-4" style="height: 39px"></div>
                            <label class="filter-label my-0 mx-2">
                                OR
                            </label>
                            <div class="dropdown ml-3" x-model="startDate">
                                <button class="btn btn-default dropdown-toggle datepicker" type="button"
                                        x-on:datepicker-changed.self="$dispatch('input', $event.detail);console.log($event.detail)">
                                    <i class="fas fa-calendar fa-fw"></i>
                                    <span x-text="startDate">
                                        21-09-2020
                                    </span>
                                </button>
                                <input type="hidden" x-model="startDate">
                            </div>
                            <div class="mx-2 text-center">
                                -
                            </div>
                            <div class="dropdown" x-model="endDate">
                                <button class="btn btn-default dropdown-toggle datepicker" type="button"
                                        x-on:datepicker-changed.self="$dispatch('input', $event.detail)">
                                    <i class="fas fa-calendar fa-fw"></i>
                                    <span x-text="endDate">
                                    28-09-2020
                                </span>
                                    <input type="hidden" x-model="endDate">
                                </button>
                            </div>
                        </div>
                    </div>
                </section>
                @push('scripts')
                    <script>
                        $('.datepicker').datepicker({
                            format: 'dd-mm-yyyy',
                            autoclose: true,
                            todayBtn: true,
                            todayHighlight: true
                        });

                        $('.datepicker').datepicker().on('changeDate', function(e) {
                            var selectedDate = moment(e.date).format('DD-MM-YYYY');
                            var dateChanged = new CustomEvent('datepicker-changed', {
                                detail: selectedDate
                            });
                            this.dispatchEvent(dateChanged);
                        });

                        function DashboardFilter() {
                            return {
                                startDate: moment().format('DD-MM-YYYY'),
                                endDate: moment().format('DD-MM-YYYY'),
                                currentFilter: 'today',
                                thisWeek: function() {
                                    this.currentFilter = 'week';
                                    this.startDate = moment().startOf('week').format('DD-MM-YYYY');
                                    this.endDate = moment().format('DD-MM-YYYY');
                                },
                                thisMonth: function() {
                                    this.currentFilter = 'month';
                                    this.startDate = moment().startOf('month').format('DD-MM-YYYY');
                                    this.endDate = moment().format('DD-MM-YYYY');
                                },
                                thisYear: function() {
                                    this.currentFilter = 'year';
                                    this.startDate = moment().startOf('year').format('DD-MM-YYYY');
                                    this.endDate = moment().format('DD-MM-YYYY');
                                },

                                onFilterChanged: function() {
                                    // any functions that needs to be executed
                                    // after filter changed goes here
                                }
                            };
                        };
                    </script>
                @endpush
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container container--app" id="overview">
        <div class="row mt-4">
            <div class="widget-carousel overflow-hidden w-100">
                <div class="widget-carousel__slide">
                    <div class="widget text-center">
                        <div class="widget__icon mx-auto">
                            <img src="{{ asset('images/icons/icon_gross_income.png') }}" alt="icon gross income"
                                 class="img-fluid">
                        </div>
                        <h3 class="widget__title">
                            GROSS INCOME (IDR)
                        </h3>
                        <div class="widget__body">
                            33,675,000
                        </div>
                        <div class="widget__meta text-color:green">
                            +9.8%
                        </div>
                    </div>
                </div>
                <div class="widget-carousel__slide">
                    <div class="widget text-center">
                        <div class="widget__icon mx-auto">
                            <img src="{{ asset('images/icons/icon_transaction.png') }}" alt="icon transactions"
                                 class="img-fluid">
                        </div>
                        <h3 class="widget__title">
                            TRANSACTIONS
                        </h3>
                        <div class="widget__body">
                            5
                        </div>
                        <div class="widget__meta text-color:red">
                            -11.9%
                        </div>
                    </div>
                </div>
                <div class="widget-carousel__slide">
                    <div class="widget text-center">
                        <div class="widget__icon mx-auto">
                            <img src="{{ asset('images/icons/icon_conversion.png') }}" alt="icon conversion rate"
                                 class="img-fluid">
                        </div>
                        <h3 class="widget__title">
                            CONVERSION RATE
                        </h3>
                        <div class="widget__body">
                            73.67%
                        </div>
                        <div class="widget__meta text-color:green">
                            +12.2%
                        </div>
                    </div>
                </div>
                <div class="widget-carousel__slide">
                    <div class="widget text-center">
                        <div class="widget__icon mx-auto">
                            <img src="{{ asset('images/icons/icon_viewed_purple.png') }}" alt="icon viewed products"
                                 class="img-fluid">
                        </div>
                        <h3 class="widget__title">
                            VIEWED PRODUCTS
                        </h3>
                        <div class="widget__body">
                            4359
                        </div>
                        <div class="widget__meta text-color:green">
                            +19.6%
                        </div>
                    </div>
                </div>
                <div class="widget-carousel__slide">
                    <div class="widget text-center">
                        <div class="widget__icon mx-auto">
                            <img src="{{ asset('images/icons/icon_sold.png') }}" alt="icon sold products"
                                 class="img-fluid">
                        </div>
                        <h3 class="widget__title">
                            SOLD PRODUCTS
                        </h3>
                        <div class="widget__body">
                            895
                        </div>
                        <div class="widget__meta text-color:red">
                            -11.9%
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4">
            <div class="card">
                <div id="product_view_chart"></div>

                @push('scripts')
                    <script>
                        Highcharts.chart('product_view_chart', {

                            title: {
                                text: 'Solar Employment Growth by Sector, 2010-2016'
                            },

                            subtitle: {
                                text: 'Source: thesolarfoundation.com'
                            },

                            yAxis: {
                                title: {
                                    text: 'Number of Employees'
                                }
                            },

                            xAxis: {
                                accessibility: {
                                    rangeDescription: 'Range: 2010 to 2017'
                                }
                            },

                            legend: {
                                layout: 'vertical',
                                align: 'right',
                                verticalAlign: 'middle'
                            },

                            plotOptions: {
                                series: {
                                    label: {
                                        connectorAllowed: false
                                    },
                                    pointStart: 2010
                                }
                            },

                            series: [
                                {
                                    name: 'Installation',
                                    data: [43934, 52503, 57177, 69658, 97031, 119931, 137133, 154175]
                                }, {
                                    name: 'Manufacturing',
                                    data: [24916, 24064, 29742, 29851, 32490, 30282, 38121, 40434]
                                }, {
                                    name: 'Sales & Distribution',
                                    data: [11744, 17722, 16005, 19771, 20185, 24377, 32147, 39387]
                                }, {
                                    name: 'Project Development',
                                    data: [null, null, 7988, 12169, 15112, 22452, 34400, 34227]
                                }, {
                                    name: 'Other',
                                    data: [12908, 5948, 8105, 11248, 8989, 11816, 18274, 18111]
                                }],

                            responsive: {
                                rules: [
                                    {
                                        condition: {
                                            maxWidth: 500
                                        },
                                        chartOptions: {
                                            legend: {
                                                layout: 'horizontal',
                                                align: 'center',
                                                verticalAlign: 'bottom'
                                            }
                                        }
                                    }]
                            }
                        });
                    </script>
                @endpush
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-6 mb-2">
                <div class="card p-0 card--widget">
                    <div class="card-header d-flex align-items-center">
                        <img src="{{ asset('images/icons/icon_bestsellers.png') }}" alt="" class="card-title-icon img-fluid">
                        <h5 class="card-title">
                            Best Sellers
                        </h5>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach(range(5, 1) as $product)
                            <a href="#" class="list-group-item list-group-item-action product-list-item">
                                <div class="product-list-item__content">
                                    <div class="product-list-item__image">
                                        <img src="{{ asset('images/candle.png') }}" alt="product item image" class="img-fluid">
                                    </div>
                                    <div class="product-list-item__info">
                                        <div class="product-list-item__title">
                                            Glass Canister {{ $product }}
                                        </div>
                                        <div class="product-list-item__meta">
                                            IDR 20,000
                                        </div>
                                    </div>
                                </div>
                                <div class="product-list-item__counter">
                                    {{ rand($product * 100, ($product * 100) + 100) }}
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <div class="card-footer text-center">
                        <a href="">
                            See More
                            <i class="fas fa-fw fa-long-arrow-alt-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-2">
                <div class="card p-0 card--widget">
                    <div class="card-header d-flex align-items-center">
                        <img src="{{ asset('images/icons/icon_viewed_blue.png') }}" alt="" class="card-title-icon img-fluid">
                        <h5 class="card-title">
                            Most Viewed Products
                        </h5>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach(range(5, 1) as $product)
                            <a href="#" class="list-group-item list-group-item-action product-list-item">
                                <div class="product-list-item__content">
                                    <div class="product-list-item__image">
                                        <img src="{{ asset('images/candle.png') }}" alt="product item image" class="img-fluid">
                                    </div>
                                    <div class="product-list-item__info">
                                        <div class="product-list-item__title">
                                            Glass Canister {{ $product }}
                                        </div>
                                        <div class="product-list-item__meta">
                                            IDR 20,000
                                        </div>
                                    </div>
                                </div>
                                <div class="product-list-item__counter">
                                    {{ rand($product * 1000, ($product * 1000) + 100) }}
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <div class="card-footer text-center">
                        <a href="">
                            See More
                            <i class="fas fa-fw fa-long-arrow-alt-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="container container--app">
        <div class="row mt-3">
            <div class="col d-flex align-items-center">
                <h2 class="page-title m-0">
                    Sales Data
                </h2>
            </div>
            <div class="col-auto d-none d-md-block">
                <div class="d-flex align-items-center">
                    <label class="filter-label m-0">
                        OUTLET:
                    </label>
                    <div class="dropdown ml-3">
                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Dropdown button
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                    </div>
                    <div class="border-right mx-4" style="height: 39px"></div>
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="btn btn-default rounded-pill py-1 active" href="#">Active</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-default rounded-pill py-1" href="#">Link</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-default rounded-pill py-1" href="#">Link</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-default rounded-pill py-1 disabled" href="#" tabindex="-1"
                               aria-disabled="true">Disabled</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-5 mb-2">
                <div class="card p-0 card--widget">
                    <div class="card-header d-flex align-items-center">
                        <img src="{{ asset('images/icons/icon_bestsellers.png') }}" alt="" class="card-title-icon img-fluid">
                        <h5 class="card-title">
                            Transaction Rate
                            <small class="d-block text-uppercase">
                                <span class="text-color:green">+5.6%</span> Since last period
                            </small>
                        </h5>
                    </div>
                    <div id="transaction_rate_chart"></div>

                    @push('scripts')
                        <script>
                            Highcharts.chart('transaction_rate_chart', {
                                chart: {
                                    type: 'pie'
                                },
                                title: {
                                    text: 'Browser market share, January, 2018'
                                },
                                subtitle: {
                                    text: 'Source: <a href="http://statcounter.com" target="_blank">statcounter.com</a>'
                                },
                                plotOptions: {
                                    pie: {
                                        shadow: false,
                                        center: ['50%', '50%']
                                    }
                                },
                                tooltip: {
                                    valueSuffix: '%'
                                },
                                series: [
                                    {
                                        name: 'Browsers',
                                        data: browserData,
                                        size: '60%',
                                        dataLabels: {
                                            formatter: function() {
                                                return this.y > 5 ? this.point.name : null;
                                            },
                                            color: '#ffffff',
                                            distance: -30
                                        }
                                    }, {
                                        name: 'Versions',
                                        data: versionsData,
                                        size: '80%',
                                        innerSize: '60%',
                                        dataLabels: {
                                            formatter: function() {
                                                // display only if larger than 1
                                                return this.y > 1 ? '<b>' + this.point.name + ':</b> ' +
                                                    this.y + '%' : null;
                                            }
                                        },
                                        id: 'versions'
                                    }],
                                responsive: {
                                    rules: [
                                        {
                                            condition: {
                                                maxWidth: 400
                                            },
                                            chartOptions: {
                                                series: [
                                                    {}, {
                                                        id: 'versions',
                                                        dataLabels: {
                                                            enabled: false
                                                        }
                                                    }]
                                            }
                                        }]
                                }
                            });
                        </script>
                    @endpush
                </div>
            </div>
            <div class="col-md-7 mb-2">
                <div class="card p-0 h-100">
                    <div id="transaction_chart"></div>

                    @push('scripts')
                        <script>
                            Highcharts.chart('transaction_chart', {
                                chart: {
                                    type: 'column'
                                },
                                title: {
                                    text: 'Monthly Average Rainfall'
                                },
                                subtitle: {
                                    text: 'Source: WorldClimate.com'
                                },
                                xAxis: {
                                    categories: [
                                        'Jan',
                                        'Feb',
                                        'Mar',
                                        'Apr',
                                        'May',
                                        'Jun',
                                        'Jul',
                                        'Aug',
                                        'Sep',
                                        'Oct',
                                        'Nov',
                                        'Dec'
                                    ],
                                    crosshair: true
                                },
                                yAxis: {
                                    min: 0,
                                    title: {
                                        text: 'Rainfall (mm)'
                                    }
                                },
                                tooltip: {
                                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                        '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
                                    footerFormat: '</table>',
                                    shared: true,
                                    useHTML: true
                                },
                                plotOptions: {
                                    column: {
                                        pointPadding: 0.2,
                                        borderWidth: 0
                                    }
                                },
                                series: [
                                    {
                                        name: 'Tokyo',
                                        data: [
                                            49.9,
                                            71.5,
                                            106.4,
                                            129.2,
                                            144.0,
                                            176.0,
                                            135.6,
                                            148.5,
                                            216.4,
                                            194.1,
                                            95.6,
                                            54.4]

                                    }, {
                                        name: 'New York',
                                        data: [
                                            83.6,
                                            78.8,
                                            98.5,
                                            93.4,
                                            106.0,
                                            84.5,
                                            105.0,
                                            104.3,
                                            91.2,
                                            83.5,
                                            106.6,
                                            92.3]

                                    }, {
                                        name: 'London',
                                        data: [48.9, 38.8, 39.3, 41.4, 47.0, 48.3, 59.0, 59.6, 52.4, 65.2, 59.3, 51.2]

                                    }, {
                                        name: 'Berlin',
                                        data: [42.4, 33.2, 34.5, 39.7, 52.6, 75.5, 57.4, 60.4, 47.6, 39.1, 46.8, 51.1]

                                    }]
                            });
                        </script>
                    @endpush
                </div>
            </div>
        </div>
    </div>

    <hr>
    <div class="container container--app">
        <div class="row mt-3">
            <div class="col d-flex align-items-center">
                <h2 class="page-title m-0">
                    Customers Data
                </h2>
            </div>
            <div class="col-auto d-none d-md-block">
                <div class="d-flex align-items-center">
                    <label class="filter-label m-0">
                        OUTLET:
                    </label>
                    <div class="dropdown ml-3">
                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Dropdown button
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                    </div>
                    <div class="border-right mx-4" style="height: 39px"></div>
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="btn btn-default rounded-pill py-1 active" href="#">Active</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-default rounded-pill py-1" href="#">Link</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-default rounded-pill py-1" href="#">Link</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-default rounded-pill py-1 disabled" href="#" tabindex="-1"
                               aria-disabled="true">Disabled</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md mb-2">
                <div class="card p-0 card--widget h-100">
                    <div class="card-header d-flex align-items-center border-0">
                        <img src="{{ asset('images/icons/Icon_topbuyer.png') }}" alt="" class="card-title-icon img-fluid">
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
                        @foreach(range(1, 10) as $topBuyers)
                            <tr>
                                <th scope="row">
                                    <div class="avatar avatar--xs mx-auto {{ rand(0, 1) ? 'female' : 'male' }}">
                                        {{ ['A', 'E', 'J', 'M', 'R', 'V'][rand(0,5)] . ['T', 'M', 'H', '', 'O', 'S'][rand(0,5)] }}
                                    </div>
                                </th>
                                <td>Andrew Tanuwijaya</td>
                                <td>10.890.000</td>
                                <td class="text-center">3</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md mb-2">
                <div class="card p-0 card--widget">
                    <div class="card-header d-flex align-items-center">
                        <img src="{{ asset('images/icons/icon_customers.png') }}" alt="" class="card-title-icon img-fluid">
                        <h5 class="card-title">
                            Customers
                            <small class="d-block text-uppercase">
                                <span class="text-color:green">+5.6%</span> Since last period
                            </small>
                        </h5>
                    </div>
                    <div id="customers_chart"></div>

                    @push('scripts')
                        <script>
                            Highcharts.chart('customers_chart', {
                                chart: {
                                    type: 'pie'
                                },
                                title: {
                                    text: 'Browser market share, January, 2018'
                                },
                                subtitle: {
                                    text: 'Source: <a href="http://statcounter.com" target="_blank">statcounter.com</a>'
                                },
                                plotOptions: {
                                    pie: {
                                        shadow: false,
                                        center: ['50%', '50%']
                                    }
                                },
                                tooltip: {
                                    valueSuffix: '%'
                                },
                                series: [
                                    {
                                        name: 'Browsers',
                                        data: browserData,
                                        size: '60%',
                                        dataLabels: {
                                            formatter: function() {
                                                return this.y > 5 ? this.point.name : null;
                                            },
                                            color: '#ffffff',
                                            distance: -30
                                        }
                                    }, {
                                        name: 'Versions',
                                        data: versionsData,
                                        size: '80%',
                                        innerSize: '60%',
                                        dataLabels: {
                                            formatter: function() {
                                                // display only if larger than 1
                                                return this.y > 1 ? '<b>' + this.point.name + ':</b> ' +
                                                    this.y + '%' : null;
                                            }
                                        },
                                        id: 'versions'
                                    }],
                                responsive: {
                                    rules: [
                                        {
                                            condition: {
                                                maxWidth: 400
                                            },
                                            chartOptions: {
                                                series: [
                                                    {}, {
                                                        id: 'versions',
                                                        dataLabels: {
                                                            enabled: false
                                                        }
                                                    }]
                                            }
                                        }]
                                }
                            });
                        </script>
                    @endpush
                    <div class="card-body px-0">
                        <div class="mx-3 my-4">
                            <div class="d-flex justify-content-between">
                                <label for="" class="filter-label">
                                    < 17 Years Old
                                </label>
                                <span>
                                0%
                            </span>
                            </div>
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="0"
                                     aria-valuemin="0"
                                     aria-valuemax="100"></div>
                            </div>
                        </div>
                        <hr>
                        <div class="mx-3 my-4">
                            <div class="d-flex justify-content-between">
                                <label for="" class="filter-label">
                                    < 17 Years Old
                                </label>
                                <span>
                                0%
                            </span>
                            </div>
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="0"
                                     aria-valuemin="0"
                                     aria-valuemax="100"></div>
                            </div>
                        </div>
                        <hr>
                        <div class="mx-3 my-4">
                            <div class="d-flex justify-content-between">
                                <label for="" class="filter-label">
                                    < 17 Years Old
                                </label>
                                <span>
                                0%
                            </span>
                            </div>
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="0"
                                     aria-valuemin="0"
                                     aria-valuemax="100"></div>
                            </div>
                        </div>
                        <hr>
                        <div class="mx-3 my-4">
                            <div class="d-flex justify-content-between">
                                <label for="" class="filter-label">
                                    < 17 Years Old
                                </label>
                                <span>
                                0%
                            </span>
                            </div>
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="0"
                                     aria-valuemin="0"
                                     aria-valuemax="100"></div>
                            </div>
                        </div>
                        <hr>
                        <div class="mx-3 my-4">
                            <div class="d-flex justify-content-between">
                                <label for="" class="filter-label">
                                    < 17 Years Old
                                </label>
                                <span>
                                0%
                            </span>
                            </div>
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="0"
                                     aria-valuemin="0"
                                     aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-auto col-12 mb-2">
                <div class="card p-0 card--widget">
                    <div class="card-header d-flex align-items-center border-0">
                        <img src="{{ asset('images/icons/icon_topcities.png') }}" alt="" class="card-title-icon img-fluid">
                        <h5 class="card-title">
                            Top Cities
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
                        @foreach(range(1, 10) as $topBuyers)
                            <tr>
                                <td>Jakarta</td>
                                <td class="text-right">50</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="text-center my-4">
        <a href="javascript:void(0)" class="text-muted" data-scroll="body">
            Back To Top
            <i class="fas fa-arrow-up fa-fw"></i>
        </a>
    </div>
@endsection

@prepend('scripts')
    <script src="https://cdn.jsdelivr.net/npm/highcharts@8.2.2/highcharts.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker3.standalone.min.css">
    <script>
        var colors = Highcharts.getOptions().colors,
            categories = [
                'Chrome',
                'Firefox',
                'Internet Explorer',
                'Safari',
                'Edge',
                'Opera',
                'Other'
            ],
            data = [
                {
                    y: 62.74,
                    color: colors[2],
                    drilldown: {
                        name: 'Chrome',
                        categories: [
                            'Chrome v65.0',
                            'Chrome v64.0',
                            'Chrome v63.0',
                            'Chrome v62.0',
                            'Chrome v61.0',
                            'Chrome v60.0',
                            'Chrome v59.0',
                            'Chrome v58.0',
                            'Chrome v57.0',
                            'Chrome v56.0',
                            'Chrome v55.0',
                            'Chrome v54.0',
                            'Chrome v51.0',
                            'Chrome v49.0',
                            'Chrome v48.0',
                            'Chrome v47.0',
                            'Chrome v43.0',
                            'Chrome v29.0'
                        ],
                        data: [
                            0.1,
                            1.3,
                            53.02,
                            1.4,
                            0.88,
                            0.56,
                            0.45,
                            0.49,
                            0.32,
                            0.29,
                            0.79,
                            0.18,
                            0.13,
                            2.16,
                            0.13,
                            0.11,
                            0.17,
                            0.26
                        ]
                    }
                },
                {
                    y: 10.57,
                    color: colors[1],
                    drilldown: {
                        name: 'Firefox',
                        categories: [
                            'Firefox v58.0',
                            'Firefox v57.0',
                            'Firefox v56.0',
                            'Firefox v55.0',
                            'Firefox v54.0',
                            'Firefox v52.0',
                            'Firefox v51.0',
                            'Firefox v50.0',
                            'Firefox v48.0',
                            'Firefox v47.0'
                        ],
                        data: [
                            1.02,
                            7.36,
                            0.35,
                            0.11,
                            0.1,
                            0.95,
                            0.15,
                            0.1,
                            0.31,
                            0.12
                        ]
                    }
                },
                {
                    y: 7.23,
                    color: colors[0],
                    drilldown: {
                        name: 'Internet Explorer',
                        categories: [
                            'Internet Explorer v11.0',
                            'Internet Explorer v10.0',
                            'Internet Explorer v9.0',
                            'Internet Explorer v8.0'
                        ],
                        data: [
                            6.2,
                            0.29,
                            0.27,
                            0.47
                        ]
                    }
                },
                {
                    y: 5.58,
                    color: colors[3],
                    drilldown: {
                        name: 'Safari',
                        categories: [
                            'Safari v11.0',
                            'Safari v10.1',
                            'Safari v10.0',
                            'Safari v9.1',
                            'Safari v9.0',
                            'Safari v5.1'
                        ],
                        data: [
                            3.39,
                            0.96,
                            0.36,
                            0.54,
                            0.13,
                            0.2
                        ]
                    }
                },
                {
                    y: 4.02,
                    color: colors[5],
                    drilldown: {
                        name: 'Edge',
                        categories: [
                            'Edge v16',
                            'Edge v15',
                            'Edge v14',
                            'Edge v13'
                        ],
                        data: [
                            2.6,
                            0.92,
                            0.4,
                            0.1
                        ]
                    }
                },
                {
                    y: 1.92,
                    color: colors[4],
                    drilldown: {
                        name: 'Opera',
                        categories: [
                            'Opera v50.0',
                            'Opera v49.0',
                            'Opera v12.1'
                        ],
                        data: [
                            0.96,
                            0.82,
                            0.14
                        ]
                    }
                },
                {
                    y: 7.62,
                    color: colors[6],
                    drilldown: {
                        name: 'Other',
                        categories: [
                            'Other'
                        ],
                        data: [
                            7.62
                        ]
                    }
                }
            ],
            browserData = [],
            versionsData = [],
            i,
            j,
            dataLen = data.length,
            drillDataLen,
            brightness;

        // Build the data arrays
        for (i = 0; i < dataLen; i += 1) {

            // add browser data
            browserData.push({
                name: categories[i],
                y: data[i].y,
                color: data[i].color
            });

            // add version data
            drillDataLen = data[i].drilldown.data.length;
            for (j = 0; j < drillDataLen; j += 1) {
                brightness = 0.2 - (j / drillDataLen) / 5;
                versionsData.push({
                    name: data[i].drilldown.categories[j],
                    y: data[i].drilldown.data[j],
                    color: Highcharts.color(data[i].color).brighten(brightness).get()
                });
            }
        }
    </script>
@endprepend
