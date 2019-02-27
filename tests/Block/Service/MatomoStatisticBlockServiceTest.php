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
use Sonata\BlockBundle\Model\Block;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Test\AbstractBlockServiceTestCase;

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
        $client->expects($this->once())->method('call')
            ->with($this->equalTo('VisitsSummary.getVisits'), $this->equalTo([
                'idSite' => 'foo',
                'period' => 'day',
                'date'   => 'last30',
            ]))
            ->willReturn(['bar'])
        ;

        $this->factory->expects($this->once())->method('createClient')
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

        $this->assertSame('@Core23Matomo/Block/block_matomo_statistic.html.twig', $this->templating->view);

        $this->assertSame($blockContext, $this->templating->parameters['context']);
        $this->assertInternalType('array', $this->templating->parameters['settings']);
        $this->assertInstanceOf(BlockInterface::class, $this->templating->parameters['block']);
        $this->assertSame(['bar'], $this->templating->parameters['data']);
    }

    public function testExecuteOffline(): void
    {
        $client = $this->createMock(ClientInterface::class);
        $client->expects($this->once())->method('call')
            ->with($this->equalTo('VisitsSummary.getVisits'), $this->equalTo([
                'idSite' => 'foo',
                'period' => 'day',
                'date'   => 'last30',
            ]))
            ->willThrowException(new MatomoException('Offline'))
        ;

        $this->factory->expects($this->once())->method('createClient')
            ->willReturn($client)
        ;

        $this->logger->expects($this->once())->method('warning');

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

        $this->assertSame('@Core23Matomo/Block/block_matomo_statistic.html.twig', $this->templating->view);

        $this->assertSame($blockContext, $this->templating->parameters['context']);
        $this->assertInternalType('array', $this->templating->parameters['settings']);
        $this->assertInstanceOf(BlockInterface::class, $this->templating->parameters['block']);
        $this->assertNull($this->templating->parameters['data']);
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
}
