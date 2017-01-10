<?php

namespace Zogs\UtilsBundle\Twig;


class AgeCalculatorExtension extends \Twig_Extension
{
    private $today;

    public function __construct()
    {        
        $this->today = new \Datetime('now');
    }

    public function getFilters()
    {
        return array(            
            new \Twig_SimpleFilter('calculAge',array($this,'calculAge')),
        );
    }

    public function calculAge($datetime)
    {        
        if($datetime instanceof \Datetime)
            return $this->today->diff($datetime)->format('%y');
        else
            return 'unknown';
    }

    public function getName()
    {
        return 'age_calculator';
    }
}