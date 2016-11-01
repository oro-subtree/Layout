<?php

namespace Oro\Component\Layout\DataProvider;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

use Oro\Bundle\EmbeddedFormBundle\Layout\Form\FormAccessor;
use Oro\Bundle\EmbeddedFormBundle\Layout\Form\FormAction;

// TODO: Refactor this class in ticket BB-5243
abstract class AbstractFormProvider
{
    /** @var array */
    protected $forms = [];

    /** @var FormFactoryInterface */
    protected $formFactory;

    /**
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * Get form accessor with new form
     *
     * @param string $formName
     * @param string $routeName
     * @param mixed $data
     * @param array $parameters
     * @param array $options
     * @param string $instanceName
     *
     * @return FormAccessor
     */
    protected function getFormAccessor(
        $formName,
        $routeName = null,
        $data = null,
        array $parameters = [],
        array $options = [],
        $instanceName = ''
    ) {
        $cacheKey = $this->getCacheKey($formName, $instanceName, $routeName, $parameters);
        if (!array_key_exists($cacheKey, $this->forms)) {
            $this->forms[$cacheKey] = new FormAccessor(
                $this->getForm($formName, $data, $options),
                $routeName ? FormAction::createByRoute($routeName, $parameters) : null
            );
        }

        return $this->forms[$cacheKey];
    }

    /**
     * Build new form
     *
     * @param string $formName
     * @param mixed $data
     * @param array $options
     *
     * @return FormInterface
     */
    protected function getForm($formName, $data = null, array $options = [])
    {
        return $this->formFactory->create($formName, $data, $options);
    }

    /**
     * Get form cache key
     *
     * @param string $formName
     * @param string $instanceName
     * @param string $routeName
     * @param array $parameters
     *
     * @return string
     */
    protected function getCacheKey($formName, $instanceName, $routeName, array $parameters = [])
    {
        return sprintf('%s:%s:%s:%s', $formName, $instanceName, $routeName, implode(':', $parameters));
    }
}
