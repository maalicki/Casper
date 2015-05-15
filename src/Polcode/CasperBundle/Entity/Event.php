<?php

namespace Polcode\CasperBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Polcode\CasperBundle\Validator\Constraints as ValidateAssert;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Polcode\CasperBundle\Entity\EventRepository")
 * @ORM\Table(name="events")
 * @ValidateAssert\EventFormFields
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
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     *
     */
    private $user;

    /**
     * @ORM\Column(name="eventname", type="string", length=255, nullable=false)
     */
    private $eventName;
    
    /**
     * @ORM\Column(type="text",  nullable=false)
     */
    private $description;
    
    /**
     * @ORM\Column(name="location", type="text", nullable=false)
     * 
     */
    private $location;
    
    /**
    *
    * @ORM\Column(name="latitude", type="float", nullable=false)
    */
    private $latitude;

    /**
    *
    * @ORM\Column(name="longitude", type="float", precision=11, scale=8, nullable=false)
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
    * @ORM\Column(name="private",  type="boolean")
    */
    private $private;

    /**
    *
    * @ORM\Column(name="deleted", type="boolean")
    */
    private $deleted;
    
    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="joinedEvents")
     * @ORM\JoinTable(name="users_events")
     * */
    private $joinedUsers;
    
    /**
     * @ORM\OneToOne(targetEntity="Invitations", mappedBy="event")
     * */
    private $invitation;
    
    
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
    
    public function getEventSignUpTimeleft($format = 'min') {
        $diff=$this->getEventSignUpEndDate()->diff( new \DateTime );
        
        if ( $diff->invert ){
            switch ($format) {
                case 'min':
                        $hours   = $diff->format('%h'); 
                        $minutes = $diff->format('%i');
                        $time = ($hours * 60 + $minutes);
                    break;

                default:
                    $time = $diff->format('%d days, %hh %imin');
                    break;
            }
            return $time;
        }
        return false;
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set eventName
     *
     * @param string $eventName
     * @return Event
     */
    public function setEventName($eventName)
    {
        $this->eventName = $eventName;

        return $this;
    }

    /**
     * Get eventName
     *
     * @return string 
     */
    public function getEventName()
    {
        return $this->eventName;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Event
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set location
     *
     * @param string $location
     * @return Event
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string 
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     * @return Event
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     * @return Event
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set eventStart
     *
     * @param \DateTime $eventStart
     * @return Event
     */
    public function setEventStart($eventStart)
    {
        $this->eventStart = $eventStart;

        return $this;
    }

    /**
     * Get eventStart
     *
     * @return \DateTime 
     */
    public function getEventStart()
    {
        return $this->eventStart;
    }

    /**
     * Set eventStop
     *
     * @param \DateTime $eventStop
     * @return Event
     */
    public function setEventStop($eventStop)
    {
        $this->eventStop = $eventStop;

        return $this;
    }

    /**
     * Get eventStop
     *
     * @return \DateTime 
     */
    public function getEventStop()
    {
        return $this->eventStop;
    }

    /**
     * Set eventSignUpEndDate
     *
     * @param \DateTime $eventSignUpEndDate
     * @return Event
     */
    public function setEventSignUpEndDate($eventSignUpEndDate)
    {
        $this->eventSignUpEndDate = $eventSignUpEndDate;

        return $this;
    }

    /**
     * Get eventSignUpEndDate
     *
     * @return \DateTime 
     */
    public function getEventSignUpEndDate()
    {
        return $this->eventSignUpEndDate;
    }

    /**
     * Set maxGuests
     *
     * @param integer $maxGuests
     * @return Event
     */
    public function setMaxGuests($maxGuests)
    {
        $this->maxGuests = $maxGuests;

        return $this;
    }

    /**
     * Get maxGuests
     *
     * @return integer 
     */
    public function getMaxGuests()
    {
        return $this->maxGuests;
    }

    /**
     * Set private
     *
     * @param boolean $private
     * @return Event
     */
    public function setPrivate($private)
    {
        $this->private = $private;

        return $this;
    }

    /**
     * Get private
     *
     * @return boolean 
     */
    public function getPrivate()
    {
        return $this->private;
    }

    /**
     * Set deleted
     *
     * @param boolean $deleted
     * @return Event
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return boolean 
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set user
     *
     * @param \Polcode\CasperBundle\Entity\User $user
     * @return Event
     */
    public function setUser(\Polcode\CasperBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Polcode\CasperBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->joinedUsers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add joinedUsers
     *
     * @param \Polcode\CasperBundle\Entity\User $joinedUsers
     * @return Event
     */
    public function addJoinedUser(\Polcode\CasperBundle\Entity\User $joinedUsers)
    {
        $this->joinedUsers[] = $joinedUsers;

        return $this;
    }

    /**
     * Remove joinedUsers
     *
     * @param \Polcode\CasperBundle\Entity\User $joinedUsers
     */
    public function removeJoinedUser(\Polcode\CasperBundle\Entity\User $joinedUsers)
    {
        $this->joinedUsers->removeElement($joinedUsers);
    }

    /**
     * Get joinedUsers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getJoinedUsers()
    {
        return $this->joinedUsers;
    }

    /**
     * Set invitation
     *
     * @param \Polcode\CasperBundle\Entity\Invitations $invitation
     * @return Event
     */
    public function setInvitation(\Polcode\CasperBundle\Entity\Invitations $invitation = null)
    {
        $this->invitation = $invitation;

        return $this;
    }

    /**
     * Get invitation
     *
     * @return \Polcode\CasperBundle\Entity\Invitations 
     */
    public function getInvitation()
    {
        return $this->invitation;
    }
}
