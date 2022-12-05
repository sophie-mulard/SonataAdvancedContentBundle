<?php

namespace Sherlockode\SonataAdvancedContentBundle\Admin;

use Sherlockode\AdvancedContentBundle\Form\Type\ContentType;
use Sherlockode\AdvancedContentBundle\Model\ContentInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\FormBuilderInterface;

class ContentAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'admin_afb_content';

    public function getFormTheme()
    {
        return array_merge(
            parent::getFormTheme(),
            ['@SherlockodeAdvancedContent/Form/content.html.twig']
        );
    }

    public function configureFormFields(FormMapper $form)
    {
        $form->tab('content.form.tabs.label')
            ->with('content.form.tabs.general', [
                'fields' => [
                    'name' => 'name',
                    'slug' => 'slug',
                    'locale' => 'locale',
                ],
            ])
            ->end()
            ->with('content.form.tabs.fields', [
                'fields' => [
                    'fieldValues' => 'fieldValues',
                ],
                'box_class' => 'box box-primary box-content-field-values'
            ])
            ->end()
            ->end(); // init the groups data for this admin class
    }

    /**
     * Create ContentType form
     *
     * @return FormBuilderInterface
     *
     * @throws \Exception
     */
    private function getCustomFormBuilder()
    {
        return $this->getFormContractor()
            ->getFormFactory()
            ->createNamedBuilder($this->getUniqid(), ContentType::class, null, $this->formOptions);
    }

    public function getFormBuilder()
    {
        $this->formOptions['data_class'] = $this->getClass();

        $formBuilder = $this->getCustomFormBuilder();
        $this->defineFormBuilder($formBuilder);

        return $formBuilder;
    }

    public function configureListFields(ListMapper $list)
    {
        $list
            ->add('id', null, ['label' => 'content.id'])
            ->add('name', null, ['label' => 'content.form.name'])
            ->add('_action', 'actions', [
                'actions' => [
                    'edit'   => [],
                    'delete' => [],
                ],
            ])
        ;
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery();
        $query->getQueryBuilder()
            ->where('o.page IS NULL');

        return $query;
    }

    /**
     * @param $object
     *
     * @return string
     */
    public function toString($object)
    {
        if ($object instanceof ContentInterface && $object->getName()) {
            return $object->getName();
        }

        return parent::toString($object);
    }
}
