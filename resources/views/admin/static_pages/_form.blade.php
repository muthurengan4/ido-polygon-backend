<div class="row">

    <div class="col-lg-12 col-12">

        <div class="box">

            <div class="box-header with-border">

                <h3 class="box-title">{{ $static_page->id ? tr('edit_static_page') : tr('add_static_page') }}</h3>

            </div>

            <form class="form-horizontal" action="{{(Setting::get('is_demo_control_enabled') == YES) ? '#' : route('admin.static_pages.save') }}" method="POST" enctype="multipart/form-data" role="form">
               
                @csrf


                @if($static_page->id)

                    <input type="hidden" name="static_page_id" value="{{$static_page->id}}">

                @endif

                <div class="box-body">

                    <div class="row">

                        <div class="form-group col-md-6">
                            <div class="form-group-1">
                                <label for="title">{{tr('title')}}<span class="admin-required">*</span> </label>
                                <input type="text" id="title" name="title" class="form-control" placeholder="Enter {{tr('title')}}" required  value="{{old('title')?: $static_page->title}}" onkeydown="return alphaOnly(event);">
                            </div>
                        </div>

                        <div class="form-group col-md-6">

                            <label for="page">
                                {{tr('select_static_page_type')}}
                                <span class="required" aria-required="true"> <span class="admin-required">*</span> </span>
                            </label>
                            
                            <select class="form-control select2" name="type" required>
                                <option value="">{{tr('select_static_page_type')}}</option>

                                @foreach($static_keys as $value)

                                    <option value="{{$value}}" @if($value == $static_page->type) selected="true" @endif>{{ ucfirst($value) }}</option>

                                @endforeach 
                            </select>
                            
                        </div>
                    </div>
                    
                    <div class="row">

                        <div class="col-md-12"> 

                            <div class="form-group">

                                <label for="description">{{tr('description')}}<span class="admin-required">*</span></label>

                                <textarea rows="5" class="form-control" required name="description" placeholder="{{ tr('description') }}">{{old('description') ?: $static_page->description}}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="box-footer">
                    
                    <button type="reset" class="btn btn-warning btn-default btn-squared px-30">Reset</button>

                    <button type="submit" class="btn btn-info pull-right">Submit</button>

                </div>

            </form>
        
        </div>
    
    </div>

</section>

