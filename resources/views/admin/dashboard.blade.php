@extends('layouts.admin')

@section('content-header', tr('dashboard'))

@section('breadcrumb')

<li class="breadcrumb-item active">{{tr('dashboard')}}</li>

@endsection

@section('styles')

@endsection


@section('content')

	<div class="row">
		<div class="col-xl-3 col-md-6 col">
			<a href="{{route('admin.users.index')}}">
			    <div class="info-box info-box-css bg-dark">
			        <span class="info-box-icon"><i class="glyphicon glyphicon-user"></i></span>
			        <div class="info-box-content">
			            <span class="info-box-number">{{$data->total_users}}</span>
			            <span class="info-box-text">{{tr('total_users')}}</span>
			        </div>
			        <!-- /.info-box-content -->
			    </div>
			    <!-- /.info-box -->
			</a>
		
		</div>

		<div class="col-xl-3 col-md-6 col">
			<a href="{{route('admin.projects.index')}}">

			    <div class="info-box info-box-css bg-success">
			        <span class="info-box-icon"><i class="glyphicon glyphicon-book"></i></span>
			        <div class="info-box-content">
			            <span class="info-box-number">{{$data->total_projects}}</span>
			            <span class="info-box-text">{{tr('total_projects')}}</span>
			        </div>
			        <!-- /.info-box-content -->
			    </div>
			    <!-- /.info-box -->
			</a>
		
		</div>

	
        
    </div>

    <div class="row">
    	<div class="col-12 col-lg-12">
			<div class="box">
				<div class="box-header with-border">
				  	<h3 class="box-title">{{tr('last_6_months_projects')}}</h3>
				</div>
				<div class="box-body">
				  	<!-- <p class="text-muted"><i>{{tr('last_6_months_revenue_notes')}}</i></p> -->

				 	<div class="chart">
						<div id="e_chart_2" class="" style="height:400px;"></div>	
				  	</div>
				</div>
			</div>

	    </div>
	</div>

    <div class="row">

    	@if($data->recent_projects->count() > 0)

	    	<div class="col-md-6 col-lg-6">
	            <div class="box">
					<div class="box-header with-border">
	              		<h5 class="box-title">{{tr('recent_projects')}}</h5>
					</div>
					<div class="box-body p-0">
					  	
					  	<div class="media-list media-list-hover media-list-divided">

					  		@foreach($data->recent_projects as $v => $project)
								<a class="media media-single" href="{{route('admin.projects.view',['project_id' => $project->id])}}">
								  	<img class="avatar" src="{{$project->picture}}" alt="{{$project->name}}">
								  	<div class="media-body">
										<h6>{{$project->name ?? tr('not_available')}}</h6>
										<small class="text-fader">By {{$project->user->name ?? tr('not_available')}}</small>
								  	</div>
								  	<p style="float: right;">{{common_date($project->created_at , Auth::guard('admin')->user()->timezone)}}</p>
								</a>
							@endforeach
					  </div>
					</div>
	              <div class="text-center bt-1 border-light p-2">
	                <a class="text-uppercase d-block font-size-12" href="{{route('admin.projects.index')}}">See all</a>
	              </div>
	            </div>
	        </div>

        @endif

        @if($data->recent_users->count() > 0)
        <div class="col-md-6 col-lg-6">
            <div class="box">
				<div class="box-header with-border">
              		<h5 class="box-title">{{tr('recent_users')}}</h5>
				</div>
				<div class="box-body p-0">
				  	<div class="media-list media-list-hover media-list-divided">

				  		@foreach($data->recent_users as $v => $users)

							<div class="media media-single">
								
								<a href="{{route('admin.users.view',['user_id' => $users->id])}}">
									<img class="avatar" src="{{$users->picture}}" alt="{{$users->name}}">
								</a>

							  	<div class="media-body">
									<h6><a href="{{route('admin.users.view',['user_id' => $users->id])}}">{{$users->name ?? tr('not_available')}}</a></h6>
									<small class="text-fader">{{$users->email}}</small>
							  	</div>

							  	<p style="float: right;">{{common_date($users->created_at , Auth::guard('admin')->user()->timezone)}}</p>
							</div>

						@endforeach
				  	</div>
				</div>
            <div class="text-center bt-1 border-light p-2">
                <a class="text-uppercase d-block font-size-12" href="{{route('admin.users.index')}}">See All</a>
            </div>
        </div>
        @endif
    </div>


@endsection

@section('scripts')
	
	<script src="../assets/vendor_components/echarts-master/dist/echarts-en.min.js"></script>
	<script src="../assets/vendor_components/echarts-liquidfill-master/dist/echarts-liquidfill.min.js"></script>

    <script type="text/javascript">

    	$(window).on("load", function() {

    		var dom = document.getElementById("e_chart_2");
			var myChart = echarts.init(dom);
			var app = {};
			option = null;
			option = {
				color: ['#00c292'],
				textStyle: {
					color: '#666666'
				},
			    tooltip: {
			        trigger: 'axis'
			    },
			    legend: {
			        data:['the highest temperature','the lowest temperature']
			    },
				
			    xAxis:  {
			        type: 'category',
			        boundaryGap: false,
			        data: [<?php foreach ($data->analytics->last_x_days_projects as $key => $value) {
                        echo '"' . $value->formatted_month . '"' . ',';
                    }
                    ?>]
			    },
			    yAxis: {
			        type: 'value',
			        axisLabel: {
			            formatter: '{value}'
			        }
			    },
			    series: [
			        {
			            name:'Projects',
			            type:'line',
			            data:[<?php foreach ($data->analytics->last_x_days_projects as $key => $value) {
                        echo '"' . $value->total_projects . '"' . ',';
                    }
                    ?>],
			            markPoint: {
			                data: [
			                    {type: 'max', name: 'Maximum'},
			                    {type: 'min', name: 'Minimum'}
			                ]
			            },
			            markLine: {
			                data: [
			                    {type: 'average', name: 'Average'}
			                ]
			            }
			        }
			    ]
			};
			if (option && typeof option === "object") {
			    myChart.setOption(option, true);
			}

		});

    </script>
@endsection
