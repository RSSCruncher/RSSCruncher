<?php

namespace ArthurHoaro\RssCruncherApiBundle\ApiEntity;

use ArthurHoaro\RssCruncherApiBundle\Model\IEntity;

/**
 * Interface IApiEntity
 *
 * IApiEntity objects represent object populated from entities, to be serialized for the REST API.
 *
 * @package ArthurHoaro\RssCruncherApiBundle\ApiEntity
 */
interface IApiEntity
{
    /**
     * @param IEntity
     */
    public function setEntity($entity);
}
