<?php

namespace DoctrineProxies\__CG__\App\Domain;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Tournament extends \App\Domain\Tournament implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Proxy\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Proxy\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array<string, null> properties to be lazy loaded, indexed by property name
     */
    public static $lazyPropertiesNames = array (
);

    /**
     * @var array<string, mixed> default values of properties to be lazy loaded, with keys being the property names
     *
     * @see \Doctrine\Common\Proxy\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = array (
);



    public function __construct(?\Closure $initializer = null, ?\Closure $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return ['__isInitialized__', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'id', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'avatar', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'name', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'playersLimit', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'buyIn', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'chips', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'commission', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'lateRegister', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'lateRegisterRule', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'prizePool', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'state', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'timeFrame', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'createdAt', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'updatedAt', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'registrationDeadline', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'lateRegistrationDeadline', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'completedAt', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'bots', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'botsRegistered', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'players', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'events', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'bets', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'bettableEvents', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'autoEnd', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'playersRegistered'];
        }

        return ['__isInitialized__', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'id', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'avatar', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'name', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'playersLimit', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'buyIn', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'chips', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'commission', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'lateRegister', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'lateRegisterRule', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'prizePool', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'state', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'timeFrame', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'createdAt', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'updatedAt', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'registrationDeadline', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'lateRegistrationDeadline', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'completedAt', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'bots', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'botsRegistered', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'players', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'events', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'bets', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'bettableEvents', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'autoEnd', '' . "\0" . 'App\\Domain\\Tournament' . "\0" . 'playersRegistered'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Tournament $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy::$lazyPropertiesDefaults as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', []);
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', []);
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @deprecated no longer in use - generated code now relies on internal components rather than generated public API
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function getId(): int
    {
        if ($this->__isInitialized__ === false) {
            return  parent::getId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getId', []);

        return parent::getId();
    }

    /**
     * {@inheritDoc}
     */
    public function isAvatar(): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isAvatar', []);

        return parent::isAvatar();
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getName', []);

        return parent::getName();
    }

    /**
     * {@inheritDoc}
     */
    public function getPlayersLimit(): string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPlayersLimit', []);

        return parent::getPlayersLimit();
    }

    /**
     * {@inheritDoc}
     */
    public function getBuyIn(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBuyIn', []);

        return parent::getBuyIn();
    }

    /**
     * {@inheritDoc}
     */
    public function getChips(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getChips', []);

        return parent::getChips();
    }

    /**
     * {@inheritDoc}
     */
    public function getCommission(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCommission', []);

        return parent::getCommission();
    }

    /**
     * {@inheritDoc}
     */
    public function getLateRegister(): ?bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLateRegister', []);

        return parent::getLateRegister();
    }

    /**
     * {@inheritDoc}
     */
    public function getLateRegisterRule(): array
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLateRegisterRule', []);

        return parent::getLateRegisterRule();
    }

    /**
     * {@inheritDoc}
     */
    public function getPrizePool(): array
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPrizePool', []);

        return parent::getPrizePool();
    }

    /**
     * {@inheritDoc}
     */
    public function getState(): \App\Tournament\Enums\TournamentState
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getState', []);

        return parent::getState();
    }

    /**
     * {@inheritDoc}
     */
    public function getTimeFrame(): string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTimeFrame', []);

        return parent::getTimeFrame();
    }

    /**
     * {@inheritDoc}
     */
    public function getCreatedAt(): ?\DateTime
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCreatedAt', []);

        return parent::getCreatedAt();
    }

    /**
     * {@inheritDoc}
     */
    public function getUpdatedAt(): ?\DateTime
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUpdatedAt', []);

        return parent::getUpdatedAt();
    }

    /**
     * {@inheritDoc}
     */
    public function getRegistrationDeadline(): ?\DateTime
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRegistrationDeadline', []);

        return parent::getRegistrationDeadline();
    }

    /**
     * {@inheritDoc}
     */
    public function getLateRegistrationDeadline(): ?\DateTime
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLateRegistrationDeadline', []);

        return parent::getLateRegistrationDeadline();
    }

    /**
     * {@inheritDoc}
     */
    public function getCompletedAt(): ?\DateTime
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCompletedAt', []);

        return parent::getCompletedAt();
    }

    /**
     * {@inheritDoc}
     */
    public function getBots(): ?array
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBots', []);

        return parent::getBots();
    }

    /**
     * {@inheritDoc}
     */
    public function getBotsRegistered(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBotsRegistered', []);

        return parent::getBotsRegistered();
    }

    /**
     * {@inheritDoc}
     */
    public function getPlayers()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPlayers', []);

        return parent::getPlayers();
    }

    /**
     * {@inheritDoc}
     */
    public function shouldAutoEnd(): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'shouldAutoEnd', []);

        return parent::shouldAutoEnd();
    }

    /**
     * {@inheritDoc}
     */
    public function getEvents(): \Doctrine\Common\Collections\Collection
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEvents', []);

        return parent::getEvents();
    }

    /**
     * {@inheritDoc}
     */
    public function canBetBePlaced(): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'canBetBePlaced', []);

        return parent::canBetBePlaced();
    }

    /**
     * {@inheritDoc}
     */
    public function getBettableEvents(): \Doctrine\Common\Collections\Collection
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBettableEvents', []);

        return parent::getBettableEvents();
    }

    /**
     * {@inheritDoc}
     */
    public function getBets(): \Doctrine\Common\Collections\Collection
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBets', []);

        return parent::getBets();
    }

    /**
     * {@inheritDoc}
     */
    public function placeStraightBet(\App\Domain\TournamentPlayer $tournamentPlayer, int $wager, \App\Domain\BetItem $betItem): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'placeStraightBet', [$tournamentPlayer, $wager, $betItem]);

        parent::placeStraightBet($tournamentPlayer, $wager, $betItem);
    }

    /**
     * {@inheritDoc}
     */
    public function placeParlayBet(\App\Domain\TournamentPlayer $tournamentPlayer, int $wager, \App\Domain\BetItem ...$betItems): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'placeParlayBet', [$tournamentPlayer, $wager, $betItems]);

        parent::placeParlayBet($tournamentPlayer, $wager, ...$betItems);
    }

    /**
     * {@inheritDoc}
     */
    public function getBotsToRegister(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBotsToRegister', []);

        return parent::getBotsToRegister();
    }

    /**
     * {@inheritDoc}
     */
    public function registerBot(\App\Domain\Bot $bot): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'registerBot', [$bot]);

        parent::registerBot($bot);
    }

    /**
     * {@inheritDoc}
     */
    public function registerPlayer(\App\Domain\User $player): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'registerPlayer', [$player]);

        parent::registerPlayer($player);
    }

    /**
     * {@inheritDoc}
     */
    public function addEvent(\App\Domain\ApiEvent $event): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addEvent', [$event]);

        parent::addEvent($event);
    }

    /**
     * {@inheritDoc}
     */
    public function getEvent($eventId): ?\App\Domain\TournamentEvent
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEvent', [$eventId]);

        return parent::getEvent($eventId);
    }

    /**
     * {@inheritDoc}
     */
    public function getPrizePoolMoney()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPrizePoolMoney', []);

        return parent::getPrizePoolMoney();
    }

    /**
     * {@inheritDoc}
     */
    public function getPrizes(): array
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPrizes', []);

        return parent::getPrizes();
    }

}
