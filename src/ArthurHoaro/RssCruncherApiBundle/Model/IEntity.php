<?php

namespace ArthurHoaro\RssCruncherApiBundle\Model;


interface IEntity {
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Generic update method, called when updating an entity.
     *
     * Usage example:
     *   update the modification date.
     *
     * @return IEntity
     */
    public function update();
}
