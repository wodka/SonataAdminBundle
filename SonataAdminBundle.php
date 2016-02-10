<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\AdminBundle;

use Sonata\AdminBundle\DependencyInjection\Compiler\AddDependencyCallsCompilerPass;
use Sonata\AdminBundle\DependencyInjection\Compiler\AddFilterTypeCompilerPass;
use Sonata\AdminBundle\DependencyInjection\Compiler\ExtensionCompilerPass;
use Sonata\AdminBundle\DependencyInjection\Compiler\GlobalVariablesCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SonataAdminBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new AddDependencyCallsCompilerPass());
        $container->addCompilerPass(new AddFilterTypeCompilerPass());
        $container->addCompilerPass(new ExtensionCompilerPass());
        $container->addCompilerPass(new GlobalVariablesCompilerPass());
        
        $this->configureAnnotations();
    }
    
    /**
     * setup annotation loading in JMSDiExtraBundle if defined
     */
    protected function configureAnnotations()
    {
        if (!class_exists('\JMS\DiExtraBundle\JMSDiExtraBundle')) {
            // cannot set up annotations -> bundle not loaded
            return;
        }
        
        \JMS\DiExtraBundle\JMSDiExtraBundle::addAnnotationPattern('Sonata\AdminBundle\Annotation');
    }
}
