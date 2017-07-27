<?php

namespace ModelBundle\Entity;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * User
 */
class User implements AdvancedUserInterface {

    public $plainpassword; // not persisted; used when creating the user

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $passwordhash;

    /**
     * @var integer
     */
    private $karma;

    /**
     * @var \DateTime
     */
    private $creationdate;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $items;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $itemvotes;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $itemcomments;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $itemcommentvotes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
        $this->itemvotes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->itemcomments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->itemcommentvotes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set passwordhash
     *
     * @param string $passwordhash
     *
     * @return User
     */
    public function setPasswordhash($passwordhash)
    {
        $this->passwordhash = $passwordhash;

        return $this;
    }

    /**
     * Get passwordhash
     *
     * @return string
     */
    public function getPasswordhash()
    {
        return $this->passwordhash;
    }

    /**
     * Set karma
     *
     * @param integer $karma
     *
     * @return User
     */
    public function setKarma($karma)
    {
        $this->karma = $karma;

        return $this;
    }

    /**
     * Get karma
     *
     * @return integer
     */
    public function getKarma()
    {
        return $this->karma;
    }

    /**
     * Set creationdate
     *
     * @param \DateTime $creationdate
     *
     * @return User
     */
    public function setCreationdate($creationdate)
    {
        $this->creationdate = $creationdate;

        return $this;
    }

    /**
     * Get creationdate
     *
     * @return \DateTime
     */
    public function getCreationdate()
    {
        return $this->creationdate;
    }

    /**
     * Add item
     *
     * @param \ModelBundle\Entity\Item $item
     *
     * @return User
     */
    public function addItem(\ModelBundle\Entity\Item $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove item
     *
     * @param \ModelBundle\Entity\Item $item
     */
    public function removeItem(\ModelBundle\Entity\Item $item)
    {
        $this->items->removeElement($item);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Add itemvote
     *
     * @param \ModelBundle\Entity\ItemVote $itemvote
     *
     * @return User
     */
    public function addItemvote(\ModelBundle\Entity\ItemVote $itemvote)
    {
        $this->itemvotes[] = $itemvote;

        return $this;
    }

    /**
     * Remove itemvote
     *
     * @param \ModelBundle\Entity\ItemVote $itemvote
     */
    public function removeItemvote(\ModelBundle\Entity\ItemVote $itemvote)
    {
        $this->itemvotes->removeElement($itemvote);
    }

    /**
     * Get itemvotes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItemvotes()
    {
        return $this->itemvotes;
    }

    /**
     * Add itemcomment
     *
     * @param \ModelBundle\Entity\ItemComment $itemcomment
     *
     * @return User
     */
    public function addItemcomment(\ModelBundle\Entity\ItemComment $itemcomment)
    {
        $this->itemcomments[] = $itemcomment;

        return $this;
    }

    /**
     * Remove itemcomment
     *
     * @param \ModelBundle\Entity\ItemComment $itemcomment
     */
    public function removeItemcomment(\ModelBundle\Entity\ItemComment $itemcomment)
    {
        $this->itemcomments->removeElement($itemcomment);
    }

    /**
     * Get itemcomments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItemcomments()
    {
        return $this->itemcomments;
    }

    /**
     * Add itemcommentvote
     *
     * @param \ModelBundle\Entity\ItemCommentVote $itemcommentvote
     *
     * @return User
     */
    public function addItemcommentvote(\ModelBundle\Entity\ItemCommentVote $itemcommentvote)
    {
        $this->itemcommentvotes[] = $itemcommentvote;

        return $this;
    }

    /**
     * Remove itemcommentvote
     *
     * @param \ModelBundle\Entity\ItemCommentVote $itemcommentvote
     */
    public function removeItemcommentvote(\ModelBundle\Entity\ItemCommentVote $itemcommentvote)
    {
        $this->itemcommentvotes->removeElement($itemcommentvote);
    }

    /**
     * Get itemcommentvotes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItemcommentvotes()
    {
        return $this->itemcommentvotes;
    }

    ///////////////////////////////////////////////////////////////////////////
    // Implementing AdvancedUserInterface
    ///////////////////////////////////////////////////////////////////////////

    public function getRoles() { return array('ROLE_USER'); }

    public function getPassword() { return $this->getPasswordHash(); }

    public function getSalt() { return null; }

    /*public function getUsername();*/

    public function eraseCredentials() {}

    public function isAccountNonExpired() { return true; }

    public function isAccountNonLocked() { return true; }

    public function isCredentialsNonExpired() { return true; }

    public function isEnabled() { return true; }
}
