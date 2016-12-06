<?php


namespace ArthurHoaro\RssCruncherApiBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraints\Url;

/**
 * @Annotation
 *
 * Class NullableUrl
 *
 * Will call NullableUrlValidator for validation.
 *
 * @package ArthurHoaro\RssCruncherApiBundle\Form
 */
class NullableUrl extends Url {}
