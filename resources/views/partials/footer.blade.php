<footer>
    <div class="footer-content">
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <img class="footer-logo" alt="logo" src="{{ asset('images/logo-white.png')}}">
                </div>
                <div class="col-sm-4">
                    <h4>@lang('company.about-us')</h4>
                    <p>@lang('company.about-us-text')</p>
                </div>
                <div class="col-sm-4">
                    <h4>@lang('company.contact-us')</h4>
                    <p>
                        {{ Config::get('app.company_name') }}<br>
                        {{ Config::get('contact.address_line_1') }}<br>
                        {{ Config::get('contact.address_line_2') }}<br><br>
                        @lang('company.phone'): {{ Config::get('contact.phone') }}<br>
                        @lang('company.toll-free'): {{ Config::get('contact.toll_free') }}<br>
                        @lang('company.fax'): {{ Config::get('contact.fax') }}<br>
                        
                    </p>
                </div>
            </div>        
        </div> 
    </div>   
    <div class="footer-foot">
        <div class="container">
            <span>&copy; {{ Config::get('app.copyright_year')}} {{ Config::get('app.company_name')}}</span>
            <span class="pull-right social-icons">
                <a href="{{ Config::get('social.facebook') }}" target="_blank" title="Facebook"><i class="fa fa-facebook"></i></a>
                <a href="{{ Config::get('social.twitter') }}" target="_blank" title="Twitter"><i class="fa fa-twitter"></i></a>
                <a href="{{ Config::get('social.youtube') }}" target="_blank" title="YouTube"><i class="fa fa-youtube-play"></i></a>
            </span>
        </div>
    </div>
</footer>