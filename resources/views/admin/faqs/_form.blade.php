<div class="row">

    <div class="col-lg-12 col-12">

        <div class="box">

            <div class="box-header with-border">

                <h3 class="box-title">{{ $faq->id ? tr('edit_faq') : tr('add_faq') }}</h3>

            </div>
            
            <form class="form-horizontal" action="{{ (Setting::get('is_demo_control_enabled') == YES) ? '#' : route('admin.faqs.save') }}" method="POST" enctype="multipart/form-data" role="form">

            @csrf

                <div class="box-body">

                    <input type="hidden" name="faq_id" id="faq_id" value="{{ $faq->id}}">

                    <div class="col-md-12 mb-3">

                        <div class="form-group">

                            <label for="question">{{tr('question')}}*</label>
                            <input type="text" id="question" name="question" class="form-control" placeholder="{{tr('question_placeholder')}}" value="{{ $faq->question ?: old('question') }}" required>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">

                        <div class="form-group">
                            <label for="answer">{{tr('answer')}}*</label>
                            
                             <textarea rows="5" class="form-control" required name="answer" placeholder="{{ tr('answer') }}">{{old('answer') ?: $faq->answer}}</textarea>
                           
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
