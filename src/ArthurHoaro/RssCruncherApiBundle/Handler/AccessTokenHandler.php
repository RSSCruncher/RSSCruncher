<?php
/**
 * AccessTokenRepository.php
 * Author: arthur
 */

namespace ArthurHoaro\RssCruncherApiBundle\Handler;


use ArthurHoaro\RssCruncherApiBundle\Entity\AccessToken;

class AccessTokenHandler extends GenericHandler
{
    /**
     * @param string $accessToken
     * @return AccessToken
     */
    public function getByToken($accessToken)
    {
        $tokens = $this->repository->findBy(['token' => $accessToken]);
        return !empty($tokens) ? $tokens[0] : null;
    }
}