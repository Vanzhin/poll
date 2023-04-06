<?php

namespace App\Service;

use Doctrine\Common\Annotations\AnnotationReader;
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

    public function serializeObject(object $object, string $format, array $groups): string
    {
        if($format === 'xml'){
            $this->content = $this->ToXml($object, $groups);
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

        return $this->serializer->serialize($object, 'xml', ['groups' => $groups], );

    }
}