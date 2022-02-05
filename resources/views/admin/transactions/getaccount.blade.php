@extends('layouts.admin')

@section('content-header')

Users

@endsection

@section('breadcrumb')

<a href="{{route('admin.users.index')}}" class="btn px-15 btn-primary">
	<i class="las la-eye fs-16"></i> Transcations
</a>

@endsection

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="card card-Vertical card-default card-md mb-4">
            <div class="card-header">
                <h6>Add User</h6>
            </div>
            <div class="card-body py-md-30">
                <form method="POST" action="{{ route('admin.transactions.index') }}" enctype="multipart/form-data">
                    
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-25">
                             <label for="for_firstname">Enter Your Account Address*</label>
                            <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15" placeholder="Account Address" name="account" required>
                        </div>

                    </div>

                    <div class="col-md-6">
                        <div class="layout-button mt-0">

                            <button type="reset" class="btn btn-warning btn-default btn-squared px-30">Reset</button>

                            <button type="submit" class="btn btn-primary btn-default btn-squared px-30">Submit</button>
                        </div>
                    
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>


@endsection