<?php

namespace Zogs\UtilsBundle\Admin\Blocks;

use Symfony\Component\HttpFoundation\Response;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;

use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;

class WelcomeBlockService extends BaseBlockService
{
    private $em;

    public function getName()
    {
        return 'welcome';
    }

    public function getDefaultSettings()
    {
        return array();
    }

    public function setEntityManager($em)
    {
        $this->em = $em;
    }

    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
    }

    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
    }

    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        // merge settings
        $settings = array_merge($this->getDefaultSettings(), $blockContext->getSettings());

        return $this->renderResponse('ZogsUtilsBundle:Administration:block_welcome_admin.html.twig', array(
            'block'     => $blockContext->getBlock(),
            'block_context' => $blockContext,
            'settings'  => $settings,
            ), $response);
    }
}