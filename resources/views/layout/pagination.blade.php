@if (null != $links)
    @if(isset($type) && $type == 'bottom')
        <div class="pagination-bottom">
            {{ $links }}
        </div>
    @else
        <div class="pagination-top">
            {{ $links }}
        </div>
    @endif
@endif