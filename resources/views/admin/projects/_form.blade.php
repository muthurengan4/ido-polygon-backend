<div class="row">

    <div class="col-lg-12 col-12">

        <div class="box">
            <div class="box-header with-border">
                
                <h3 class="box-title">{{ $project->id ? tr('edit_project') : tr('add_project') }}</h3>

                <div class="heading-elements pull-right">
                    <a href="{{route('admin.projects.index') }}" class="btn btn-primary"><i class="ft-eye icon-left"></i>{{ tr('view_projects') }}</a>
                </div>

            </div>
                
            <form action="{{ (Setting::get('is_demo_control_enabled') == YES) ? '#' : route('admin.projects.save') }}" method="POST" enctype="multipart/form-data" role="form">
                
                @csrf

                <div class="box-body">

                    <div class="callout bg-pale-secondary">
                        <h4>Notes:</h4>
                        <p>
                            <ul>
                                <li>Admin can add project details on behalf of any project owner. </li>
                            </ul>
                        </p>
                    </div>

                    <div class="row">

                        <input type="hidden" name="project_id" id="project_id" value="{{$project->id}}">

                        <!-- <div class="col-md-6">
                            <label for="page">
                                {{tr('owner_name')}}
                                <span class="required" aria-required="true"> <span class="admin-required">*</span> </span>
                            </label>

                            <div class="form-group">
                                
                                <select class="form-control select2" name="user_id" required>

                                    <option value="">{{tr('select_user_name')}}</option>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}"  @if($user->is_selected == YES || $user->id == $project->user_id) selected="true" @endif>
                                            {{$user->name}}
                                        </option>
                                    @endforeach
                                
                                </select>
                            </div>
                        </div> -->

                        <div class="col-md-6">
                                
                            <label for="project_name">{{ tr('project_name') }} *</label>

                            <div class="form-group">                                  
                                
                                <input type="text" id="name" name="name" class="form-control" placeholder="{{ tr('project_name') }}" value="{{old('name') ?: $project->name}}" required>

                            </div>

                        </div>   

                        <div class="col-md-6">

                            <label for="from_wallet_address">{{ tr('project_owner_wallet_address') }} *</label>

                            <div class="form-group">
                                <input type="text" min="1" step="any" class="form-control" id="from_wallet_address" name="from_wallet_address" placeholder="{{ tr('project_owner_wallet_address') }}" value="{{ old('from_wallet_address') ?: $project->from_wallet_address}}" required/>
                            </div>
                        </div>

                        <div class="col-md-6">

                            <label for="page">{{tr('start_time')}} *</label>

                            <div class="form-group">
                                    
                                <input class="form-control datetimepicker" name="start_time" type="text" value="{{ old('start_time') ?: $project->start_time}}">

                            </div>
                        </div>

                        <div class="col-md-6">

                            <label for="page">{{tr('end_time')}} *</label>

                            <div class="form-group">
                                    
                                <input class="form-control datetimepicker" name="end_time" type="text" value="{{ old('end_time') ?: $project->end_time}}">

                            </div>
                        </div>

                        <div class="col-md-6">

                            <label for="next_round_start_time">{{ tr('next_round_start_time') }}({{tr('in_minutes')}}) *</label>

                            <div class="form-group">
                                <input type="number" min="1" step="any" class="form-control" id="next_round_start_time" name="next_round_start_time" placeholder="{{ tr('next_round_start_time') }}" value="{{ old('next_round_start_time') ?: $project->next_round_start_time}}" required/>
                            </div>
                        </div>

                        <div class="col-md-6">

                            <label for="total_tokens">{{ tr('total_tokens') }} *</label>

                            <div class="form-group">
                                <input type="number" min="1" step="any" class="form-control" id="total_tokens" name="total_tokens" placeholder="{{ tr('total_tokens') }}" value="{{ old('total_tokens') ?: $project->total_tokens}}" required/>
                            </div>
                        </div>

                        <div class="col-md-6">

                            <label for="allowed_tokens">{{ tr('allowed_tokens') }} *</label>

                            <div class="form-group">
                                <input type="number" min="1" step="any" class="form-control" id="allowed_tokens" name="allowed_tokens" placeholder="{{ tr('allowed_tokens') }}" value="{{ old('allowed_tokens') ?: $project->allowed_tokens}}" required/>
                            </div>
                        </div>

                        <div class="col-md-6">

                            <label for="contract_address">{{ tr('contract_address') }} *</label>

                            <div class="form-group">
                                <input type="text" min="1" step="any" class="form-control" id="contract_address" name="contract_address" placeholder="{{ tr('contract_address') }}" value="{{ old('contract_address') ?: $project->contract_address}}" required/>
                            </div>
                        </div>

                        <div class="col-md-6">

                            <label for="decimal_points">{{ tr('decimal_points') }} (By default 18)</label>

                            <div class="form-group">
                                <input type="number" min="1" step="any" class="form-control" id="decimal_points" name="decimal_points" placeholder="{{ tr('decimal_points') }}" value="{{ old('decimal_points') ?: $project->decimal_points}}" required/>
                            </div>
                        </div>

                        <div class="col-md-6">

                            <label for="total_tokens">{{ tr('swap_rate') }} ({{tr('conversion_value')}}) * </label>

                            <div class="form-group">
                                <input type="number" min="0" step="any" class="form-control" id="exchange_rate" name="exchange_rate" placeholder="{{ tr('exchange_rate') }}" value="{{ old('exchange_rate') ?: $project->exchange_rate}}" required />
                            </div>
                        </div>

                        <div class="col-md-6">

                            <label for="token_symbol">{{ tr('token_symbol') }} * (Ex: BTC, ETH)</label>

                            <div class="form-group">
                                <input type="text" minlength="1" class="form-control" id="token_symbol" name="token_symbol" placeholder="{{ tr('token_symbol') }}" value="{{ old('token_symbol') ?: $project->token_symbol}}" required />
                            </div>
                        </div>

                        <div class="col-md-6">

                            <label>{{ tr('select_picture') }}</label>
                            <p class="text-muted mt-0 mb-0">{{tr('image_validate')}}</p>
                            <div class="form-group">

                                <input type="file" class="form-control"  id="picture" name="picture" accept="image/*"  >
                            
                            </div>
                        </div>   

                        <div class="col-md-12">

                            <label>{{ tr('description') }} *</label>

                            <div class="form-group">

                                <textarea name="description" rows="5" class="form-control" required>{{ $project->description ?: old('description') }}</textarea>
                            </div>
                        </div>


                    </div>

                    <div class="row">

                        <div class="col-lg-12">
                            <h3>{{tr('social_settings')}} ({{tr('optional')}})</h3>
                            <hr>
                        </div>

                        <div class="col-md-6">

                            <label for="website">{{ tr('website') }}</label>

                            <div class="form-group">
                                <input type="url" id="website" name="website" class="form-control" placeholder="{{ tr('website') }}" value="{{ $project->website ?: old('website') }}">
                            </div>
                        </div>     

                        <div class="col-md-6">

                            <label for="telegram_link">{{ tr('telegram_link') }}</label>

                            <div class="form-group">                            
                                <input type="url" id="telegram_link" name="telegram_link" class="form-control" placeholder="{{ tr('telegram_link') }}" value="{{ $project->telegram_link ?: old('telegram_link') }}">
                            </div>
                        </div>

                        <div class="col-md-6">

                            <label for="medium_link">{{ tr('medium_link') }}</label>

                            <div class="form-group">                            
                                <input type="url" id="medium_link" name="medium_link" class="form-control" placeholder="{{ tr('medium_link') }}" value="{{ $project->medium_link ?: old('  medium_link') }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="facebook_link">{{ tr('facebook_link') }}</label>

                            <div class="form-group">
                                
                                <input type="url" id="facebook_link" name="facebook_link" class="form-control" placeholder="{{ tr('facebook_link') }}" value="{{ $project->facebook_link ?: old('facebook_link') }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="twitter_link">{{ tr('twitter_link') }}</label>
                            <div class="form-group">
                                
                                <input type="url" id="twitter_link" name="twitter_link" class="form-control" placeholder="{{ tr('twitter_link') }}" value="{{ $project->twitter_link ?: old('twitter_link') }}">
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

</div>