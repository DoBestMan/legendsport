<?php

namespace App\Domain;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity() */
class Bot extends User
{
    public static function create($name)
    {
        return new self(
            $name,
            $name . '@bots.legendsports.bet',
            '...',
            '',
            '',
           \DateTime::createFromFormat('U', 0)
        );
    }
}
