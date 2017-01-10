<?php

namespace Zogs\UtilsBundle\Twig;


class JavascriptDateExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(            
            new \Twig_SimpleFilter('jsDate',array($this,'createDate')),
        );
    }

    public function createDate($datetime, $format = 'Y-m-d H:i:s')
    {       
        if($datetime instanceof \Datetime)
            return $this->printJs($datetime);
        elseif(is_string($datetime)) {            
            $datetime = \Datetime::createFromFormat($format,$datetime);
            return $this->printJs($datetime);
        }
        else
            return 'unknown';
    }

    public function getName()
    {
        return 'js_date';
    }

    public function printJs(\DateTime $datetime)
    {
        return 'Date.UTC('.$datetime->format('Y').','.($datetime->format('n') - 1).','.$datetime->format('d').')';
    }
}