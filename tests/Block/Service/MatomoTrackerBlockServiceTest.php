<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\MatomoBundle\Tests\Block\Service;

use Core23\MatomoBundle\Block\Service\MatomoTrackerBlockService;
use Sonata\BlockBundle\Block\BlockContext;
use Sonata\BlockBundle\Form\Mapper\FormMapper;
use Sonata\BlockBundle\Model\Block;
use Sonata\BlockBundle\Test\BlockServiceTestCase;
use Symfony\Component\HttpFoundation\Response;

final class MatomoTrackerBlockServiceTest extends BlockServiceTestCase
{
    public function testExecute(): void
    {
        $block = new Block();

        $blockContext = new BlockContext($block, [
            'host'        => null,
            'site'        => null,
            'domaintitle' => false,
            'donottrack'  => false,
            'nocookies'   => false,
            'template'    => '@Core23Matomo/Block/block_matomo_tracker.html.twig',
        ]);

        $response = new Response();

        $this->twig->expects(static::once())->method('render')
            ->with(
                '@Core23Matomo/Block/block_matomo_tracker.html.twig',
                [
                    'context'    => $blockContext,
                    'settings'   => $blockContext->getSettings(),
                    'block'      => $blockContext->getBlock(),
                    'statusCode' => 200,
                ]
            )
            ->willReturn('TWIG_CONTENT')
        ;

        $blockService = new MatomoTrackerBlockService($this->twig);

        static::assertSame($response, $blockService->execute($blockContext, $response));
        static::assertSame('TWIG_CONTENT', $response->getContent());
    }

    public function testDefaultSettings(): void
    {
        $blockService = new MatomoTrackerBlockService($this->twig);
        $blockContext = $this->getBlockContext($blockService);

        $this->assertSettings([
            'host'        => null,
            'site'        => null,
            'domaintitle' => false,
            'donottrack'  => false,
            'nocookies'   => false,
            'template'    => '@Core23Matomo/Block/block_matomo_tracker.html.twig',
        ], $blockContext);
    }

    public function testGetMetadata(): void
    {
        $blockService = new MatomoTrackerBlockService($this->twig);

        $metadata = $blockService->getMetadata();

        static::assertSame('core23_matomo.block.tracker', $metadata->getTitle());
        static::assertNull($metadata->getImage());
        static::assertSame('Core23MatomoBundle', $metadata->getDomain());
        static::assertSame([
            'class' => 'fa fa-code',
        ], $metadata->getOptions());
    }

    public function testConfigureEditForm(): void
    {
        $blockService = new MatomoTrackerBlockService($this->twig);

        $block = new Block();

        $formMapper = $this->createMock(FormMapper::class);
        $formMapper->expects(static::once())->method('add');

        $blockService->configureEditForm($formMapper, $block);
    }
}
