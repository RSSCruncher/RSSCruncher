<?php


namespace ArthurHoaro\RssCruncherApiBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\UrlValidator;

/**
 * Class NullableUrlValidator
 *
 * Validates URL which can be null.
 *
 * @package ArthurHoaro\RssCruncherApiBundle\Form
 */
class NullableUrlValidator extends UrlValidator
{
    public function validate($value, Constraint $constraint)
    {
        if ($value === null) {
            return true;
        }
        return parent::validate($value, $constraint);
    }

}