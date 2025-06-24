<?php

namespace App\Domain\Vos;

class GramSet {
    public PartOfSpeech $partOfSpeech;
    /** [ category => [values] ] */
    public array $set;
}