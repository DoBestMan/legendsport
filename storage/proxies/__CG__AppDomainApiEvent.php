<?php

namespace DoctrineProxies\__CG__\App\Domain;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class ApiEvent extends \App\Domain\ApiEvent implements \Doctrine\ORM\Proxy\Proxy
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
            return ['__isInitialized__', '' . "\0" . 'App\\Domain\\ApiEvent' . "\0" . 'id', '' . "\0" . 'App\\Domain\\ApiEvent' . "\0" . 'apiId', '' . "\0" . 'App\\Domain\\ApiEvent' . "\0" . 'createdAt', '' . "\0" . 'App\\Domain\\ApiEvent' . "\0" . 'updatedAt', '' . "\0" . 'App\\Domain\\ApiEvent' . "\0" . 'sportId', '' . "\0" . 'App\\Domain\\ApiEvent' . "\0" . 'timeStatus', '' . "\0" . 'App\\Domain\\ApiEvent' . "\0" . 'startsAt', '' . "\0" . 'App\\Domain\\ApiEvent' . "\0" . 'teamAway', '' . "\0" . 'App\\Domain\\ApiEvent' . "\0" . 'teamHome', '' . "\0" . 'App\\Domain\\ApiEvent' . "\0" . 'scoreAway', '' . "\0" . 'App\\Domain\\ApiEvent' . "\0" . 'scoreHome', '' . "\0" . 'App\\Domain\\ApiEvent' . "\0" . 'provider', '' . "\0" . 'App\\Domain\\ApiEvent' . "\0" . 'odds', '' . "\0" . 'App\\Domain\\ApiEvent' . "\0" . 'tournamentEvents'];
        }

        return ['__isInitialized__', '' . "\0" . 'App\\Domain\\ApiEvent' . "\0" . 'id', '' . "\0" . 'App\\Domain\\ApiEvent' . "\0" . 'apiId', '' . "\0" . 'App\\Domain\\ApiEvent' . "\0" . 'createdAt', '' . "\0" . 'App\\Domain\\ApiEvent' . "\0" . 'updatedAt', '' . "\0" . 'App\\Domain\\ApiEvent' . "\0" . 'sportId', '' . "\0" . 'App\\Domain\\ApiEvent' . "\0" . 'timeStatus', '' . "\0" . 'App\\Domain\\ApiEvent' . "\0" . 'startsAt', '' . "\0" . 'App\\Domain\\ApiEvent' . "\0" . 'teamAway', '' . "\0" . 'App\\Domain\\ApiEvent' . "\0" . 'teamHome', '' . "\0" . 'App\\Domain\\ApiEvent' . "\0" . 'scoreAway', '' . "\0" . 'App\\Domain\\ApiEvent' . "\0" . 'scoreHome', '' . "\0" . 'App\\Domain\\ApiEvent' . "\0" . 'provider', '' . "\0" . 'App\\Domain\\ApiEvent' . "\0" . 'odds', '' . "\0" . 'App\\Domain\\ApiEvent' . "\0" . 'tournamentEvents'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (ApiEvent $proxy) {
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
    public function getApiId(): string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getApiId', []);

        return parent::getApiId();
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
    public function getSportId(): ?int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSportId', []);

        return parent::getSportId();
    }

    /**
     * {@inheritDoc}
     */
    public function getTimeStatus(): \App\Betting\TimeStatus
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTimeStatus', []);

        return parent::getTimeStatus();
    }

    /**
     * {@inheritDoc}
     */
    public function getStartsAt(): ?\DateTime
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getStartsAt', []);

        return parent::getStartsAt();
    }

    /**
     * {@inheritDoc}
     */
    public function getTeamAway(): string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTeamAway', []);

        return parent::getTeamAway();
    }

    /**
     * {@inheritDoc}
     */
    public function getTeamHome(): string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTeamHome', []);

        return parent::getTeamHome();
    }

    /**
     * {@inheritDoc}
     */
    public function getScoreAway(): ?int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getScoreAway', []);

        return parent::getScoreAway();
    }

    /**
     * {@inheritDoc}
     */
    public function getScoreHome(): ?int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getScoreHome', []);

        return parent::getScoreHome();
    }

    /**
     * {@inheritDoc}
     */
    public function getProvider(): string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getProvider', []);

        return parent::getProvider();
    }

    /**
     * {@inheritDoc}
     */
    public function isCancelled(): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isCancelled', []);

        return parent::isCancelled();
    }

    /**
     * {@inheritDoc}
     */
    public function isFinished(): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isFinished', []);

        return parent::isFinished();
    }

    /**
     * {@inheritDoc}
     */
    public function isFresherThan(int $seconds): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isFresherThan', [$seconds]);

        return parent::isFresherThan($seconds);
    }

    /**
     * {@inheritDoc}
     */
    public function result(\App\Betting\SportEventResult $sportEventResult): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'result', [$sportEventResult]);

        return parent::result($sportEventResult);
    }

    /**
     * {@inheritDoc}
     */
    public function isUpcoming()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isUpcoming', []);

        return parent::isUpcoming();
    }

    /**
     * {@inheritDoc}
     */
    public function updateOdds(\App\Betting\SportEventOdd $odds): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'updateOdds', [$odds]);

        parent::updateOdds($odds);
    }

    /**
     * {@inheritDoc}
     */
    public function getOddTypes(): array
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getOddTypes', []);

        return parent::getOddTypes();
    }

    /**
     * {@inheritDoc}
     */
    public function getOdds(string $betType): ?\App\Domain\ApiEventOdds
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getOdds', [$betType]);

        return parent::getOdds($betType);
    }

    /**
     * {@inheritDoc}
     */
    public function getAllOdds(): \Doctrine\Common\Collections\Collection
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAllOdds', []);

        return parent::getAllOdds();
    }

    /**
     * {@inheritDoc}
     */
    public function getTournamentEvents(): \Doctrine\Common\Collections\Collection
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTournamentEvents', []);

        return parent::getTournamentEvents();
    }

}
