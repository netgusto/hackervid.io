<?php

namespace AppBundle\Services;

use ModelBundle\Entity\Item;
use AppBundle\Services\Utils;

class RankingHelper {

    protected $utils;

    public function __construct(Utils $utils) {
        $this->utils = $utils;
    }

    public function computeItemMomentum(Item $item, DateTime $refdate = null) {

        $gravity = 1.8;
        if(is_null($refdate)) $refdate = new \DateTime();

        // Score = (P-1) / (T+2)^G
        // Where
        // P = points of an item (and -1 is to negate submitters vote)
        // T = time since submission (in hours)
        // G = Gravity, defaults to 1.8 in news.arc

        $agehours = $this->utils->getAgeHours($item->getCreationDate(), $refdate);
        return ($item->getPoints()-1) / pow($agehours+2, $gravity);
    }
}