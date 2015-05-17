<?php

namespace Polcode\CasperBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="invitations")
 */
class Invitations {
    /**
     * @ORM\Id 
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\GeneratedValue
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="sendInvitations")
     * @ORM\JoinColumn(name="sender_id", referencedColumnName="id", onDelete="CASCADE")
     **/
    private $sender;
    
    /**
     * @ORM\Column(type="string")
     */
    private $description;
    
    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="receivedInvitations")
     * @ORM\JoinTable(name="users_receivedInvitations")
     **/
    private $receivers;
    
    /**
     * @ORM\OneToOne(targetEntity="Event", inversedBy="invitation")
     * @ORM\JoinColumn(name="id", referencedColumnName="id", onDelete="CASCADE")
     **/
    private $event;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->receivers = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function removeAllReceiver() {
        $this->receivers->clear();
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
     * Set description
     *
     * @param string $description
     * @return Invitations
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
     * Set sender
     *
     * @param \Polcode\CasperBundle\Entity\User $sender
     * @return Invitations
     */
    public function setSender(\Polcode\CasperBundle\Entity\User $sender = null)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Get sender
     *
     * @return \Polcode\CasperBundle\Entity\User 
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Add receivers
     *
     * @param \Polcode\CasperBundle\Entity\User $receivers
     * @return Invitations
     */
    public function addReceiver(\Polcode\CasperBundle\Entity\User $receivers)
    {
        $this->receivers[] = $receivers;

        return $this;
    }

    /**
     * Remove receivers
     *
     * @param \Polcode\CasperBundle\Entity\User $receivers
     */
    public function removeReceiver(\Polcode\CasperBundle\Entity\User $receivers)
    {
        $this->receivers->removeElement($receivers);
    }

    /**
     * Get receivers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReceivers()
    {
        return $this->receivers;
    }

    /**
     * Set event
     *
     * @param \Polcode\CasperBundle\Entity\Event $event
     * @return Invitations
     */
    public function setEvent(\Polcode\CasperBundle\Entity\Event $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return \Polcode\CasperBundle\Entity\Event 
     */
    public function getEvent()
    {
        return $this->event;
    }
}
