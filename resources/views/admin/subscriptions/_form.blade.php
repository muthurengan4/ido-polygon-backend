<div class="row">

    <div class="col-lg-12 col-12">

        <div class="box">
            <div class="box-header with-border">
                
                <h3 class="box-title">{{ $subscription->id ? tr('edit_subscription') : tr('add_subscription') }}</h3>

            </div>
                
            <form action="{{ (Setting::get('is_demo_control_enabled') == YES) ? '#' : route('admin.subscriptions.save') }}" method="POST" enctype="multipart/form-data" role="form">
                
                @csrf

                <div class="box-body">

                    <div class="row">

                        <input type="hidden" name="subscription_id" id="subscription_id" value="{{ $subscription->id}}">

                        <div class="col-md-6">
                                
                            <label for="title">{{ tr('title') }}*</label>

                            <div class="form-group">                                  
                                
                                <input type="text" id="title" name="title" class="form-control" placeholder="{{ tr('title') }}" value="{{old('title') ?: $subscription->title}}" required>

                            </div>

                        </div>

                        <div class="col-md-6">
                                
                            <label for="min_staking_balance">{{tr('min_staking_balance')}}*</label>

                            <div class="form-group">                                  
                                
                                <input type="number" min="1" step="any" id="min_staking_balance" name="min_staking_balance" class="form-control" placeholder="{{tr('min_staking_balance')}}" value="{{old('min_staking_balance') ?: $subscription->min_staking_balance}}" required>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <label for="allowed_tokens">{{ tr('allowed_tokens') }} <span class="admin-required">*</span></label>

                            <div class="form-group">

                                <input type="number" min="1" step="any" required name="allowed_tokens" class="form-control" id="allowed_tokens" value="{{ old('allowed_tokens') ?: $subscription->allowed_tokens }}" title="{{ tr('allowed_tokens') }}" placeholder="{{ tr('allowed_tokens') }}">
                            </div>
                        
                        </div>

                        <div class="col-md-6">

                            <label for="picture">{{ tr('picture') }}</label>

                            <div class="form-group">

                                <input type="file" class="form-control"  id="picture" name="picture"  accept="image/png, image/jpg"  >
                            </div>
                        
                        </div>

                        <div class="col-md-12">

                            <label for="simpleMde">{{ tr('description') }}</label>

                            <div class="form-group">

                                <textarea class="form-control" rows="5" placeholder="Enter description" id="description" name="description">{{ old('description') ?: $subscription->description}}</textarea>
                            </div>
                        </div>


                    </div>

                </div>

                <div class="box-footer">
                    
                    <button type="reset" class="btn btn-warning btn-default btn-squared px-30">{{tr('reset')}}</button>

                    <button type="submit" class="btn btn-info pull-right">{{tr('submit')}}</button>

                </div>

            </form>

        </div>

    </div>

</div>