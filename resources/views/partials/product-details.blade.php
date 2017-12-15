<div class="media">
    <div class="pull-left text-center">
        {{--  <img class="img-rounded" data-src="holder.js/128x128?theme=default&text={{ __('products.' . $kit->test_id . '.slug') }}"><br>  --}}
        <img class="img-rounded" data-src="holder.js/128x128?theme=default&text=Kit Image"><br>
        <i class="fa fa-barcode"></i> {{ $kit->barcode }}
    </div>
    <div class="media-body">
        <h4>@lang('products.whats-included'):</h4>
        @foreach ($features as $feature)
            <div class="row feature-row">
                <div class="col-xs-1"><i class="fa fa-check"></i></div>
                <div class="col-xs-10">@lang($feature->title)</div>
            </div>
        @endforeach
    </div>
</div>