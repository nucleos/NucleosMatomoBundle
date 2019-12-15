<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\MatomoBundle\Block\Service;

use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Sonata\BlockBundle\Block\Service\EditableBlockService;
use Sonata\BlockBundle\Form\Mapper\FormMapper;
use Sonata\BlockBundle\Meta\Metadata;
use Sonata\BlockBundle\Meta\MetadataInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\Form\Type\ImmutableArrayType;
use Sonata\Form\Validator\ErrorElement;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class MatomoTrackerBlockService extends AbstractBlockService implements EditableBlockService
{
    public function execute(BlockContextInterface $blockContext, ?Response $response = null): Response
    {
        return $this->renderResponse($blockContext->getTemplate(), [
            'context'    => $blockContext,
            'settings'   => $blockContext->getSettings(),
            'block'      => $blockContext->getBlock(),
            'statusCode' => null === $response ? 200 : $response->getStatusCode(),
        ], $response);
    }

    public function configureCreateForm(FormMapper $form, BlockInterface $block): void
    {
        $this->configureEditForm($form, $block);
    }

    public function configureEditForm(FormMapper $formMapper, BlockInterface $block): void
    {
        $formMapper->add('settings', ImmutableArrayType::class, [
            'keys' => [
                ['host', TextType::class, [
                    'required' => false,
                    'label'    => 'form.label_host',
                ]],
                ['site', NumberType::class, [
                    'label' => 'form.label_site',
                ]],
                ['domaintitle', CheckboxType::class, [
                    'label'    => 'form.label_domaintitle',
                    'required' => false,
                ]],
                ['nocookies', CheckboxType::class, [
                    'label'    => 'form.label_nocookies',
                    'required' => false,
                ]],
                ['donottrack', CheckboxType::class, [
                    'label'    => 'form.label_donottrack',
                    'required' => false,
                ]],
            ],
            'translation_domain' => 'Core23MatomoBundle',
        ]);
    }

    public function configureSettings(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'host'        => null,
            'site'        => null,
            'domaintitle' => false,
            'donottrack'  => false,
            'nocookies'   => false,
            'template'    => '@Core23Matomo/Block/block_matomo_tracker.html.twig',
        ]);

        $resolver->setRequired(['site', 'host']);
    }

    public function validate(ErrorElement $errorElement, BlockInterface $block): void
    {
    }

    public function getMetadata(): MetadataInterface
    {
        return new Metadata('core23_matomo.block.tracker', null, null, 'Core23MatomoBundle', [
            'class' => 'fa fa-code',
        ]);
    }

    public function getName(): string
    {
        return $this->getMetadata()->getTitle();
    }
}
