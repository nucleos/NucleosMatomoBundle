<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\MatomoBundle\Tests\Block\Service;

use Core23\MatomoBundle\Block\Service\MatomoStatisticBlockService;
use Core23\MatomoBundle\Client\ClientFactoryInterface;
use Core23\MatomoBundle\Client\ClientInterface;
use Core23\MatomoBundle\Exception\MatomoException;
use Psr\Log\LoggerInterface;
use Sonata\BlockBundle\Block\BlockContext;
use Sonata\BlockBundle\Form\Mapper\FormMapper;
use Sonata\BlockBundle\Model\Block;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Test\BlockServiceTestCase;

final class MatomoStatisticBlockServiceTest extends AbstractBlockServiceTestCase
{
    private $logger;

    private $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->logger  = $this->createMock(LoggerInterface::class);
        $this->factory = $this->createMock(ClientFactoryInterface::class);
    }

    public function testExecute(): void
    {
        $client = $this->createMock(ClientInterface::class);
        $client->expects(static::once())->method('call')
            ->with(static::equalTo('VisitsSummary.getVisits'), static::equalTo([
                'idSite' => 'foo',
                'period' => 'day',
                'date'   => 'last30',
            ]))
            ->willReturn(['bar'])
        ;

        $this->factory->expects(static::once())->method('createClient')
            ->willReturn($client)
        ;

        $block = new Block();

        $blockContext = new BlockContext($block, [
            'title'    => null,
            'site'     => 'foo',
            'method'   => 'VisitsSummary.getVisits',
            'host'     => 'localhost',
            'token'    => '0815',
            'period'   => 'day',
            'date'     => 'last30',
            'template' => '@Core23Matomo/Block/block_matomo_statistic.html.twig',
        ]);

        $blockService = new MatomoStatisticBlockService('block.service', $this->templating, $this->factory);
        $blockService->execute($blockContext);

        static::assertSame('@Core23Matomo/Block/block_matomo_statistic.html.twig', $this->templating->view);

        static::assertSame($blockContext, $this->templating->parameters['context']);
        static::assertInternalType('array', $this->templating->parameters['settings']);
        static::assertInstanceOf(BlockInterface::class, $this->templating->parameters['block']);
        static::assertSame(['bar'], $this->templating->parameters['data']);
    }

    public function testExecuteOffline(): void
    {
        $client = $this->createMock(ClientInterface::class);
        $client->expects(static::once())->method('call')
            ->with(static::equalTo('VisitsSummary.getVisits'), static::equalTo([
                'idSite' => 'foo',
                'period' => 'day',
                'date'   => 'last30',
            ]))
            ->willThrowException(new MatomoException('Offline'))
        ;

        $this->factory->expects(static::once())->method('createClient')
            ->willReturn($client)
        ;

        $this->logger->expects(static::once())->method('warning');

        $block = new Block();

        $blockContext = new BlockContext($block, [
            'title'    => null,
            'site'     => 'foo',
            'method'   => 'VisitsSummary.getVisits',
            'host'     => 'localhost',
            'token'    => '0815',
            'period'   => 'day',
            'date'     => 'last30',
            'template' => '@Core23Matomo/Block/block_matomo_statistic.html.twig',
        ]);

        $blockService = new MatomoStatisticBlockService('block.service', $this->templating, $this->factory);
        $blockService->setLogger($this->logger);
        $blockService->execute($blockContext);

        static::assertSame('@Core23Matomo/Block/block_matomo_statistic.html.twig', $this->templating->view);

        static::assertSame($blockContext, $this->templating->parameters['context']);
        static::assertInternalType('array', $this->templating->parameters['settings']);
        static::assertInstanceOf(BlockInterface::class, $this->templating->parameters['block']);
        static::assertNull($this->templating->parameters['data']);
    }

    public function testDefaultSettings(): void
    {
        $blockService = new MatomoStatisticBlockService('block.service', $this->templating, $this->factory);
        $blockService->setLogger($this->logger);
        $blockContext = $this->getBlockContext($blockService);

        $this->assertSettings([
            'title'              => null,
            'translation_domain' => null,
            'icon'               => 'fa fa-bar-chart-o',
            'class'              => null,
            'site'               => null,
            'method'             => 'VisitsSummary.getVisits',
            'host'               => null,
            'token'              => null,
            'period'             => 'day',
            'date'               => 'last30',
            'template'           => '@Core23Matomo/Block/block_matomo_statistic.html.twig',
        ], $blockContext);
    }

    public function testGetMetadata(): void
    {
        $blockService = new MatomoStatisticBlockService('block.service', $this->templating, $this->factory);

        $metadata = $blockService->getMetadata();

        static::assertSame('block.service', $metadata->getTitle());
        static::assertSame('description', $metadata->getDescription());
        static::assertNotNull($metadata->getImage());
        static::assertStringStartsWith('data:image/png;base64,', $metadata->getImage() ?? '');
        static::assertSame('Core23MatomoBundle', $metadata->getDomain());
        static::assertSame([
            'class' => 'fa fa-area-chart',
        ], $metadata->getOptions());
    }

    public function testConfigureEditForm(): void
    {
        $blockService = new MatomoStatisticBlockService('block.service', $this->templating, $this->factory);

        $block = new Block();

        $formMapper = $this->createMock(FormMapper::class);
        $formMapper->expects(static::once())->method('add');

        $blockService->configureEditForm($formMapper, $block);
    }
}
