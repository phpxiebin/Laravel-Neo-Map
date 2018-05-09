<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        @if (count($breadcrumbs))
            <h2>{{ ($breadcrumb = Breadcrumbs::current()) ? $breadcrumb->title : 'Fallback Title' }}</h2>
            <ul class="breadcrumb">
                @foreach ($breadcrumbs as $breadcrumb)
                    @if ($breadcrumb->url && !$loop->last)
                        <li><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
                    @else
                        <li class="active">{{ $breadcrumb->title }}</li>
                    @endif
                @endforeach
            </ul>
        @endif
    </div>
    <div class="col-lg-2">

    </div>
</div>