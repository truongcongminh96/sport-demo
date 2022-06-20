@php
    $tagsEn = App\Models\Product::groupBy('product_tags_en')->select('product_tags_en')->get();
    $tagsVn = App\Models\Product::groupBy('product_tags_vn')->select('product_tags_vn')->get();
@endphp

<!-- ============================================== PRODUCT TAGS ============================================== -->
<div class="sidebar-widget product-tag wow fadeInUp">
    <h3 class="section-title">Product tags</h3>
    <div class="sidebar-widget-body outer-top-xs">

        <div class="tag-list">
            @if(session()->get('language') == 'vietnam')
                @foreach($tagsVn as $tag)
                    <a class="item active" title="Smartphone" href="{{ url('product/tag/' . $tag->product_tags_vn) }}">{{ str_replace(',', ' ',$tag->product_tags_vn) }}</a>
                @endforeach
            @else
                @foreach($tagsEn as $tag)
                    <a class="item active" title="Smartphone" href="{{ url('product/tag/' . $tag->product_tags_en) }}">{{ str_replace(',', ' ',$tag->product_tags_en) }}</a>
                @endforeach
            @endif

        </div>
        <!-- /.tag-list -->
    </div>
    <!-- /.sidebar-widget-body -->
</div>
<!-- /.sidebar-widget -->
<!-- ============================================== PRODUCT TAGS : END ============================================== -->
