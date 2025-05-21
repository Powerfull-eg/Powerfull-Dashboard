@if ($adminType == 'shopAdmin')
    @include("dashboard.index-types.$adminType")
@else
    @include("dashboard.index-types.personnel")
@endif