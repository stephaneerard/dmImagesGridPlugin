<?php
/**
 * Description of dmMediaItemValidator
 *
 * @author TheCelavi
 */
class dmMediaItemValidator extends sfValidatorBase {
    protected $val = '';


    protected $image_resize_method = array(
        'fit'=>'Fit',
        'scale'=>'Scale',
        'inflate'=>'Inflate',
        'top'=>'Top',
        'right'=>'Right',
        'bottom'=>'Bottom',
        'left'=>'Left',
        'center'=>'Center'
    );


    protected function configure($options = array(), $messages = array()) {
        /* OPTIONS */
        // Media
        $this->addOption('media_id');
        $this->addOption('model', 'DmMedia');
        $this->addOption('query', null);
        $this->addOption('column', null);
        
        // Media config        
        $this->addOption('media_config_required', false);
        
        // Title
        $this->addOption('title_min_length', false);
        $this->addOption('title_max_length', false);
        $this->addOption('title_required', false);
        
        // Link
        $this->addOption('item_link_required', false);
        
        // Link config
        $this->addOption('item_link_config_required', false);
        
        /* MESSAGES */
        
        $this->addMessage('empty', 'List of images can not be empty');
        
        // Media
        $this->addMessage('media_invalid', 'Error in image file, it does not exist.');
        
        // Media config
        $this->addMessage('media_config_required', 'Image configuration is required.');
        $this->addMessage('media_config_invalid', 'Image configuration content is not in valid YAML format.');
        
        // Title
        $this->addMessage('title_min_length', '"%value%" is too short for the title (%title_min_length% characters min).');
        $this->addMessage('title_max_length', '"%value%" is too long for the title (%title_max_length% characters max).');
        $this->addMessage('title_required', 'Title is required.');
        
        // Link
        $this->addMessage('link_invalid', '"%value%" is not valid URL resource.');
        $this->addMessage('link_required', 'Link is required.');
        
        // Link config
        $this->addMessage('link_config_required', 'Link configuration is required.');
        $this->addMessage('link_config_invalid', 'Link configuration content is not in valid YAML format.');
    }
    
    protected function doClean($mediaItems) {
        try {
            $mediaItems = get_object_vars(json_decode($mediaItems));
        } catch (Exception $e) {
            throw new sfValidatorError($this, 'empty');
        }        
        if (sizeof($mediaItems['media_id'])==0){
            throw new sfValidatorError($this, 'empty');
        }
        $cleanedMediaItems = array();
        $hasErrors = false;
        for ($i=0; $i<$c = sizeof($mediaItems['media_id']); $i++) {
            $mediaItem = array();
            $mediaItem['errors'] = array();
            
            try {
                $mediaItem['media_id'] = $this->validateMediaId($mediaItems['media_id'][$i]);
            } catch (sfValidatorError $e) {
                $mediaItem['media_id'] = $mediaItems['media_id'][$i];
                $mediaItem['errors'][] = array(
                    "field"=>'media_id',
                    "message"=>$e->getMessage()
                );
                $hasErrors = true;
            }
            
            try {
                $mediaItem['media_config'] = $this->validateMediaConfig($mediaItems['media_config'][$i]);
            } catch (sfValidatorError $e) {
                $mediaItem['media_config'] = $mediaItems['media_config'][$i];
                $mediaItem['errors'][] = array(
                    "field"=>'media_config',
                    "message"=>$e->getMessage()
                );
                $hasErrors = true;
            }
            
            try {
                $mediaItem['media_title'] = $this->validateMediaTitle($mediaItems['media_title'][$i]);
            } catch (sfValidatorError $e) {
                $mediaItem['media_title'] = $mediaItems['media_title'][$i];
                $mediaItem['errors'][] = array(
                    "field"=>'media_title',
                    "message"=>$e->getMessage()
                );
                $hasErrors = true;
            }
            
            try {
                $mediaItem['link'] = $this->validateLink($mediaItems['media_link'][$i]);
            } catch (sfValidatorError $e) {
                $mediaItem['link'] = $mediaItems['media_link'][$i];
                $mediaItem['errors'][] = array(
                    "field"=>'media_link',
                    "message"=>$e->getMessage()
                );
                $hasErrors = true;
            }
            
            try {
                $mediaItem['link_config'] = $this->validateMediaConfig($mediaItems['link_config'][$i]);
            } catch (sfValidatorError $e) {
                $mediaItem['link_config'] = $mediaItems['link_config'][$i];
                $mediaItem['errors'][] = array(
                    "field"=>'link_config',
                    "message"=>$e->getMessage()
                );
                $hasErrors = true;
            }
            
            $mediaItem['media_position'] = $mediaItems['media_position'][$i];
            $cleanedMediaItems[] = $mediaItem;            
            
        }
        $this->val = json_encode($cleanedMediaItems);
        if ($hasErrors) throw new sfValidatorError($this, 'invalid', array('value'=>  $this->val));
        else return $this->val;
        
    }
    // Hack for form
    public function getVal() {
        return $this->val;
    }


    protected function validateMediaId($value) {
        $query = $this->getOption('query');
        if ($query)
            $query = clone $query;
        else
            $query = Doctrine_Core::getTable($this->getOption('model'))->createQuery();
        $query->andWhere(sprintf('%s.%s = ?', $query->getRootAlias(), 'id'), $value);
        if (!$query->count())
            throw new sfValidatorError($this, 'media_invalid', array('value' => $value));
        return $value;
    }
    
    protected function validateMediaConfig($value) {
        $clean = (string) $value;
        $length = function_exists('mb_strlen') ? mb_strlen($clean, $this->getCharset()) : strlen($clean);
        if ($this->hasOption('media_config_required') && $this->getOption('media_config_required') && $length == 0) {
            throw new sfValidatorError($this, 'media_config_required');
        }
        if ($length > 0) {
            try {
                sfYaml::load($clean);
            } catch (InvalidArgumentException $e) {
                throw new sfValidatorError($this, 'media_config_invalid');
            }
        }
        return $clean;
    }
    
    protected function validateMediaTitle($value) {
        $clean = (string) $value;
        $length = function_exists('mb_strlen') ? mb_strlen($clean, $this->getCharset()) : strlen($clean);
        if ($this->hasOption('title_required') && $this->getOption('title_required') && $length == 0) {
            throw new sfValidatorError($this, 'title_required');
        }
        if ($this->hasOption('title_max_length') && $this->getOption('title_max_length') && $length > $this->getOption('title_max_length')) {
            throw new sfValidatorError($this, 'title_max_length', array('value' => $value, 'title_max_length' => $this->getOption('title_max_length')));
        }
        if ($this->hasOption('title_min_length') && $this->getOption('title_min_length') && $length < $this->getOption('title_min_length')) {
            throw new sfValidatorError($this, 'title_min_length', array('value' => $value, 'title_min_length' => $this->getOption('title_min_length')));
        }
        return $clean;
    }
    
    protected function validateLink($value) {        
        $linkValidator = new dmValidatorLinkUrl(array('required' => ($this->hasOption('link_required') && $this->getOption('link_required'))));        
        $linkValidator->setMessage('invalid', $this->getMessage('link_invalid'));
        $linkValidator->setMessage('required', $this->getMessage('link_required'));
        return $linkValidator->clean($value);
    }
    
    protected function validateLinkConfig($value) {
        $clean = (string) $value;
        $length = function_exists('mb_strlen') ? mb_strlen($clean, $this->getCharset()) : strlen($clean);
        if ($this->hasOption('link_config_required') && $this->getOption('link_config_required') && $length == 0) {
            throw new sfValidatorError($this, 'link_config_required');
        }
        if ($length > 0) {
            try {
                sfYaml::load($clean);
            } catch (InvalidArgumentException $e) {
                throw new sfValidatorError($this, 'link_config_invalid');
            }
        }
        return $clean;
    }
    
}

