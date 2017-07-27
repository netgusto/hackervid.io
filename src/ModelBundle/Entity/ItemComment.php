<?php

namespace ModelBundle\Entity;

/**
 * ItemComment
 */
class ItemComment
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $points;

    /**
     * @var string
     */
    private $content;

    /**
     * @var \DateTime
     */
    private $creationdate;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $children;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $votes;

    /**
     * @var \ModelBundle\Entity\User
     */
    private $user;

    /**
     * @var \ModelBundle\Entity\ItemComment
     */
    private $parent;

    /**
     * @var \ModelBundle\Entity\Item
     */
    private $item;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->votes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set points
     *
     * @param integer $points
     *
     * @return ItemComment
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return integer
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return ItemComment
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set creationdate
     *
     * @param \DateTime $creationdate
     *
     * @return ItemComment
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
     * Add child
     *
     * @param \ModelBundle\Entity\ItemComment $child
     *
     * @return ItemComment
     */
    public function addChild(\ModelBundle\Entity\ItemComment $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \ModelBundle\Entity\ItemComment $child
     */
    public function removeChild(\ModelBundle\Entity\ItemComment $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Add vote
     *
     * @param \ModelBundle\Entity\ItemCommentVote $vote
     *
     * @return ItemComment
     */
    public function addVote(\ModelBundle\Entity\ItemCommentVote $vote)
    {
        $this->votes[] = $vote;

        return $this;
    }

    /**
     * Remove vote
     *
     * @param \ModelBundle\Entity\ItemCommentVote $vote
     */
    public function removeVote(\ModelBundle\Entity\ItemCommentVote $vote)
    {
        $this->votes->removeElement($vote);
    }

    /**
     * Get votes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * Set user
     *
     * @param \ModelBundle\Entity\User $user
     *
     * @return ItemComment
     */
    public function setUser(\ModelBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \ModelBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set parent
     *
     * @param \ModelBundle\Entity\ItemComment $parent
     *
     * @return ItemComment
     */
    public function setParent(\ModelBundle\Entity\ItemComment $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \ModelBundle\Entity\ItemComment
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set item
     *
     * @param \ModelBundle\Entity\Item $item
     *
     * @return ItemComment
     */
    public function setItem(\ModelBundle\Entity\Item $item = null)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get item
     *
     * @return \ModelBundle\Entity\Item
     */
    public function getItem()
    {
        return $this->item;
    }
    /**
     * @var string
     */
    private $ipv4;


    /**
     * Set ipv4
     *
     * @param string $ipv4
     *
     * @return ItemComment
     */
    public function setIpv4($ipv4)
    {
        $this->ipv4 = $ipv4;

        return $this;
    }

    /**
     * Get ipv4
     *
     * @return string
     */
    public function getIpv4()
    {
        return $this->ipv4;
    }
}
