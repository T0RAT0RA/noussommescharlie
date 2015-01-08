<?php
namespace Entity;
/**
 * @Entity
 * @Table(name="charlies", uniqueConstraints={@UniqueConstraint(name="unique_coords", columns={"latitude", "longitude"})})
 */
class Charlie implements \JsonSerializable {
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    private $id;
    /**
     * @Column(type="float")
     */
    private $latitude;
    /**
     * @Column(type="float")
     */
    private $longitude;
    /**
     * @Column(type="datetime")
     */
    private $created_at;

    /************************************/
    /* Custom methods                   */
    function __construct($latitude = 0, $longitude = 0) {
        $this->latitude     = round($latitude, 6);
        $this->longitude    = round($longitude, 6);
        $this->created_at   = new \DateTime();
    }
    public function jsonSerialize() {
        return [
            'latitude'   => $this->latitude,
            'longitude'  => $this->longitude,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
    /************************************/

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
     * Set latitude
     *
     * @param float $latitude
     * @return Charlie
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     * @return Charlie
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Charlie
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }
}
