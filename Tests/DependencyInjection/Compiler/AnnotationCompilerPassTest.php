<?php

namespace Sonata\AdminBundle\Tests\DependencyInjection;

use JMS\DiExtraBundle\Metadata\ClassMetadata;
use Sonata\AdminBundle\Annotation\Admin;

class AnnotationCompilerPassTest extends \PHPUnit_Framework_TestCase
{
    public function testInvalidAdminAnnotation()
    {
        /*
         * @Admin(class="Sonata\AdminBundle\Tests\Fixtures\Foo")
         */

        $this->setExpectedException(
            'RuntimeException',
            'Unable to generate admin group and label for class Sonata\AdminBundle\Tests\Fixtures\Foo.'
        );

        $annotation = new Admin();
        $annotation->class = 'Sonata\AdminBundle\Tests\Fixtures\Foo';

        $meta = new ClassMetadata('Sonata\AdminBundle\Tests\Fixtures\Entity\Foo');

        $annotation->processMetadata($meta);
    }

    public function testMinimalAdmin()
    {
        /*
         * @Admin(class="Sonata\AdminBundle\Entity\Foo")
         */
        $annotation = new Admin();
        $annotation->class = 'Sonata\AdminBundle\Entity\Foo';

        $meta = new ClassMetadata('Sonata\AdminBundle\Tests\Fixtures\Entity\Foo');

        $annotation->processMetadata($meta);

        $this->assertEquals(
            $meta->tags['sonata.admin'][0],
            [
                'manager_type' => 'orm',
                'group' => 'Admin',
                'label' => 'Foo',
                'show_in_dashboard' => true
            ]
        );
    }

    public function testAdmin()
    {
        /*
         * @Admin(
         *      class="Sonata\AdminBundle\Entity\Foo"
         *
         * )
         */
        $annotation = new Admin();
        $annotation->class = 'Sonata\AdminBundle\Entity\Foo';
        $annotation->managerType = 'doctrine_mongodb';
        $annotation->group = 'myGroup';
        $annotation->label = 'myLabel';
        $annotation->showInDashboard = false;
        $annotation->translationDomain = 'OMG';

        $meta = new ClassMetadata('Sonata\AdminBundle\Tests\Fixtures\Entity\Foo');

        $annotation->processMetadata($meta);

        $this->assertEquals(
            $meta->tags['sonata.admin'][0],
            [
                'manager_type' => 'doctrine_mongodb',
                'group' => 'myGroup',
                'label' => 'myLabel',
                'show_in_dashboard' => false
            ]
        );

        $this->assertEquals(
            $meta->methodCalls[0],
            [
                'setTranslationDomain',
                [
                    'OMG'
                ]
            ]
        );
    }
}
