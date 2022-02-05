@extends('layouts.admin') 

@section('title', tr('subscriptions'))

@section('icon', 'corner-right-down')

@section('breadcrumb')

    <li class="breadcrumb-item">
    	<a href="{{ route('admin.subscriptions.index') }}">{{tr('subscriptions')}}</a>
    </li>
    
    <li class="breadcrumb-item" aria-current="page">
    	<span>{{tr('edit_subscription')}}</span>
    </li>
           
@endsection 

@section('content')
	
	@include('admin.subscriptions._form') 

@endsection