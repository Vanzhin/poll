<?php

namespace App\Service;

use App\Twig\Extension\AppUpLoadedAsset;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SerializerService
{
    protected $encoder;
    protected $normalizer;
    protected $serializer;
    protected $data;
    private string $content = '';

    public function __construct(readonly private NormalizerService $normalizerService,
                                readonly private AppUpLoadedAsset  $upLoadedAsset)
    {
    }


    public function serializeObject(object $object, string $format, array $groups): string
    {
        switch ($format) {
            case ('xml'):
                $this->content = $this->ToXml($object, $groups);
                break;
            case ('json'):
                $this->content = $this->ToJson($object, $groups);
                break;
        }

        return $this->content;
    }

    private function ToXml(object $object, array $groups): string
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

        $defaultContext = [
            'xml_encoding' => 'utf-8',
            'xml_root_node_name' => 'RegistrySet',
        ];
        $this->encoder = [new XmlEncoder($defaultContext)];
        $this->normalizer = [new ObjectNormalizer($classMetadataFactory)];
        $this->serializer = new Serializer($this->normalizer, $this->encoder);

        return $this->serializer->serialize($object, 'xml', ['groups' => $groups]);

    }

    private function toJson(object $object, array $groups): string
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

        $defaultContext = [
            'charset' => 'utf-8',
        ];
        $this->encoder = [new JsonEncoder()];
        $this->normalizer = [new ObjectNormalizer($classMetadataFactory)];
        $this->serializer = new Serializer($this->normalizer, $this->encoder);

        $contextBuilder = (new ObjectNormalizerContextBuilder())
            ->withContext($defaultContext)
            ->withGroups($groups)
            ->withSkipNullValues(true)
            ->withCallbacks([
                'image' => $this->normalizerService->imageCallback($this->upLoadedAsset),
            ])
        ;

        return $this->serializer->serialize($object, 'json', $contextBuilder->toArray());
    }

    public function serializeMany(array $objects, string $format, array $groups): string
    {
        $response = [];
        foreach ($objects as $object){
            $response[] = ($this->serializeObject($object, $format, $groups));
        }
//        return '{ "question":' . implode(',',$response) . '}';
        return implode(',',$response);

    }
}