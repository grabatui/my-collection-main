<?php

declare(strict_types=1);

namespace App\Tests\Core;

use App\Tests\Core\Trait\FakerTrait;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @coversNothing
 */
class KernelTestSuit extends KernelTestCase
{
    use FakerTrait;
    private static ?Application $application = null;

    private static ?EntityManager $entityManager = null;

    protected Generator $faker;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->faker = Factory::create('ru_RU');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        if (self::$application instanceof EntityManager) {
            self::$entityManager->close();
        }

        self::$entityManager = null;
    }

    protected function getEntityManager(): EntityManager|ObjectManager|null
    {
        if (null === self::$entityManager) {
            self::$entityManager = static::$kernel->getContainer()
                ->get('doctrine')
                ->getManager();
        }

        return self::$entityManager;
    }

    protected function getApplication(): Application
    {
        if (null === self::$application) {
            self::$application = new Application(self::$kernel);
            self::$application->setAutoExit(false);
        }

        return self::$application;
    }

    protected function getService(string $id): ?object
    {
        return static::getContainer()->get($id);
    }

    protected function makeArrayFromEntity(
        object $entity,
        array $excludedAttributes = [],
    ): array {
        /** @var SerializerInterface $serializer */
        $serializer = $this->getService('serializer');

        return $serializer->normalize(
            data: $entity,
            context: [
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => static fn ($object): null => null,
                AbstractNormalizer::IGNORED_ATTRIBUTES => array_merge(
                    $excludedAttributes,
                    ['__initializer__', '__cloner__', '__isInitialized__'],
                ),
            ],
        );
    }

    protected function assertEntitiesEqual(
        object $expectedEntity,
        object $actualEntity,
        array $excludedAttributes = [],
        string $message = '',
    ): void {
        $this->assertEquals(
            $this->makeArrayFromEntity($expectedEntity, $excludedAttributes),
            $this->makeArrayFromEntity($actualEntity, $excludedAttributes),
            $message,
        );
    }

    protected function assertArrayOfEntitiesEqual(
        array $expectedEntities,
        array $actualEntities,
        ?callable $sortFunction = null,
        array $excludedAttributes = [],
    ): void {
        if ($sortFunction) {
            usort($expectedEntities, $sortFunction);
            usort($actualEntities, $sortFunction);
        }

        $this->assertEquals(
            array_map(
                fn (object $expectedEntity): array => $this->makeArrayFromEntity($expectedEntity, $excludedAttributes),
                $expectedEntities,
            ),
            array_map(
                fn (object $actualEntity): array => $this->makeArrayFromEntity($actualEntity, $excludedAttributes),
                $actualEntities,
            ),
        );
    }
}
