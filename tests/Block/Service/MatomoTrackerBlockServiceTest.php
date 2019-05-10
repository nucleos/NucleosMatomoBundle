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
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Block\BlockContext;
use Sonata\BlockBundle\Model\Block;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Test\AbstractBlockServiceTestCase;

final class MatomoTrackerBlockServiceTest extends AbstractBlockServiceTestCase
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

        $blockService = new MatomoTrackerBlockService('block.service', $this->templating);
        $blockService->execute($blockContext);

        static::assertSame('@Core23Matomo/Block/block_matomo_tracker.html.twig', $this->templating->view);

        static::assertSame($blockContext, $this->templating->parameters['context']);
        static::assertInternalType('array', $this->templating->parameters['settings']);
        static::assertInstanceOf(BlockInterface::class, $this->templating->parameters['block']);
    }

    public function testDefaultSettings(): void
    {
        $blockService = new MatomoTrackerBlockService('block.service', $this->templating);
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

    public function testGetBlockMetadata(): void
    {
        $blockService = new MatomoTrackerBlockService('block.service', $this->templating);

        $metadata = $blockService->getBlockMetadata('description');

        static::assertSame('block.service', $metadata->getTitle());
        static::assertSame('description', $metadata->getDescription());
        static::assertNotNull($metadata->getImage());
        static::assertStringStartsWith('data:image/png;base64,', $metadata->getImage() ?? '');
        static::assertSame('Core23MatomoBundle', $metadata->getDomain());
        static::assertSame([
            'class' => 'fa fa-code',
        ], $metadata->getOptions());
    }

    public function testBuildEditForm(): void
    {
        $blockService = new MatomoTrackerBlockService('block.service', $this->templating);

        $block = new Block();

        $formMapper = $this->createMock(FormMapper::class);
        $formMapper->expects(static::once())->method('add');

        $blockService->buildEditForm($formMapper, $block);
    }
}
