@extends('layout')

@section('content')
    <main role="main">
        <div class="container container--app mb-4" id="overview">
            <div class="row">
                <div id="header-container" class="widget-carousel overflow-hidden w-100"
                     x-data="HeaderWidget()" x-id="HeaderWidget"
                     x-on:filter-changed.window="getHeaderData()">
                    <div class="widget-carousel__slide">
                        <div class="widget text-center" style="position:relative;">
                            <div class="widget__icon mx-auto">
                                <img src="{{asset('images/icons/icon_gross_income.png')}}" alt="icon gross income"
                                     class="img-fluid">
                            </div>
                            <h3 class="widget__title">
                                TOTAL BUSINESS
                            </h3>
                            <div class="widget__body">
                                33,675,000
                            </div>
                            <div class="widget__meta text-color:green">
                                +9.8%
                            </div>
                            <div x-show="loading" class="loading-dashboard"
                                 style="background-color: rgba(255, 255, 255, 1);"></div>
                        </div>
                    </div>
                    <div class="widget-carousel__slide">
                        <div class="widget text-center" style="position:relative;">
                            <div class="widget__icon mx-auto">
                                <img src="{{asset('images/icons/icon_transaction.png')}}" alt="icon transactions"
                                     class="img-fluid">
                            </div>
                            <h3 class="widget__title">
                                TOTAL BASIC BUSINESSES
                            </h3>
                            <div class="widget__body">
                                5
                            </div>
                            <div class="widget__meta text-color:red">
                                -11.9%
                            </div>
                            <div x-show="loading" class="loading-dashboard"
                                 style="background-color: rgba(255, 255, 255, 1);"></div>
                        </div>
                    </div>
                    <div class="widget-carousel__slide">
                        <div class="widget text-center" style="position:relative;">
                            <div class="widget__icon mx-auto">
                                <img src="{{asset('images/icons/icon_conversion.png')}}" alt="icon conversion rate"
                                     class="img-fluid">
                            </div>
                            <h3 class="widget__title">
                                TOTAL COMPLETE BUSINESSES
                            </h3>
                            <div class="widget__body">
                                73.67%
                            </div>
                            <div class="widget__meta text-color:green">
                                +12.2%
                            </div>
                            <div x-show="loading" class="loading-dashboard"
                                 style="background-color: rgba(255, 255, 255, 1);"></div>
                        </div>
                    </div>
                    <div class="widget-carousel__slide">
                        <div class="widget text-center" style="position:relative;">
                            <div class="widget__icon mx-auto">
                                <img src="{{asset('images/icons/icon_viewed_purple.png')}}" alt="icon viewed products"
                                     class="img-fluid">
                            </div>
                            <h3 class="widget__title">
                                TOTAL MEMBER
                            </h3>
                            <div class="widget__body">
                                4359
                            </div>
                            <div class="widget__meta text-color:green">
                                +19.6%
                            </div>
                            <div x-show="loading" class="loading-dashboard"
                                 style="background-color: rgba(255, 255, 255, 1);"></div>
                        </div>
                    </div>
                    <div class="widget-carousel__slide">
                        <div class="widget text-center" style="position:relative;">
                            <div class="widget__icon mx-auto">
                                <img src="{{asset('images/icons/icon_sold.png')}}" alt="icon sold products"
                                     class="img-fluid">
                            </div>
                            <h3 class="widget__title">
                                TOTAL TREASURE ARISE BUSINESSES
                            </h3>
                            <div class="widget__body">
                                895
                            </div>
                            <div class="widget__meta text-color:red">
                                -11.9%
                            </div>
                            <div x-show="loading" class="loading-dashboard"
                                 style="background-color: rgba(255, 255, 255, 1);"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="background-color: #FEFEFE">
            <div class="sticky-top sticky-top--header"
                 style="background-color: #FEFEFE">
                <ul class="nav nav-tabs nav-justified mt-4 dashboard-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="#impact_tab" data-toggle="tab">
                            Impact
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#members_tab" data-toggle="tab">
                            Members
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#business_tab" data-toggle="tab">
                            Business
                        </a>
                    </li>
                </ul>
                <div class="container container--app">
                    <div class="row justify-content-between py-3">
                        <div class="col-5 col-md-auto d-flex align-items-center">
                            <h1 class="page-title m-0">Statistik</h1>
                        </div>
                        <div class="col">
                            <section class="dashboard-filter"
                                     x-data id="DashboardFilter">
                                <div class="d-flex align-items-center justify-content-end">
                                    <div class="d-none d-md-flex align-items-center">
                                        <ul class="nav nav-pills">
                                            <template x-for="(filter, index) in filters" :key="index">
                                                <li class="nav-item mr-3">
                                                    <a class="btn btn-default rounded-pill btn-sm px-3" href="#"
                                                       :class="{ 'text-color:blue font-weight-bold': $store.filter.currentFilter === index }"
                                                       @click.prevent="$store.filter.setFilter(index)"
                                                       x-text="filter.title">
                                                        Filter
                                                    </a>
                                                </li>
                                            </template>
                                            <template x-if="$store.filter.currentFilter === 'custom'">
                                                <li class="nav-item">
                                                    <a class="btn btn-default rounded-pill btn-sm px-3" href="#"
                                                       :class="{ 'text-color:blue font-weight-bold': $store.filter.currentFilter === 'custom' }">
                                                        Custom
                                                    </a>
                                                </li>
                                            </template>
                                        </ul>
                                        <div class="border-right mx-4" style="height: 39px"></div>
                                        <label class="filter-label my-0 mx-2">
                                            OR
                                        </label>
                                        <div class="dropdown ml-3" x-model="$store.filter.startDate">
                                            <button class="btn btn-default dropdown-toggle datepicker" type="button"
                                                    x-on:datepicker-changed.self="$dispatch('input', $event.detail);$dispatch('filter-date-changed')">
                                                <i class="fas fa-calendar fa-fw"></i>
                                                <span x-text="$store.filter.startDate">{{$start_date}}</span>
                                            </button>
                                            <input type="hidden" name="start_date" x-model="$store.filter.startDate">
                                        </div>
                                        <div class="mx-2 text-center">
                                            -
                                        </div>
                                        <div class="dropdown" x-model="$store.filter.endDate">
                                            <button class="btn btn-default dropdown-toggle datepicker" type="button"
                                                    x-on:datepicker-changed.self="$dispatch('input', $event.detail);$dispatch('filter-date-changed')">
                                                <i class="fas fa-calendar fa-fw"></i>
                                                <span x-text="$store.filter.endDate">{{$end_date}}</span>
                                                <input type="hidden" name="end_date" x-model="$store.filter.endDate">
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-content pb-2">
                <div class="tab-pane fade show active" id="impact_tab" role="tabpanel"
                     x-data="ImpactTab()"
                     x-on:filter-changed.window="fetchData()">
                </div>
                <div class="tab-pane fade" id="members_tab" role="tabpanel"
                     x-data="MembersTab()"
                     x-on:filter-changed.window="fetchData()">
                </div>
                <div class="tab-pane fade" id="business_tab" role="tabpanel"
                     x-data="BusinessTab()"
                     x-on:filter-changed.window="fetchData()">
                </div>
            </div>
            <hr>
            <div class="sticky-top pb-3" style="bottom: 0;">
                <div class="text-center mt-4">
                    <a href="#overview" class="text-muted" data-scroll="body">
                        Back To Top
                        <i class="fas fa-arrow-up fa-fw"></i>
                    </a>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('styles')
    <style>
        .loading-dashboard {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 10
        }

        .loading-dashboard:after {
            content: url({{asset('images/ajax-loader.gif')}});
            width: 100px;
            height: 100px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%)
        }

        .chart__title-container {
            position: absolute;
            text-align: left;
            width: 250px;
            padding-bottom: 20px;
            border-bottom: 1px solid gray;
            padding-left: 30px;
        }

        .chart__title {
            font-size: 32px;
            text-align: left;
        }

        .chart__subtitle {
            font-size: 16px;
            text-align: left;
        }

        .dashboard-tabs .nav-link {
            background: #F5F5F8;
            border-color: #DBDDE4;
            color: #3E3F42;
            border-radius: 0;
            font-size: 14px;
            line-height: 28px;
            transition: background 200ms;
        }

        .dashboard-tabs .nav-link:hover {
            background: #efeff4;
        }

        .dashboard-tabs .nav-link.active {
            color: #1665D8;
            position: relative;
        }

        .dashboard-tabs .nav-link.active:after {
            content: '';
            display: block;
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: #1D78FD;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker3.standalone.min.css">
    <script>
        var filters = {
            '1y': {
                title: '1 year',
                start: moment().subtract(1, 'year').add(1, 'day').format('YYYY-MM-DD'),
                end: moment().format('YYYY-MM-DD'),
                group_by: 'month'
            },
            '7d': {
                title: '7 days',
                start: moment().subtract(7, 'days').add(1, 'day').format('YYYY-MM-DD'),
                end: moment().format('YYYY-MM-DD'),
                group_by: 'day'
            },
            '30d': {
                title: '30 days',
                start: moment().subtract(30, 'days').add(1, 'day').format('YYYY-MM-DD'),
                end: moment().format('YYYY-MM-DD'),
                group_by: 'week'
            },
            '90d': {
                title: '90 days',
                start: moment().subtract(90, 'days').add(1, 'day').format('YYYY-MM-DD'),
                end: moment().format('YYYY-MM-DD'),
                group_by: 'month'
            }
        };

        $(function() {
            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayBtn: true,
                todayHighlight: true
            }).on('changeDate', function(e) {
                var selectedDate = moment(e.date).format('DD-MM-YYYY');
                var endDateChanged = new CustomEvent('datepicker-changed', {
                    detail: selectedDate
                });
                Alpine.store('filter').currentFilter = 'custom';
                this.dispatchEvent(endDateChanged);
            });
        });

        function HeaderWidget() {
            return {
                loading: false,

                init: function() {
                    this.getHeaderData();
                },
                getHeaderData: function() {
                    this.loading = true;
                    var that = this;

                    $.ajax({
                        url: '{{route("dashboard.getheader")}}',
                        data: {
                            start_date: Alpine.store('filter').startDate,
                            end_date: Alpine.store('filter').endDate
                        }
                    }).done(function(response) {
                        var $carousel = $('.widget-carousel');
                        $carousel.slick('destroy');

                        $('#header-container').html(response);
                        $carousel.slick({
                            slidesToShow: 2.5,
                            infinite: false,
                            mobileFirst: true,
                            responsive: [
                                {
                                    breakpoint: 992,
                                    settings: {
                                        slidesToShow: 5
                                    }
                                }]
                        });
                    }).always(function() {
                        that.loading = false;
                    });
                }
            };
        }

        function ImpactTab() {
            return {
                loading: false,

                init: function() {
                    this.fetchData();
                },
                fetchData: function() {
                    this.loading = true;
                    var that = this;

                    $.ajax({
                        url: '{{route("dashboard.impact")}}',
                        data: {
                            start_date: Alpine.store('filter').startDate,
                            end_date: Alpine.store('filter').endDate,
                            group_by: Alpine.store('filter').groupBy
                        }
                    }).done(function(response) {
                        $('#impact_tab').html(response);
                    }).always(function() {
                        that.loading = false;
                    });
                }
            };
        }

        function MembersTab() {
            return {
                loading: false,

                init: function() {
                    this.fetchData();
                },
                fetchData: function() {
                    this.loading = true;
                    var that = this;

                    $.ajax({
                        url: '{{route("dashboard.members")}}',
                        data: {
                            start_date: Alpine.store('filter').startDate,
                            end_date: Alpine.store('filter').endDate,
                            group_by: Alpine.store('filter').groupBy
                        }
                    }).done(function(response) {
                        $('#members_tab').html(response);
                    }).always(function() {
                        that.loading = false;
                    });
                }
            };
        }

        function BusinessTab() {
            return {
                loading: false,

                init: function() {
                    this.fetchData();
                },
                fetchData: function() {
                    this.loading = true;
                    var that = this;

                    $.ajax({
                        url: '{{route("dashboard.business")}}',
                        data: {
                            start_date: Alpine.store('filter').startDate,
                            end_date: Alpine.store('filter').endDate,
                            group_by: Alpine.store('filter').groupBy
                        }
                    }).done(function(response) {
                        $('#business_tab').html(response);
                    }).always(function() {
                        that.loading = false;
                    });
                }
            };
        }

        document.addEventListener('alpine:init', function() {
            Alpine.store('filter', {
                currentFilter: '30d',

                startDate: '{{$start_date}}',
                endDate: moment().format('DD-MM-YYYY'),
                groupBy: 'week',

                setFilter: function(filterKey) {
                    this.currentFilter = filterKey;
                    this.startDate = filters[filterKey].start;
                    this.endDate = filters[filterKey].end;
                    this.groupBy = filters[filterKey].group_by;
                }
            });

            Alpine.effect(function() {
                var currentFilter = Alpine.store('filter').currentFilter;
                var startDate = Alpine.store('filter').startDate;
                var endDate = Alpine.store('filter').endDate;

                dispatchEvent(new CustomEvent('filter-changed'));
            });
        });
    </script>
@endpush
