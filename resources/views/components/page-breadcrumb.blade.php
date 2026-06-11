<div class="page-breadcrumb d-flex align-items-center m-2">
    @if ($subMenu != null)
        <div class="breadcrumb-title pe-2">{{ $subMenu }}</div> | 
    @endif
    <div class=" mt-1">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0" style="border: none!important;" >
                @if ($currentPage != 'Dashboard')
                <li class="breadcrumb-item ">
                    <a href="{{ route('member.dashboard') }}" class="ps-2" style="text-decoration: none;">
                        <i class="fa fa-home" style="font-size: 20px;"></i> 
                    </a>
                </li>
                <li class="breadcrumb-item ">
                    <i class="fa fa-angle-right" style="font-size: 20px;"></i>
                </li>
                @endif
                <li class="breadcrumb-item active" style="margin-top: -2px;" aria-current="page">{{ $currentPage }}</li>
            </ol>
        </nav>
    </div>
</div>