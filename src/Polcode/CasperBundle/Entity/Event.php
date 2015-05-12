<?php

namespace Polcode\CasperBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="events")
 * 
 */
class Event {
    /**
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="events")
     * @ORM\JoinColumn(name="userid", referencedColumnName="id")
     *
     */
    private $userId;

    /**
     * @ORM\Column(name="eventname", type="string", length=255, nullable=false)
     */
    private $eventName;
    
    /**
     * @ORM\Column(type="text",  nullable=false)
     */
    private $description;
    
    /**
     * @ORM\Column(name="location", type="string", length=255, nullable=false)
     * 
     */
    private $location;
    
    /**
    *
    * @ORM\Column(name="latitude", type="decimal", precision=10, scale=8, nullable=false)
    */
    private $latitude;

    /**
    *
    * @ORM\Column(name="longitude", type="decimal", precision=11, scale=8, nullable=false)
    */
    private $longitude;

    /**
    *
    * @ORM\Column(name="eventstart", type="datetime",  nullable=false)
    */
    private $eventStart;

    /**
    *
    * @ORM\Column(name="eventstop", type="datetime",  nullable=false)
    */
    private $eventStop;

    /**
    *
    * @ORM\Column(name="eventsignupenddate", type="datetime",  nullable=false)
    */
    private $eventSignUpEndDate;

    /**
    *
    * @ORM\Column(name="maxguests", type="integer",  nullable=true, options={"unsigned"=true})
    */
    private $maxGuests;

    /**
    *
    * @ORM\Column(name="private",  length=1, columnDefinition="TINYINT DEFAULT 1 NOT NULL")
    */
    private $private;

    /**
    *
    * @ORM\Column(name="deleted",  length=1, columnDefinition="TINYINT DEFAULT 0 NOT NULL")
    */
    private $deleted;
    

    function getId() {
        return $this->id;
    }

    function getUserId() {
        return $this->userId;
    }

    function getEventName() {
        return $this->eventName;
    }

    function getDescription() {
        return $this->description;
    }

    function getLocation() {
        return $this->location;
    }

    function getLatitude() {
        return $this->latitude;
    }

    function getLongitude() {
        return $this->longitude;
    }

    function getEventStart() {
        return $this->eventStart;
    }

    function getEventStop() {
        return $this->eventStop;
    }

    function getEventSignUpEndDate() {
        return $this->eventSignUpEndDate;
    }

    function getMaxGuests() {
        return $this->maxGuests;
    }

    function getPrivate() {
        return $this->private;
    }

    function getDeleted() {
        return $this->deleted;
    }

    function setUserId($userId) {
        $this->userId = $userId;
        return $this;
    }

    function setEventName($eventName) {
        $this->eventName = $eventName;
        return $this;
    }

    function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    function setLocation($location) {
        $this->location = $location;
        return $this;
    }

    function setLatitude($latitude) {
        $this->latitude = $latitude;
        return $this;
    }

    function setLongitude($longitude) {
        $this->longitude = $longitude;
        return $this;
    }

    function setEventStart($eventStart) {
        $this->eventStart = $eventStart;
        return $this;
    }

    function setEventStop($eventStop) {
        $this->eventStop = $eventStop;
        return $this;
    }

    function setEventSignUpEndDate($eventSignUpEndDate) {
        $this->eventSignUpEndDate = $eventSignUpEndDate;
        return $this;
    }

    function setMaxGuests($maxGuests) {
        $this->maxGuests = $maxGuests;
        return $this;
    }

    function setPrivate($private) {
        $this->private = $private;
        return $this;
    }

    function setDeleted($deleted) {
        $this->deleted = $deleted;
        return $this;
    }

    public function isEventDatesValid( ) {
        
        $diff=$this->getEventStop()->diff( $this->getEventStart() );
        
        if ( $diff->invert ){
            return true;
        }
        
        return false;
    }
    
    public function isEventSignUpValid( ) {
        
        $diff=$this->getEventStop()->diff( $this->getEventSignUpEndDate() );
        
        if ( $diff->invert ){
            return true;
        }
        
        return false;
    }
        

}
