<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Question;
use App\Entity\Subtitle;
use App\Entity\User\User;
use App\Entity\Variant;
use App\Twig\Extension\AppUpLoadedAsset;

class NormalizerService
{
    public function imageCallback(AppUpLoadedAsset $upLoadedAsset): \Closure
    {
        return function ($key, $innerObject, string $attributeName) use ($upLoadedAsset) {
            if ($attributeName === 'image' && !is_null($key)) {

                if ($innerObject instanceof Category) {
                    return $upLoadedAsset->asset('category_upload_url', $key);
                }
                if ($innerObject instanceof Question) {
                    return $upLoadedAsset->asset('question_upload_url', $key);
                }
                if ($innerObject instanceof Variant || $innerObject instanceof Subtitle) {
                    return $upLoadedAsset->asset('variant_upload_url', $key);
                }
            };
        };
    }

    public function rolesCallback(RoleService $roleService): \Closure
    {
        return function ($key, $innerObject, string $attributeName) use ($roleService) {
            if ($attributeName === 'roles' && !is_null($key)) {
                if ($innerObject instanceof User) {
                    return $roleService->getAliases($innerObject->getRoles());
                }
            };
        };
    }

    public function dateTimeCallback(): \Closure
    {
        return function (object $innerObject, object $outerObject, string $attributeName, string $format = null, array $context = []): string {
            if ($format == 'Y-m-d' && $innerObject instanceof \DateTimeImmutable) {
                return $innerObject->format('Y-m-d');
            }

            if ($innerObject instanceof \DateTimeImmutable) {
                return $innerObject->format('Y-m-d H:i:s');
            }

            return '';
        };
    }

    public function dateCallback(): \Closure
    {
        return function (object $innerObject, object $outerObject, string $attributeName, string $format = null, array $context = []): string {

            if ($innerObject instanceof \DateTimeImmutable) {
                return $innerObject->format('Y-m-d');
            }

            return '';
        };
    }
}