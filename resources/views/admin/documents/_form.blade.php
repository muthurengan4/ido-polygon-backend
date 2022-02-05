<div class="row">

    <div class="col-lg-12 col-12">

        <div class="box">

            <div class="box-header with-border">

                <h3 class="box-title">{{ $document->id ? tr('edit_document') : tr('add_document') }}</h3>

            </div>


            <form class="form-horizontal" action="{{(Setting::get('is_demo_control_enabled') == YES) ? '#' : route('admin.documents.save') }}" method="POST" enctype="multipart/form-data" role="form">
               
                @csrf
              
                <div class="box-body">

                    <div class="row">

                        <input type="hidden" name="document_id" id="document_id" value="{{ $document->id}}">

                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="name">{{ tr('name') }}*</label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="{{ tr('name') }}" value="{{ $document->name ?: old('name') }}" required onkeydown="return alphaOnly(event);">
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                
                                <label>{{ tr('select_picture') }}</label>
                            
                                <input class="form-control"  type="file" id="picture" name="picture" accept="image/png,image/jpeg" >
                                                                
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6">

                            <div class="form-group clearfix icheck_minimal skin">

                              <div class="icheck-success d-inline">

                                <fieldset>

                                    <input type="checkbox" id="input-6" name="is_required" value="{{YES}}" @if($document->is_required ==  YES) checked="checked" @endif>

                                    <label for="input-6">{{tr('is_required')}}</label>

                                </fieldset>

                              </div>

                            </div>

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-12">
                            
                            <label for="description">{{ tr('description') }}</label>

                            <textarea class="form-control" name="description" placeholder="{{ tr('description') }}">{{ $document->description ? $document->description :old('description') }}</textarea>
                           
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

