<?php

namespace ArthurHoaro\RssCruncherUserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ArthurHoaroRssCruncherUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
