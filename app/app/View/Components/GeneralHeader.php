<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class GeneralHeader extends Component
{

    public $logo;
    public $title;
    public $subtitle;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->logo = 'https://www.uabcs.mx/transparencia/assets/images/logo_uabcs.png';
        $this->title = 'Secretaria General';
        $this->subtitle = 'Programa de Estímulos al Desempeño del personal Docente';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.general-header');
    }
}
