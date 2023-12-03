<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\MatomoBundle\Tests\Block\Service;

use Nucleos\MatomoBundle\Block\Service\MatomoStatisticBlockService;
use Nucleos\MatomoBundle\Client\ClientFactoryInterface;
use Nucleos\MatomoBundle\Client\ClientInterface;
use Nucleos\MatomoBundle\Exception\MatomoException;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;
use Sonata\BlockBundle\Block\BlockContext;
use Sonata\BlockBundle\Form\Mapper\FormMapper;
use Sonata\BlockBundle\Model\Block;
use Sonata\BlockBundle\Test\BlockServiceTestCase;
use Symfony\Component\HttpFoundation\Response;

final class MatomoStatisticBlockServiceTest extends BlockServiceTestCase
{
    private LoggerInterface&MockObject $logger;

    private ClientFactoryInterface&MockObject $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->logger  = $this->createMock(LoggerInterface::class);
        $this->factory = $this->createMock(ClientFactoryInterface::class);
    }

    public function testExecute(): void
    {
        $client = $this->createMock(ClientInterface::class);
        $client->expects(self::once())->method('call')
            ->with(self::equalTo('VisitsSummary.getVisits'), self::equalTo([
                'idSite' => 'foo',
                'period' => 'day',
                'date'   => 'last30',
            ]))
            ->willReturn(['bar'])
        ;

        $this->factory->expects(self::once())->method('createClient')
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
            'template' => '@NucleosMatomo/Block/block_matomo_statistic.html.twig',
        ]);

        $response = new Response();

        $this->twig->expects(self::once())->method('render')
            ->with(
                '@NucleosMatomo/Block/block_matomo_statistic.html.twig',
                [
                    'context'    => $blockContext,
                    'settings'   => $blockContext->getSettings(),
                    'block'      => $blockContext->getBlock(),
                    'data'       => ['bar'],
                ]
            )
            ->willReturn('TWIG_CONTENT')
        ;

        $blockService = new MatomoStatisticBlockService($this->twig, $this->factory);

        self::assertSame($response, $blockService->execute($blockContext, $response));
        self::assertSame('TWIG_CONTENT', $response->getContent());
    }

    public function testExecuteOffline(): void
    {
        $client = $this->createMock(ClientInterface::class);
        $client->expects(self::once())->method('call')
            ->with(self::equalTo('VisitsSummary.getVisits'), self::equalTo([
                'idSite' => 'foo',
                'period' => 'day',
                'date'   => 'last30',
            ]))
            ->willThrowException(new MatomoException('Offline'))
        ;

        $this->factory->expects(self::once())->method('createClient')
            ->willReturn($client)
        ;

        $this->logger->expects(self::once())->method('warning');

        $block = new Block();

        $blockContext = new BlockContext($block, [
            'title'    => null,
            'site'     => 'foo',
            'method'   => 'VisitsSummary.getVisits',
            'host'     => 'localhost',
            'token'    => '0815',
            'period'   => 'day',
            'date'     => 'last30',
            'template' => '@NucleosMatomo/Block/block_matomo_statistic.html.twig',
        ]);

        $response = new Response();

        $this->twig->expects(self::once())->method('render')
            ->with(
                '@NucleosMatomo/Block/block_matomo_statistic.html.twig',
                [
                    'context'    => $blockContext,
                    'settings'   => $blockContext->getSettings(),
                    'block'      => $blockContext->getBlock(),
                    'data'       => null,
                ]
            )
            ->willReturn('TWIG_CONTENT')
        ;

        $blockService = new MatomoStatisticBlockService($this->twig, $this->factory);
        $blockService->setLogger($this->logger);

        self::assertSame($response, $blockService->execute($blockContext, $response));
        self::assertSame('TWIG_CONTENT', $response->getContent());
    }

    public function testDefaultSettings(): void
    {
        $blockService = new MatomoStatisticBlockService($this->twig, $this->factory);
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
            'template'           => '@NucleosMatomo/Block/block_matomo_statistic.html.twig',
        ], $blockContext);
    }

    public function testGetMetadata(): void
    {
        $blockService = new MatomoStatisticBlockService($this->twig, $this->factory);

        $metadata = $blockService->getMetadata();

        self::assertSame('nucleos_matomo.block.statistic', $metadata->getTitle());
        self::assertNull($metadata->getImage());
        self::assertSame('NucleosMatomoBundle', $metadata->getDomain());
        self::assertSame([
            'class' => 'fa fa-area-chart',
        ], $metadata->getOptions());
    }

    public function testConfigureEditForm(): void
    {
        $blockService = new MatomoStatisticBlockService($this->twig, $this->factory);

        $block = new Block();

        $formMapper = $this->createMock(FormMapper::class);
        $formMapper->expects(self::once())->method('add');

        $blockService->configureEditForm($formMapper, $block);
    }
}
