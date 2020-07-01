<?php

namespace App\Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tournament
 *
 * @ORM\Table(name="tournaments")
 * @ORM\Entity
 */
class Tournament
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var bool
     *
     * @ORM\Column(name="avatar", type="boolean", nullable=false)
     */
    private $avatar = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="players_limit", type="string", length=0, nullable=false)
     */
    private $playersLimit;

    /**
     * @var int
     *
     * @ORM\Column(name="buy_in", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $buyIn;

    /**
     * @var int
     *
     * @ORM\Column(name="chips", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $chips;

    /**
     * @var int
     *
     * @ORM\Column(name="commission", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $commission;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="late_register", type="boolean", nullable=true)
     */
    private $lateRegister;

    /**
     * @var json|null
     *
     * @ORM\Column(name="late_register_rule", type="json", nullable=true)
     */
    private $lateRegisterRule;

    /**
     * @var json
     *
     * @ORM\Column(name="prize_pool", type="json", nullable=false)
     */
    private $prizePool;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255, nullable=false)
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="time_frame", type="string", length=0, nullable=false)
     */
    private $timeFrame;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="registration_deadline", type="datetime", nullable=true)
     */
    private $registrationDeadline;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="late_registration_deadline", type="datetime", nullable=true)
     */
    private $lateRegistrationDeadline;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="completed_at", type="datetime", nullable=true)
     */
    private $completedAt;
}
