@extends('layouts.admin')

@section('title', tr('contact_forms'))

@section('content-header', tr('contact_forms'))

@section('breadcrumb')
    

    <li class="breadcrumb-item"><a href="{{route('admin.contact_forms.index')}}">{{tr('contact_forms')}}</a>
    </li>

    <li class="breadcrumb-item active">{{tr('view_contact_forms')}}</a>
    </li>

@endsection

@section('content')


<div class="box">
    
    <div class="box-body">

        <div class="row">
            
            <div class="col-md-12">
                <div class="media-list media-list-divided">

                    <div class="media media-single">
                        <div class="media-body">
                            <h6>{{$contact_form->name}}</h6>
                            <small class="text-fader">{{common_date($contact_form->created_at , Auth::guard('admin')->user()->timezone)}}</small>
                        </div>


                        @if($contact_form->status == CONTACT_FORM_INITIATED)

                            <div class="media-right">

                                <a class="btn bg-navy margin" href="{{  route('admin.contact_forms.status' , ['contact_form_id' => $contact_form->id, 'status' => CONTACT_FORM_COMPLETED] )  }}" > <i class="fa fa-check"></i>  {{ tr('complete') }}
                                                </a>
                            </div>

                            <div class="media-right">

                                <a class="btn bg-navy margin" href="{{ route('admin.contact_forms.status' , ['contact_form_id' => $contact_form->id, 'status' => CONTACT_FORM_DECLINED] ) }}"> <i class="fa fa-check"></i>  {{ tr('decline') }}</a>
                            </div>

                        @endif                        
                      
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>      

<div class="row">
    
    <div class="col-lg-12 col-12">
        
        <div class="box">
            
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover no-margin">
                        <tbody>
                            <tr>
                                <th>{{tr('name')}}</th>
                                <td>{{$contact_form->name ?: tr('n_a')}}</td>
                            </tr>

                            <tr>
                                <th>{{tr('title')}}</th>
                                <td>{{$contact_form->title ?: tr('n_a')}}</td>
                            </tr>

                            <tr>
                                <th>{{tr('email')}}</th>
                                <td>{{$contact_form->email ?: tr('n_a')}}</td>
                            </tr>

                            <!-- <tr>
                                <th>{{tr('mobile')}}</th>
                                <td>{{$contact_form->mobile ?: tr('n_a')}}</td>
                            </tr> -->

                            <tr>
                                <th>{{tr('description')}}</th>
                                <td>{{$contact_form->description ?: tr('n_a')}}</td>
                            </tr>

                            <tr>
                                <th>{{tr('telegram_link')}}</th>
                                <td>{{$contact_form->telegram_link ?: tr('n_a')}}</td>
                            </tr>

                            <tr>
                                <th>{{tr('status')}}</th>
                                <td>
                                    @if($contact_form->status == CONTACT_FORM_INITIATED)

                                        <span class="label label-primary">{{tr('initiated')}}</span>

                                    @elseif($contact_form->status == CONTACT_FORM_COMPLETED)

                                        <span class="label label-success">{{tr('completed')}}</span>

                                    @else

                                        <span class="label label-danger">{{tr('declined')}}</span>

                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th>{{tr('created_at')}}</th>
                                <td>{{common_date($contact_form->created_at , Auth::guard('admin')->user()->timezone)}}</td>
                            </tr>

                            <tr>
                                <th>{{tr('updated_at')}}</th>
                                <td>{{common_date($contact_form->updated_at , Auth::guard('admin')->user()->timezone)}}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.box-body -->
         </div>
    </div>
</div>

@endsection

