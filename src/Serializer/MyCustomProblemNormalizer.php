<?php
/**
 * Created by PhpStorm.
 * User: msi-n
 * Date: 17/06/2021
 * Time: 14:49
 */

namespace App\Serializer;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MyCustomProblemNormalizer implements NormalizerInterface
{
    public function normalize($exception, string $format = null, array $context = [])
    {
        return [
            'content'   => 'This is my custom problem normalizer.',
            'exception' => [
                'message' => $exception->getMessage(),
                'code'    => $exception->getStatusCode(),
            ],
        ];
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof FlattenException;
    }
}