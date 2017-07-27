<?php

namespace AppBundle\Services;

use Wookieb\RelativeDate\Formatters\BasicFormatter;
use Wookieb\RelativeDate\Calculators\TimeAgoDateDiffCalculator;

class RelativeDateHelper {

    protected $now;
    protected $formatter;
    protected $calculator;

    public function __construct() {

        $this->now = new \DateTime();
        $this->formatter = new BasicFormatter();
        $this->calculator = TimeAgoDateDiffCalculator::upTo2Weeks();
    }

    public function get(\DateTime $date, \DateTime $ref = null) : string {
        if(is_null($ref)) {
            $ref = $this->now;
        }

        return $this->formatter->format($this->calculator->compute($date, $ref));
    }
}