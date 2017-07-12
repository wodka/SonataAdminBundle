<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\AdminBundle\Annotation;

use JMS\DiExtraBundle\Annotation\MetadataProcessorInterface;
use JMS\DiExtraBundle\Metadata\ClassMetadata;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Use annotations to define block classes.
 *
 * @Annotation
 * @Target("CLASS")
 */
class Block implements MetadataProcessorInterface
{
    /**
     * Service id - autogenerated per default.
     *
     * @var string
     */
    public $id;

    /**
     * @param ClassMetadata $metadata
     */
    public function processMetadata(ClassMetadata $metadata)
    {
        if (!empty($this->id)) {
            $metadata->id = $this->id;
        }

        $metadata->tags['sonata.block'][] = array();
        $metadata->arguments = array($this->id, new Reference('templating'));
    }
}
