<?php

namespace ArthurHoaro\RssCruncherApiBundle\Handler;


use ArthurHoaro\RssCruncherApiBundle\Entity\AccessToken;

/**
 * Class AccessTokenHandler
 *
 * Handler for AccessToken
 *
 * @package ArthurHoaro\RssCruncherApiBundle\Handler
 */
class AccessTokenHandler extends GenericHandler
{
    /**
     * Retrieve an AccessToken object using a token as a string.
     *
     * @param string $accessToken token
     *
     * @return AccessToken
     */
    public function getByToken($accessToken)
    {
        $tokens = $this->repository->findBy(['token' => $accessToken]);
        return !empty($tokens) ? $tokens[0] : null;
    }
}
