<div class="row">
    <div class="col-sm-6">
        <lorem-ipsum :paragraphs="1"></lorem-ipsum>
        <img data-src="holder.js/100px100?text=Image" />
    </div>
    <div class="col-sm-6">
        <!-- 16:9 aspect ratio -->
        <div class="embed-responsive embed-responsive-16by9">
            <iframe src="https://www.youtube.com/embed/M2NFwBWcYYk" gesture="media" allow="encrypted-media" allowfullscreen></iframe>
        </div>
    </div>
</div>
<div class="padding-lg text-center">
    <a href="{{LocaleRoute::route('kit.mail') }}" class="btn btn-primary">@lang('forms.buttons.next') <i class="fa fa-arrow-circle-right"></i> @lang('forms.buttons.mailing-instructions')</a>
</div>