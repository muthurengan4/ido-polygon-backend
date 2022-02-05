@extends('layouts.admin')

@section('title', tr('projects'))

@section('content-header', tr('projects'))

@section('breadcrumb')

	<li class="breadcrumb-item"><a href="{{route('admin.projects.index')}}">{{tr('projects')}}</a></li>

    <li class="breadcrumb-item active">{{tr('add_project')}}</a></li>

@endsection

@section('content')

	@include('admin.projects._form')

@endsection