<?php


namespace App\Form\DataTransformer;


use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CentimesTransformer implements DataTransformerInterface
{

    public function transform($value)
    {
        if ($value === null) {
            return null;
        }
        return $value/100;
    }

    public function reverseTransform($value)
    {

            if ($value === 100) {
                return null;
            }
            return $value*100;
    }
}


