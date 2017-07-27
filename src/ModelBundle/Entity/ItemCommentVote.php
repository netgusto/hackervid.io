<?php

namespace ModelBundle\Entity;

/**
 * ItemCommentVote
 */
class ItemCommentVote
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $creationdate;

    /**
     * @var \ModelBundle\Entity\User
     */
    private $user;

    /**
     * @var \ModelBundle\Entity\Item
     */
    private $item;

    /**
     * @var \ModelBundle\Entity\ItemComment
     */
    private $comment;


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
     * Set creationdate
     *
     * @param \DateTime $creationdate
     *
     * @return ItemCommentVote
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
     * Set user
     *
     * @param \ModelBundle\Entity\User $user
     *
     * @return ItemCommentVote
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
     * Set comment
     *
     * @param \ModelBundle\Entity\ItemComment $comment
     *
     * @return ItemCommentVote
     */
    public function setComment(\ModelBundle\Entity\ItemComment $comment = null)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return \ModelBundle\Entity\ItemComment
     */
    public function getComment()
    {
        return $this->comment;
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
     * @return ItemCommentVote
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

    /**
     * Set item
     *
     * @param \ModelBundle\Entity\Item $item
     *
     * @return ItemCommentVote
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
}
