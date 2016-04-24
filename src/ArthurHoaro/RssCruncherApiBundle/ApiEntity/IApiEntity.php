<?php

namespace ArthurHoaro\RssCruncherApiBundle\ApiEntity;

use ArthurHoaro\RssCruncherApiBundle\Model\IEntity;

interface IApiEntity
{
    /**
     * @param IEntity
     */
    public function setEntity($entity);
}