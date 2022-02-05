@extends('layouts.admin') 

@section('title', tr('revenue_management')) 

@section('content-header', tr('revenue_management'))

@section('breadcrumb')

<li class="breadcrumb-item active">{{ tr('revenue_dashboard') }}</li>

@endsection 

@section('styles')

<link rel="stylesheet" href="{{asset('assets/vendor_components/chartist-js-develop/chartist.css')}}">

@endsection

@section('content')

<div class="row">
    
    <div class="col-xl-3 col-md-6 col-12 ">
        
        <div class="box box-body bg-success">
            <a href="{{route('admin.subscription_payments.index')}}">
                <h6 class="text-uppercase text-white">{{tr('total_payments')}}</h6>
                <div class="flexbox text-white">
                    <span class="fa fa-area-chart font-size-50"></span>
                    <span class="font-size-30">{{formatted_amount($data->total_payments)}}</span>
                </div>
            </a>
            
        </div>
    </div>
   
    <div class="col-xl-3 col-md-6 col-12 ">
        
        <div class="box box-body bg-dark">
            
            <h6 class="text-uppercase text-white">{{tr('today_payments')}}</h6>
            <div class="flexbox text-white">
                <span class="ion ion-ios-calendar font-size-50"></span>
                <span class="font-size-30">{{formatted_amount($data->today_payments)}}</span>
            </div>
            
        </div>
    </div>

    <div class="col-12">
        <div class="box">

            <div class="box-header">
                <h4 class="text-uppercase">Last 7 Months Revenue Stastics</h4>
                <p class="text-muted">Subscription payments analytics for each month.</p>
            </div>

            <div class="box-body">
                <div class="chart">
                    <canvas id="chart_8" height="510"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection 

@section('scripts')
    
    <script src="{{asset('assets/vendor_components/chart.js-master/Chart.min.js')}}"></script>
    <script type="text/javascript">

    if( $('#chart_8').length > 0 ){
        var ctx2 = document.getElementById("chart_8").getContext("2d");
        var data2 = {
            labels: [<?php foreach ($data->analytics->last_x_days_revenues as $key => $value)       {
                                echo '"'.$value->date.'"'.',';
                            } 
                            ?>],
            datasets: [
                
                {
                    label: "Subscription Payments",
                    backgroundColor: "rgba(251, 150, 120, 0.6)",
                    borderColor: "rgba(251, 150, 120, 0.6)",
                    data: [<?php 
                                foreach ($data->analytics->last_x_days_revenues as $value) {
                                    echo $value->total_subscription_earnings.',';
                                }

                            ?>]
                },
                
            ]
        };
        
        var hBar = new Chart(ctx2, {
            type:"bar",
            data:data2,
            
            options: {
                tooltips: {
                    mode:"label"
                },
                scales: {
                    yAxes: [{
                        stacked: true,
                        gridLines: {
                            color: "rgba(135,135,135,0)",
                        },
                        ticks: {
                            fontFamily: "Poppins",
                            fontColor:"#878787"
                        }
                    }],
                    xAxes: [{
                        stacked: true,
                        gridLines: {
                            color: "rgba(135,135,135,0)",
                        },
                        ticks: {
                            fontFamily: "Poppins",
                            fontColor:"#878787"
                        }
                    }],
                    
                },
                elements:{
                    point: {
                        hitRadius:40
                    }
                },
                animation: {
                    duration:   3000
                },
                responsive: true,
                maintainAspectRatio:false,
                legend: {
                    display: false,
                },
                
                tooltip: {
                    backgroundColor:'rgba(33,33,33,1)',
                    cornerRadius:0,
                    footerFontFamily:"'Poppins'"
                }
                
            }
        });
    }

    </script>
@endsection
