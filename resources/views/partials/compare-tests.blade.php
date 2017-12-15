<div class="compare-tests selected-product-row">
    <div class="row">
        <div class="col-xs-3">&nbsp;</div>
        @foreach ($products as $product)
        <div class="col-xs-3{{ $product->selected ? ' selected-product selected-product-top' : '' }}">
            <h4>@lang('products.' . $product->product_id . '.title')</h4>
            <p>${{ number_format($product->price, 2, '.', ',')  }}</p>
        </div>
        @endforeach
    </div>
    @foreach ($features as $i => $feature)
        <div class="row{{ $i % 2 == 0 ? ' alt': '' }} selected-product-row">
            <div class="col-xs-3">
                <h5>{{ $feature->title }}</h5>
            </div>
            @foreach ($products as $product)
                <div class="col-xs-3{{ $product->selected ? ' selected-product' : '' }}">
                    <h5>
                        @if (\App\Product::hasFeature($product, $feature->id)) <i class="material-icons">done</i>
                        @endif
                        
                    </h5>
                </div>
            @endforeach
        </div>
    @endforeach
    <div class="row">
        <div class="col-xs-3">&nbsp;</div>
        @foreach ($products as $product)
        <div class="col-xs-3{{ $product->selected ? ' selected-product selected-product-top' : '' }}">
            <h4>@lang('products.' . $product->product_id . '.title')</h4>
            <p>${{ number_format($product->price, 2, '.', ',')  }}</p>
        </div>
        @endforeach
    </div>
    <div class="row">
        <div class="col-xs-3">
            <div class="product-actions">
                <a href="{{ LocaleRoute::route('kit.new') }}" class="btn btn-default">@lang('forms.buttons.start-over')</a>
            </div>
        </div>
        @foreach ($products as $product)
        <div class="col-xs-3{{ $product->selected ? ' selected-product-bottom' : '' }}">
            <div class="product-actions">
                @if ($product->selected)
                    <a href="{{ LocaleRoute::route('kit.payment', [ 'barcode' => $barcode ]) }}" class="btn btn-primary">@lang('forms.buttons.continue')</a>
                @else
                    <form method="POST" action="{{ LocaleRoute::route('kit.upgrade', ['barcode' => $barcode]) }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="testId" id="testId" value="{{ $product->product_id }}" />
                        <input type="submit" class="btn btn-primary" value="{{ __('forms.buttons.upgrade') }}" />
                    </form>
                
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>