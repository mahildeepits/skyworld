<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PageBreadcrumb extends Component
{
    /**
     * Create a new component instance.
     */
    public $currentPage;
    public $subMenu;
    public function __construct($currentPage,$subMenu = null)
    {
        $this->currentPage = $currentPage;
        $this->subMenu = $subMenu;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.page-breadcrumb', ['subMenu' => $this->subMenu, 'currentPage' => $this->currentPage]);
    }
}
