<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\MatomoBundle\Block\Service;

use LogicException;
use Nucleos\MatomoBundle\Client\ClientFactoryInterface;
use Nucleos\MatomoBundle\Exception\MatomoException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Sonata\BlockBundle\Block\Service\EditableBlockService;
use Sonata\BlockBundle\Form\Mapper\FormMapper;
use Sonata\BlockBundle\Meta\Metadata;
use Sonata\BlockBundle\Meta\MetadataInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\Form\Type\ImmutableArrayType;
use Sonata\Form\Validator\ErrorElement;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Environment;

final class MatomoStatisticBlockService extends AbstractBlockService implements EditableBlockService, LoggerAwareInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ClientFactoryInterface
     */
    private $factory;

    public function __construct(Environment $twig, ClientFactoryInterface $factory)
    {
        parent::__construct($twig);

        $this->factory = $factory;
        $this->logger  = new NullLogger();
    }

    public function execute(BlockContextInterface $blockContext, ?Response $response = null): Response
    {
        if (!\is_string($blockContext->getTemplate())) {
            throw new LogicException('Cannot render block without template');
        }

        return $this->renderResponse($blockContext->getTemplate(), [
            'context'  => $blockContext,
            'settings' => $blockContext->getSettings(),
            'block'    => $blockContext->getBlock(),
            'data'     => $this->getData($blockContext->getSettings()),
        ], $response);
    }

    public function configureCreateForm(FormMapper $form, BlockInterface $block): void
    {
        $this->configureEditForm($form, $block);
    }

    /**
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function configureEditForm(FormMapper $formMapper, BlockInterface $block): void
    {
        $formMapper->add('settings', ImmutableArrayType::class, [
            'keys'               => [
                ['title', TextType::class, [
                    'required' => false,
                    'label'    => 'form.label_title',
                ]],
                ['translation_domain', TextType::class, [
                    'label'    => 'form.label_translation_domain',
                    'required' => false,
                ]],
                ['icon', TextType::class, [
                    'label'    => 'form.label_icon',
                    'required' => false,
                ]],
                ['class', TextType::class, [
                    'label'    => 'form.label_class',
                    'required' => false,
                ]],
                ['host', TextType::class, [
                    'required' => false,
                    'label'    => 'form.label_host',
                ]],
                ['token', TextType::class, [
                    'required' => false,
                    'label'    => 'form.label_token',
                ]],
                ['site', NumberType::class, [
                    'label' => 'form.label_site',
                ]],
                ['method', ChoiceType::class, [
                    'choices' => [
                        'form.choice_visitors'        => 'VisitsSummary.getVisits',
                        'form.choice_unique_visitors' => 'VisitsSummary.getUniqueVisitors',
                        'form.choice_hits'            => 'VisitsSummary.getActions ',
                    ],
                    'label'   => 'form.label_method',
                ]],
                ['period', ChoiceType::class, [
                    'choices' => [
                        'form.choice_day'   => 'day',
                        'form.choice_week'  => 'week',
                        'form.choice_month' => 'month',
                        'form.choice_year'  => 'year',
                    ],
                    'label'   => 'form.label_period',
                ]],
                ['date', ChoiceType::class, [
                    'choices' => [
                        'form.choice_today'    => 'last1',
                        'form.choice_1_week'   => 'last7',
                        'form.choice_2_weeks'  => 'last14',
                        'form.choice_1_month'  => 'last30',
                        'form.choice_3_months' => 'last90',
                        'form.choice_6_months' => 'last180',
                        'form.choice_1_year'   => 'last360',
                    ],
                    'label'   => 'form.label_date',
                ]],
            ],
            'translation_domain' => 'NucleosMatomoBundle',
        ]);
    }

    public function configureSettings(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
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
        ]);

        $resolver->setRequired(['site', 'host', 'token']);
    }

    public function validate(ErrorElement $errorElement, BlockInterface $block): void
    {
    }

    public function getMetadata(): MetadataInterface
    {
        return new Metadata('nucleos_matomo.block.statistic', null, null, 'NucleosMatomoBundle', [
            'class' => 'fa fa-area-chart',
        ]);
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    public function getName(): string
    {
        return $this->getMetadata()->getTitle();
    }

    /**
     * @param array<int|string> $settings
     *
     * @return array<mixed>
     */
    protected function getData(array $settings = []): ?array
    {
        try {
            $client = $this->factory->createClient((string) $settings['host'], (string) $settings['token']);

            $result = $client->call((string) $settings['method'], [
                'idSite' => $settings['site'],
                'period' => $settings['period'],
                'date'   => $settings['date'],
            ]);

            if (\is_array($result)) {
                return $result;
            }
        } catch (MatomoException $ce) {
            $this->logger->warning('Error retrieving Matomo url: '.$settings['host'], [
                'exception' => $ce,
            ]);
        }

        return null;
    }
}
