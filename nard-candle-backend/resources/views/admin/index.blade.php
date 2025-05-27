@extends('admin.layout')

@section('content')

    <!-- Page Wrapper -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" >

            <!-- Main Content -->
            <div id="content">

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <button onclick="printReport()" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                            <i class="fas fa-download fa-sm text-white-50"></i> Generate Report
                        </button>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Earnings (Monthly)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($monthlyRevenue, 2) }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Earnings (Annual) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Earnings (Annual)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($annualRevenue, 2) }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Completed Orders Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Completed Orders</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $completedOrdersCount }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Registered Users</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $registeredUsersCount }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Content Row -->
                    <div class="row">
                        <!-- Area Chart -->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myAreaChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Pie Chart -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header -->
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Products List</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Price</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($products as $product)
                                                    <tr>
                                                        <td>{{ $product->name }}</td>
                                                        <td>${{ number_format($product->price, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Content Column -->
                        <div class="col-lg-6 mb-4">

                            {{--  --}}
                        </div>
                        <div class="col-lg-6 mb-4">
                        </div>
                                </div>

                    <!-- Hidden Printable Report Section -->
                    <div id="printable-report" style="display: none;">
                        <div class="report-header">
                            <h2 class="text-center mb-4">Nard Candles Dashboard Report</h2>
                            <p class="text-center">Generated on: <span id="report-date"></span></p>
                                    </div>
                        <div class="report-body">
                            <div class="earnings-section mb-4">
                                <h3>Earnings Summary</h3>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Monthly Revenue</th>
                                        <td>${{ number_format($monthlyRevenue, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Annual Revenue</th>
                                        <td>${{ number_format($annualRevenue, 2) }}</td>
                                    </tr>
                                </table>
                                </div>
                            <div class="orders-section">
                                <h3>Orders Summary</h3>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Completed Orders</th>
                                        <td>{{ $completedOrdersCount }}</td>
                                    </tr>
                                    <tr>
                                        <th>Registered Users</th>
                                        <td>{{ $registeredUsersCount }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            
        </div>
        
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

@endsection

@section('scripts')
<script>
    // Wait for DOM and Chart.js to be ready
    $(document).ready(function() {
        // Debug data
        var monthlyData = {!! json_encode($monthlyEarnings) !!};
        var monthLabels = {!! json_encode($monthLabels) !!};
        
        console.log('Debug Data:', {
            monthlyData: monthlyData,
            monthLabels: monthLabels
        });

        // Set up chart container height
        $('.chart-area').height(400);
        $('.chart-pie').height(300);

        try {
            // Area Chart
            var ctx = document.getElementById('myAreaChart').getContext('2d');
            var myLineChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: monthLabels,
                    datasets: [{
                        label: 'Monthly Earnings ($)',
                        data: monthlyData,
                        fill: true,
                        borderColor: '#4e73df',
                        backgroundColor: 'rgba(78, 115, 223, 0.05)',
                        tension: 0.3,
                        pointRadius: 3,
                        pointBackgroundColor: '#4e73df',
                        pointBorderColor: '#4e73df',
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: '#4e73df',
                        pointHoverBorderColor: '#4e73df',
                        pointHitRadius: 10,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return '$ ' + context.raw.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 12
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                },
                                font: {
                                    size: 12
                                }
                            }
                        }
                    }
                }
            });

            console.log('Charts initialized successfully');
        } catch (error) {
            console.error('Error initializing charts:', error);
        }
    });
</script>

<script>
function printReport() {
    // Update the report date
    document.getElementById('report-date').textContent = new Date().toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });

    // Create a new window for printing
    var printWindow = window.open('', '_blank');
    var reportContent = document.getElementById('printable-report').innerHTML;
    
    // Add necessary styles
    var styles = `
        <style>
            body { font-family: Arial, sans-serif; padding: 20px; }
            .report-header { margin-bottom: 30px; text-align: center; }
            .report-body { max-width: 800px; margin: 0 auto; }
            table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
            th, td { padding: 12px; border: 1px solid #ddd; }
            th { background-color: #f8f9fc; }
            h2, h3 { color: #4e73df; }
            .mb-4 { margin-bottom: 1.5rem; }
            .text-center { text-align: center; }
            @media print {
                body { padding: 0; }
                .report-body { max-width: 100%; }
            }
        </style>
    `;

    // Write content to the new window
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Nard Candles Dashboard Report</title>
            ${styles}
        </head>
        <body>
            ${reportContent}
        </body>
        </html>
    `);

    // Wait for content to load then print
    printWindow.document.close();
    printWindow.onload = function() {
        printWindow.print();
    };
}
</script>

<style>
.chart-area {
    position: relative;
    height: 400px;
    width: 100%;
}
#printable-report {
    display: none;
}
</style>
@endsection
