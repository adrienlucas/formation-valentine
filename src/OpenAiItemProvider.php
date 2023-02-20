<?php
declare(strict_types=1);

namespace App;

use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Doctrine\Orm\State\ItemProvider;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Movie;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;

class OpenAiItemProvider implements ProviderInterface
{
    public function __construct(
        private CollectionProvider $actualProvider,
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $resource = $this->actualProvider->provide($operation, $uriVariables, $context);
        dd($resource);
        if($resource instanceof Movie) {
            $resource->setTitle('Hello');
        }

        return $resource;
    }
}