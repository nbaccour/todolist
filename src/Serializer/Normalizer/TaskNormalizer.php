<?php

namespace App\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Tasks",
 *     description="list des taches",
 *     @OA\Property(type="integer", property="id"),
 *     @OA\Property(type="string", property="title", nullable=false),
 *     @OA\Property(type="string", property="content", nullable=false),
 *     @OA\Property(type="boolean", property="isDone", nullable=false),
 *     @OA\Property(type="dateTime", property="createAt", nullable=false),
 * )
 *
 */
class TaskNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private $normalizer;

    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function normalize($object, $format = null, array $context = []): array
    {
        $data = $this->normalizer->normalize($object, $format, $context);

        // Here: add, edit, or delete some data

        return $data;
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof \App\Entity\Task;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
