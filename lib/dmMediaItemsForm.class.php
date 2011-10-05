<?php

/**
 * Description of dmMediaItemsForm
 *
 * @author TheCelavi
 */
class dmMediaItemsForm extends dmBehaviorableForm {

    protected $imageResizeMethods = array(
        'center' => 'Center',
        'scale' => 'Scale',
        'inflate' => 'Inflate',
        'left' => 'Left',
        'right' => 'Right',
        'top' => 'Top',
        'bottom' => 'Bottom'
    );
    protected $thumbnailDisplayStyle = array(
        'grid' => 'Grid',
        'horizontal' => 'Horizontal',
        'vertical' => 'Vertical'
    );
    
    protected $imageGridContainerStyle = array(
        'elastic' => 'Elastic',
        'fixed' => 'Fixed size',
        'fixed_width' => 'Fixed width',
        'fixed_height' => 'Fixed height'
    );

    protected $overflow = array(
        'visible' => 'Visible',
        'hidden' => 'Hidden',
        'auto' => 'Auto',
        'scroll' => 'Scroll'
    );


    public function configure() {
        parent::configure();
        
        
        $this->widgetSchema['container_width'] = new sfWidgetFormInputText();
        $this->validatorSchema['container_width'] = new sfValidatorInteger(array(
                    'min' => 50,
                    'max' => 2000,
                    'required' => true
                ));
        if (!$this->getDefault('container_width'))
            $this->setDefault('container_width', 450);
        $this->widgetSchema['container_width']->setLabel('Container width');
        
        $this->widgetSchema['container_height'] = new sfWidgetFormInputText();
        $this->validatorSchema['container_height'] = new sfValidatorInteger(array(
                    'min' => 30,
                    'max' => 2000,
                    'required' => true
                ));
        if (!$this->getDefault('container_height'))
            $this->setDefault('container_height', 220);
        $this->widgetSchema['container_height']->setLabel('Container height');
        
        $methods = $this->getService('i18n')->translateArray($this->imageGridContainerStyle);
        $this->widgetSchema['container_style'] = new sfWidgetFormSelect(array(
                    'choices' => $methods
                ));
        $this->validatorSchema['container_style'] = new sfValidatorChoice(array(
                    'choices' => array_keys($methods)
                ));
        if (!$this->getDefault('container_style'))
            $this->setDefault('container_style', 'elastic');
        $this->widgetSchema['container_style']->setLabel('Container style');
        
        
        
        
        $this->widgetSchema['thumbnail_width'] = new sfWidgetFormInputText();
        $this->validatorSchema['thumbnail_width'] = new sfValidatorInteger(array(
                    'min' => 50,
                    'max' => 1000,
                    'required' => true
                ));
        if (!$this->getDefault('thumbnail_width'))
            $this->setDefault('thumbnail_width', 120);
        $this->widgetSchema['thumbnail_width']->setLabel('Images width');

        $this->widgetSchema['thumbnail_height'] = new sfWidgetFormInputText();
        $this->validatorSchema['thumbnail_height'] = new sfValidatorInteger(array(
                    'min' => 30,
                    'max' => 1000,
                    'required' => true
                ));
        if (!$this->getDefault('thumbnail_height'))
            $this->setDefault('thumbnail_height', 80);
        $this->widgetSchema['thumbnail_height']->setLabel('Images height');

        $methods = $this->getService('i18n')->translateArray($this->imageResizeMethods);
        $this->widgetSchema['thumbnail_image_resize_method'] = new sfWidgetFormSelect(array(
                    'choices' => $methods
                ));
        $this->validatorSchema['thumbnail_image_resize_method'] = new sfValidatorChoice(array(
                    'choices' => array_keys($methods)
                ));
        if (!$this->getDefault('thumbnail_image_resize_method'))
            $this->setDefault('thumbnail_image_resize_method', dmConfig::get('image_resize_method', 'center'));
        $this->widgetSchema['thumbnail_image_resize_method']->setLabel('Resize method');

        $this->widgetSchema['thumbnail_image_resize_quality'] = new sfWidgetFormInputText();
        $this->validatorSchema['thumbnail_image_resize_quality'] = new sfValidatorInteger(array(
                    'required' => true,
                    'min' => 0,
                    'max' => 100
                ));
        if (!$this->getDefault('thumbnail_image_resize_quality'))
            $this->setDefault('thumbnail_image_resize_quality', dmConfig::get('image_resize_quality', 90));
        $this->widgetSchema['thumbnail_image_resize_quality']->setLabel('Resize quality');

        $this->widgetSchema['thumbnail_display_style'] = new sfWidgetFormSelect(array(
                    'choices' => $this->getService('i18n')->translateArray($this->thumbnailDisplayStyle)
                ));
        $this->validatorSchema['thumbnail_display_style'] = new sfValidatorChoice(array(
                    'choices' => array_keys($this->thumbnailDisplayStyle)
                ));
        $this->widgetSchema['thumbnail_display_style']->setLabel('Display style');

        $this->widgetSchema['thumbnail_display_per_row'] = new sfWidgetFormInputText();
        $this->validatorSchema['thumbnail_display_per_row'] = new sfValidatorInteger(array(
                    'min' => 2,
                    'max' => 30,
                    'required' => true
                ));
        if (!$this->getDefault('thumbnail_display_per_row'))
            $this->setDefault('thumbnail_display_per_row', 3);
        $this->widgetSchema['thumbnail_display_per_row']->setLabel('Display per row');

        sfContext::getInstance()->getConfigCache()->registerConfigHandler(sfConfig::get('sf_plugins_dir') . '/dmImagesGridPlugin/config/templates.yml', 'dmImagesGridPluginTemplatesConfigHandler', array());
        include_once sfContext::getInstance()->getConfigCache()->checkConfig(sfConfig::get('sf_plugins_dir') . '/dmImagesGridPlugin/config/templates.yml');
        $templates = sfConfig::get('dm_images_grid_plugin');
        $templates_values = array();
        foreach ($templates['templates'] as $key => $value) {
            $templates_values[$key] = $value['name'];
        }
        $methods = $this->getService('i18n')->translateArray($templates_values);


        $this->widgetSchema['templates'] = new sfWidgetFormSelect(array(
                    'choices' => $methods
                ));
        $this->validatorSchema['templates'] = new sfValidatorChoice(array(
                    'choices' => array_keys($templates_values)
                ));
        if (!$this->getDefault('templates'))
            $this->setDefault('templates', 'default');
        $this->widgetSchema['templates']->setLabel('Template');

        // Show title
        $this->widgetSchema['show_title'] = new sfWidgetFormInputCheckbox();
        $this->validatorSchema['show_title'] = new sfValidatorBoolean();
        $this->widgetSchema['show_title']->setLabel('Show title');
        $this->getWidgetSchema()->setHelp('show_title', 'Display title for each item');
        
        
        $this->widgetSchema['cssClass']->setAttribute('style', 'width:95%');
        // CSS clases images
        $this->widgetSchema['css_class_images'] = new sfWidgetFormInputText();
        $this->validatorSchema['css_class_images'] = new sfValidatorString(array(
                    'required' => false
                ));
        $this->widgetSchema['css_class_images']->setLabel('Image CSS class');
        $this->getWidgetSchema()->setHelp('css_class_images', 'Apply CSS classes for all images');
        $this->widgetSchema['css_class_images']->setAttribute('style', 'width:95%');

        // CSS clases links
        $this->widgetSchema['css_class_links'] = new sfWidgetFormInputText();
        $this->validatorSchema['css_class_links'] = new sfValidatorString(array(
                    'required' => false
                ));
        $this->widgetSchema['css_class_links']->setLabel('Link CSS class');
        $this->getWidgetSchema()->setHelp('css_class_links', 'Apply CSS classes for all links');
        $this->widgetSchema['css_class_links']->setAttribute('style', 'width:95%');

        $this->widgetSchema['media_item'] = new sfWidgetFormInputText();
        $this->validatorSchema['media_item'] = new dmMediaItemValidator();
        
        // Overflow -> visible, hidden, auto, scroll
        
        $methods = $this->getService('i18n')->translateArray($this->overflow);
        $this->widgetSchema['overflow'] = new sfWidgetFormSelect(array(
                    'choices' => $methods
                ));
        $this->validatorSchema['overflow'] = new sfValidatorChoice(array(
                    'choices' => array_keys($methods)
                ));
        if (!$this->getDefault('overflow'))
            $this->setDefault('overflow', 'visible');
        $this->widgetSchema['overflow']->setLabel('Overflow');
        $this->getWidgetSchema()->setHelp('overflow', 'It can be used only for fixed container style');
        
    }

    protected function renderContent($attributes) {
        $helper = dm::getHelper();
        // A small hack for the F****** validator        
        if ($this->isBound()) $this->getWidget('media_item')->setAttribute('value', $this->getValidator('media_item')->getVal());
        // End of small hack

        $formRenderer = new dmFrontFormRenderer(array(
                    new dmFrontFormSection(
                            array(
                                array('name' => 'media_item', 'is_big' => true)
                            ),
                            'Images'
                    ),
                    new dmFrontFormSection(
                            array(
                                'thumbnail_display_style',
                                'thumbnail_display_per_row',
                                'templates',
                                'container_style',
                                'container_width',
                                'container_height',
                                'overflow'
                            ),
                            'Grid settings'
                    ),
                    new dmFrontFormSection(
                            array(                                
                                'thumbnail_width',
                                'thumbnail_height',
                                'thumbnail_image_resize_method',
                                'thumbnail_image_resize_quality',
                                'show_title'
                            ),
                            'Image settings'
                    ),
                    new dmFrontFormSection(
                            array(   
                                array("name" => 'behaviors', "is_big" => true),
                                array("name" => 'css_class_images', "is_big" => true),
                                array("name" => 'css_class_links', "is_big" => true),
                                array("name" => 'cssClass', "is_big" => true)
                            ),
                            'Advanced'
                    )
                        ), $this);
        return $formRenderer->render() .
                $helper->renderPartial('dmImagesGrid', 'mediaItems', array(
                    'form_name' => $this->getName()
                ));
        ;
    }

    public function bindRequest(sfWebRequest $request) {
        $params = $request->getParameter($this->name);
        try {
            if (isset ($params['media_item'])) $params['media_item'] = json_encode($params['media_item']);
        } catch(Exception $e) {}
        $this->bind($params, $request->getFiles($this->name));
        return $this;
    }

    public function getStylesheets() {
        return array_merge(
                        parent::getStylesheets(), dmFrontFormRenderer::getStylesheets(), array(
                    '/dmImagesGridPlugin/css/dmMediaItemsForm.css'
                        )
        );
    }

    public function getJavaScripts() {
        return array_merge(
                        parent::getJavaScripts(), dmFrontFormRenderer::getJavascripts(), array(
                    '/dmImagesGridPlugin/js/dmMediaItemsForm.js'
                        )
        );
    }

}

