<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Question;
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
                if ($innerObject instanceof Variant) {
                    return $upLoadedAsset->asset('variant_upload_url', $key);

                }
            };
        };
    }
}