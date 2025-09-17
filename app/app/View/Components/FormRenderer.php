<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
namespace App\View\Components;

use Illuminate\View\Component;

class FormRenderer extends Component
{
    public $forms;

    /**
     * Create a new component instance.
     *
     * @param array $forms
     */
    public function __construct($forms)
    {
        $this->forms = $forms;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.form-renderer');
    }
}