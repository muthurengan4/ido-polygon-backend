@extends('layouts.admin') 

@section('content-header', tr('contact_forms')) 

@section('breadcrumb')

<li class="breadcrumb-item active">
    <a href="{{route('admin.contact_forms.index')}}">{{ tr('contact_forms') }}</a>
</li>

<li class="breadcrumb-item active">{{ tr('view_contact_forms') }}</li>
@endsection 

@section('content')


<div class="row">
    
    <div class="col-md-12">

        <div class="box">

            <div class="box-header with-border">
                
                <h3 class="box-title">{{$title ?? tr('view_contact_forms')}}</h3>

            </div>


            <div class="box-body">

                @include('admin.contact_forms._search')

                <div class="table-responsive">
                    
                    <table id="example2" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                    
                        <thead>
                            <tr>
                                <th>{{ tr('s_no') }}</th>
                                <th>{{ tr('title') }}</th>
                                <th>{{ tr('name') }}</th>
                                <th>{{ tr('email') }}</th>
                                <th>{{ tr('status') }}</th>
                                <th>{{ tr('action') }}</th>
                            </tr>
                        </thead>
                   
                        <tbody>

                            @foreach($contact_forms as $i => $contact_form)
                            <tr>
                                <td>{{ $i+$contact_forms->firstItem() }}</td>

                                <td>
                                    <a href="{{route('admin.contact_forms.view',['contact_form_id' => $contact_form->id])}}">
                                    {{ $contact_form->title ?: tr('n_a')}}
                                    </a>
                                </td>

                                <td>
                                    {{ $contact_form->name ?: tr('n_a')}}
                                </td>

                                <td>
                                    {{ $contact_form->email ?: tr('n_a')}}
                                </td>

                                <td>
                                    @if($contact_form->status == CONTACT_FORM_INITIATED)

                                        <span class="label label-primary">{{tr('initiated')}}</span>

                                    @elseif($contact_form->status == CONTACT_FORM_COMPLETED)

                                        <span class="label label-success">{{tr('completed')}}</span>

                                    @else

                                        <span class="label label-danger">{{tr('declined')}}</span>

                                    @endif
                                </td>
                                
                                <td>
                                
                                    <div class="btn-group" role="group">

                                        <button class="btn btn-primary dropdown-toggle dropdown-menu-right" id="btnGroupDrop1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ft-settings icon-left"></i> {{ tr('action') }}</button>

                                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">

                                            <a class="dropdown-item" href="{{ route('admin.contact_forms.view', ['contact_form_id' => $contact_form->id] ) }}">&nbsp;{{ tr('view') }}</a> 

                                            @if($contact_form->status == CONTACT_FORM_INITIATED)

                                                <a class="dropdown-item" href="{{  route('admin.contact_forms.status' , ['contact_form_id' => $contact_form->id, 'status' => CONTACT_FORM_COMPLETED] )  }}">
                                                    &nbsp;{{ tr('complete') }}
                                                </a> 

                                                <a class="dropdown-item" href="{{  route('admin.contact_forms.status' , ['contact_form_id' => $contact_form->id, 'status' => CONTACT_FORM_DECLINED] )  }}">
                                                    &nbsp;{{ tr('decline') }}
                                                </a> 

                                            @endif

                                        </div>

                                    </div>

                                </td>

                            </tr>

                            @endforeach

                        </tbody>
                    
                    </table>
                </div>
            </div>

            <div class="box-footer clearfix">
                    
                <div class="pull-right rd-flex">{{ $contact_forms->appends(request()->input())->links('pagination::bootstrap-4') }}</div>
            </div>

        </div>

    </div>
</div>


@endsection


