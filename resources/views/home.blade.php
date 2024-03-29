@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="assets/css/chart.min.css">
@endpush

@section('content')
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="filter-toggle btn-group float-end">
                <div class="btn btn-primary data-btn" data-start_date="{{ date('Y-m-d') }}"
                    data-end_date="{{ date('Y-m-d') }}">Today</div>
                <div class="btn btn-primary data-btn" data-start_date="{{ date('Y-m-d', strtotime('-7 day')) }}"
                    data-end_date="{{ date('Y-m-d') }}">This Week</div>
                <div class="btn btn-primary data-btn active" data-start_date="{{ date('Y-m') . '-01' }}"
                    data-end_date="{{ date('Y-m-d') }}">This Month</div>
                <div class="btn btn-primary data-btn" data-start_date="{{ date('Y') . '-01-01' }}"
                    data-end_date="{{ date('Y') . '-12-31' }}">This Year</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card radius-10 bg-gradient-deepblue">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0 text-white">${{ number_format($sale, 2) }}</h5>
                        <div class="ms-auto">
                            <img src="images/sale.svg" width="30px" alt="">
                        </div>
                    </div>
                    <div class="d-flex align-items-center text-white">
                        <p class="mb-0">Sale</p>
                        {{-- <p class="mb-0 ms-auto">+4.2%<span><i class='bx bx-up-arrow-alt'></i></span></p> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card radius-10 bg-gradient-orange">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0 text-white">${{ number_format($purchase, 2) }}</h5>
                        <div class="ms-auto">
                            <img src="images/purchase.svg" width="30px" alt="">
                        </div>
                    </div>
                    <div class="d-flex align-items-center text-white">
                        <p class="mb-0">Purchase</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card radius-10 bg-gradient-ohhappiness">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0 text-white">${{ number_format($profit, 2) }}</h5>
                        <div class="ms-auto">
                            <img src="images/profit.svg" width="30px" alt="">
                        </div>
                    </div>
                    <div class="d-flex align-items-center text-white">
                        <p class="mb-0">Profit</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card radius-10 bg-gradient-ibiza">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0 text-white">${{ number_format($expense, 2) }}</h5>
                        <div class="ms-auto">
                            <img src="images/expense.svg" width="30px" alt="">
                        </div>
                    </div>
                    <div class="d-flex align-items-center text-white">
                        <p class="mb-0">Expense</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card radius-10 bg-gradient-ibiza">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0 text-white">{{ $customer }}</h5>
                        <div class="ms-auto">
                            <img src="images/customer.svg" width="30px" alt="">
                        </div>
                    </div>
                    <div class="d-flex align-items-center text-white">
                        <p class="mb-0">Customer</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card radius-10 bg-gradient-ibiza">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0 text-white">{{ $supplier }}</h5>
                        <div class="ms-auto">
                            <img src="images/expense.svg" width="30px" alt="">
                        </div>
                    </div>
                    <div class="d-flex align-items-center text-white">
                        <p class="mb-0">Supplier</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end row-->

    <div class="row pt-5">
        <!-- Start :: Cash Flow Graph -->
        <div class="col-md-7">
            <div class="card line-chart">
                <div class="card-header d-flex align-items-center">
                    <h4>Cash Flow</h4>
                </div>
                <div class="card-body">
                    <canvas id="cashFlow" data-color="#038fde" data-color_rgba="rgba(3, 143, 222, 1)"
                        data-received="{{ json_encode($payment_received) }}" data-sent="{{ json_encode($payment_sent) }}"
                        data-month="{{ json_encode($month) }}" data-label1="Payment Received"
                        data-label2="Payment Sent"></canvas>
                </div>
            </div>
        </div>
        <!-- End :: Cash Flow Graph -->

        <!-- Start :: Transaction Chart-->
        <div class="col-md-5">
            <div class="card doughnut-chart">
                <div class="card-header d-flex align-items-center">
                    <h4>{{ date('F') }} {{ date('Y') }}</h4>
                </div>
                <div class="card-body">
                    <canvas id="transactionChart" data-color="#038fde" data-color_rgba="rgba(3, 143, 222, 1)"
                        data-sale="{{ $sale }}" data-purchase="{{ $purchase }}"
                        data-expense="{{ $expense }}" data-label1="Purchase" data-label2="Sale"
                        data-label3="Expense"></canvas>
                </div>
            </div>
        </div>
        <!-- End :: Transaction Chart-->
    </div>



    <!-- Start :: Bar Chart-->
    <div class="row">
        <div class="col-md-12">
            <div class="card bar-chart">
                <div class="card-header d-flex align-items-center">
                    <h4>Yearly Report </h4>
                </div>
                <div class="card-body">
                    <canvas id="yearlyReportChart" data-sale_chart_value="{{ json_encode($yearly_sale_amount) }}"
                        data-purchase_chart_value="{{ json_encode($yearly_purchase_amount) }}"
                        data-label1="Purchase Amount" data-label2="Sale Amount"></canvas>

                </div>
            </div>
        </div>
    </div>
    <!-- End :: Bar Chart-->
@endsection

@push('scripts')
    <script src="assets/js/chart.min.js"></script>
    <script>
        $(document).ready(function() {

            $('.data-btn').on('click', function() {
                $('.data-btn').removeClass('active');
                $(this).addClass('active');
                var start_date = $(this).data('start_date');
                var end_date = $(this).data('end_date');

                $.get("{{ url('dashboard-data') }}/" + start_date + '/' + end_date, function(data) {
                    $('#sale').text(data.sale);
                    $('#purchase').text(data.purchase);
                    $('#profit').text(data.profit);
                    $('#expense').text(data.expense);
                    $('#customer').text(data.customer);
                    $('#supplier').text(data.supplier);
                });
            });


            var brandPrimary;
            var brandPrimaryRgba;

            //Cash Flow Chart
            var CASHFLOW = $('#cashFlow');
            if (CASHFLOW.length > 0) {
                brandPrimary = CASHFLOW.data('color');
                brandPrimaryRgba = CASHFLOW.data('color_rgba');
                var received = CASHFLOW.data('received');
                var sent = CASHFLOW.data('sent');
                var month = CASHFLOW.data('month');
                var label1 = CASHFLOW.data('label1');
                var label2 = CASHFLOW.data('label2');
                var cashFlow_chart = new Chart(CASHFLOW, {
                    type: 'line',
                    data: {
                        labels: [month[0], month[1], month[2], month[3], month[4], month[5], month[6]],
                        datasets: [{
                                label: label1,
                                fill: true,
                                lineTension: 0.3,
                                backgroundColor: 'transparent',
                                borderColor: brandPrimary,
                                borderCapStyle: 'butt',
                                borderDash: [],
                                borderDashOffset: 0.0,
                                borderJoinStyle: 'miter',
                                borderWidth: 3,
                                pointBorderColor: brandPrimary,
                                pointBackgroundColor: '#fff',
                                pointBorderWidth: 5,
                                pointHoverRadius: 5,
                                pointHoverBackgroundColor: brandPrimary,
                                pointHoverBorderColor: brandPrimaryRgba,
                                pointHoverBorderWidth: 2,
                                pointRadius: 1,
                                pointHitRadius: 10,
                                data: [received[0], received[1], received[2], received[3], received[4],
                                    received[5], received[6]
                                ],
                                spanGaps: false
                            },
                            {
                                label: label2,
                                fill: true,
                                lineTension: 0.3,
                                backgroundColor: 'transparent',
                                borderColor: '#f5222d',
                                borderCapStyle: 'butt',
                                borderDash: [],
                                borderDashOffset: 0.0,
                                borderJoinStyle: 'miter',
                                borderWidth: 3,
                                pointBorderColor: 'rgba(245, 34, 45, 1)',
                                pointBackgroundColor: '#fff',
                                pointBorderWidth: 5,
                                pointHoverRadius: 5,
                                pointHoverBackgroundColor: '#f5222d',
                                pointHoverBorderColor: 'rgba(245, 34, 45, 1)',
                                pointHoverBorderWidth: 2,
                                pointRadius: 1,
                                pointHitRadius: 10,
                                data: [sent[0], sent[1], sent[2], sent[3], sent[4], sent[5], sent[6]],
                                spanGaps: false
                            }
                        ]
                    }
                });
            }

            //Transaction Chart
            var TRANSACTIONCHART = $('#transactionChart');
            if (TRANSACTIONCHART.length > 0) {
                brandPrimary = TRANSACTIONCHART.data('color');
                brandPrimaryRgba = TRANSACTIONCHART.data('color_rgba');
                var sale = TRANSACTIONCHART.data('sale');
                var purchase = TRANSACTIONCHART.data('purchase');
                var expense = TRANSACTIONCHART.data('expense');
                var label1 = TRANSACTIONCHART.data('label1');
                var label2 = TRANSACTIONCHART.data('label2');
                var label3 = TRANSACTIONCHART.data('label3');
                var transaction_chart = new Chart(TRANSACTIONCHART, {
                    type: 'doughnut',
                    data: {
                        labels: [label1, label2, label3],
                        datasets: [{
                            data: [purchase, sale, expense],
                            borderWidth: [1, 1, 1],
                            backgroundColor: [brandPrimary, '#52c41a', '#f5222d'],
                            hoverBackgroundColor: [
                                brandPrimaryRgba,
                                'rgba(82, 196, 26, 1)',
                                'rgba(245, 34, 45, 1)'
                            ],
                            hoverBorderWidth: [4, 4, 4],
                            hoverBorderColor: [
                                brandPrimaryRgba,
                                'rgba(82, 196, 26, 1)',
                                'rgba(245, 34, 45, 1)'
                            ]
                        }]
                    }
                });
            }

            //Yearly Report Chart
            var YEARLYREPORTCHART = $('#yearlyReportChart');

            if (YEARLYREPORTCHART.length > 0) {
                var yearly_sale_amount = YEARLYREPORTCHART.data('sale_chart_value');
                var yearly_purchase_amount = YEARLYREPORTCHART.data('purchase_chart_value');
                var label1 = YEARLYREPORTCHART.data('label1');
                var label2 = YEARLYREPORTCHART.data('label2');

                var yearly_report_chart = new Chart(YEARLYREPORTCHART, {
                    type: 'bar',
                    data: {
                        labels: ["January", "February", "March", "April", "May", "June", "July", "August",
                            "September", "October", "November", "December"
                        ],
                        datasets: [{
                                label: label1,
                                backgroundColor: [
                                    brandPrimaryRgba,
                                    brandPrimaryRgba,
                                    brandPrimaryRgba,
                                    brandPrimaryRgba,
                                    brandPrimaryRgba,
                                    brandPrimaryRgba,
                                    brandPrimaryRgba,
                                    brandPrimaryRgba,
                                    brandPrimaryRgba,
                                    brandPrimaryRgba,
                                    brandPrimaryRgba,
                                    brandPrimaryRgba,
                                    brandPrimaryRgba,
                                ],
                                borderColor: [
                                    brandPrimary,
                                    brandPrimary,
                                    brandPrimary,
                                    brandPrimary,
                                    brandPrimary,
                                    brandPrimary,
                                    brandPrimary,
                                    brandPrimary,
                                    brandPrimary,
                                    brandPrimary,
                                    brandPrimary,
                                    brandPrimary,
                                    brandPrimary,
                                ],
                                borderWidth: 1,
                                data: [
                                    yearly_purchase_amount[0], yearly_purchase_amount[1],
                                    yearly_purchase_amount[2], yearly_purchase_amount[3],
                                    yearly_purchase_amount[4], yearly_purchase_amount[5],
                                    yearly_purchase_amount[6], yearly_purchase_amount[7],
                                    yearly_purchase_amount[8], yearly_purchase_amount[9],
                                    yearly_purchase_amount[10], yearly_purchase_amount[11], 0
                                ],
                            },
                            {
                                label: label2,
                                backgroundColor: [
                                    'rgba(82, 196, 26, 1)',
                                    'rgba(82, 196, 26, 1)',
                                    'rgba(82, 196, 26, 1)',
                                    'rgba(82, 196, 26, 1)',
                                    'rgba(82, 196, 26, 1)',
                                    'rgba(82, 196, 26, 1)',
                                    'rgba(82, 196, 26, 1)',
                                    'rgba(82, 196, 26, 1)',
                                    'rgba(82, 196, 26, 1)',
                                    'rgba(82, 196, 26, 1)',
                                    'rgba(82, 196, 26, 1)',
                                    'rgba(82, 196, 26, 1)',
                                    'rgba(82, 196, 26, 1)',
                                ],
                                borderColor: [
                                    '#52c41a',
                                    '#52c41a',
                                    '#52c41a',
                                    '#52c41a',
                                    '#52c41a',
                                    '#52c41a',
                                    '#52c41a',
                                    '#52c41a',
                                    '#52c41a',
                                    '#52c41a',
                                    '#52c41a',
                                    '#52c41a',
                                    '#52c41a',
                                ],
                                borderWidth: 1,
                                data: [
                                    yearly_sale_amount[0], yearly_sale_amount[1],
                                    yearly_sale_amount[2], yearly_sale_amount[3],
                                    yearly_sale_amount[4], yearly_sale_amount[5],
                                    yearly_sale_amount[6], yearly_sale_amount[7],
                                    yearly_sale_amount[8], yearly_sale_amount[9],
                                    yearly_sale_amount[10], yearly_sale_amount[11], 0
                                ],
                            },
                        ]
                    }
                });
            }
        });
    </script>
@endpush
