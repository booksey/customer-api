<?php

declare(strict_types=1);

namespace App\Factory;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use Psr\Container\ContainerInterface;

class SerializerFactory
{
    public function __invoke(ContainerInterface $container): SerializerInterface
    {
        /** @var array $config */
        $config = $container->get('config');
        $serializerConfig = $config['serializer'];
        $serializerDebugMode = (bool) $serializerConfig['debugMode'];
        $serializer = SerializerBuilder::create()
            ->setSerializationContextFactory(function () {
                return SerializationContext::create()
                    ->setSerializeNull(true);
            })
            ->setPropertyNamingStrategy(
                new SerializedNameAnnotationStrategy(
                    new IdenticalPropertyNamingStrategy()
                )
            )
            ->setDebug($serializerDebugMode);
        return $serializer->build();
    }
}
