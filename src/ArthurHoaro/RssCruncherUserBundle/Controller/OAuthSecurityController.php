<?php

namespace ArthurHoaro\RssCruncherUserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;

/**
 * Class OAuthSecurityController
 * @package ArthurHoaro\RssCruncherUserBundle\Controller
 */
class OAuthSecurityController extends SecurityController
{
    /**
     * Different login for OAuth (client auth) or classic login.
     *
     * @param array $data
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderLogin(array $data)
    {
        /** @var RequestContext $request */
        $request = $this->get('router.request_context');
        if (strpos($request->getPathInfo(), 'oauth') !== false) {
            $data += ['path_check_login' => 'arthur_hoaro_rss_cruncher_user_oauth_login_check'];
        } else {
            $data += ['path_check_login' => 'fos_user_security_check'];
        }
        return $this->render('FOSUserBundle:Security:login.html.twig', $data);
    }
}