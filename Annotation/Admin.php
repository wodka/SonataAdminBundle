<?php

namespace Sonata\AdminBundle\Annotation;

use JMS\DiExtraBundle\Annotation\MetadataProcessorInterface;
use JMS\DiExtraBundle\Metadata\ClassMetadata;
use Sonata\AdminBundle\Admin\Admin as AdminClass;

/**
 * Use annotations to define admin classes.
 *
 * @Annotation
 * @Target("CLASS")
 */
class Admin implements MetadataProcessorInterface
{
    /**
     * Service id - autogenerated per default.
     *
     * @var string
     */
    public $id;

    /**
     * Admin class.
     *
     * @var string
     */
    public $class;

    /**
     * Data storage.
     *
     * @var string
     */
    public $managerType = 'orm';

    /**
     * @var string
     */
    public $pagerType;

    /**
     * @var string
     */
    public $persistFilters;

    /**
     * Admin group with fallback to class.
     *
     * @var string
     */
    public $group;

    /**
     * Icon for admin group default is '<i class="fa fa-folder"></i>'.
     *
     * @var string
     */
    public $icon;

    /**
     * Admin label with fallback to class.
     *
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $baseControllerName = 'SonataAdminBundle:CRUD';

    /**
     * @var string
     */
    public $translationDomain;

    /**
     * @var bool
     */
    public $showInDashboard = true;

    /**
     * @param ClassMetadata $metadata
     */
    public function processMetadata(ClassMetadata $metadata)
    {
        $this->generateFallback($this->class);
        $this->validate();

        $tag = array(
            'manager_type' => $this->managerType,
            'group' => $this->group,
            'label' => $this->label,
            'show_in_dashboard' => $this->showInDashboard,
            'icon' => $this->icon,
            'pager_type' => $this->pagerType,
            'persist_filters' => $this->persistFilters,
        );

        // Remove empty entries
        $tag = array_filter($tag, function ($v) { return !is_null($v); });

        $metadata->tags['sonata.admin'][] = $tag;

        $metadata->arguments = array(
            $this->id,
            $this->class,
            $this->baseControllerName,
        );

        if ($this->translationDomain) {
            $metadata->methodCalls[] = array(
                'setTranslationDomain',
                array(
                    $this->translationDomain,
                ),
            );
        }
    }

    /**
     * Check if all the required fields are given.
     */
    private function validate()
    {
        if (!$this->showInDashboard) {
            return;
        }

        if (empty($this->group) || empty($this->label)) {
            throw new \RuntimeException(
                sprintf(
                    'Unable to generate admin group and label for class %s.',
                    $this->class
                )
            );
        }
    }

    /**
     * Set group and label from class name it not set.
     *
     * @param $name
     */
    private function generateFallback($name)
    {
        if (empty($name)) {
            return;
        }

        if (preg_match(AdminClass::CLASS_REGEX, $name, $matches)) {
            if (empty($this->group)) {
                $this->group = $matches[3];
            }

            if (empty($this->label)) {
                $this->label = $matches[5];
            }
        }
    }
}
