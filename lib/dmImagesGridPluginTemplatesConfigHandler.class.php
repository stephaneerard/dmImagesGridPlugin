<?php

/**
 * Description of dmImagesGridPluginTemplatesConfigHandler
 *
 * @author TheCelavi
 */
class dmImagesGridPluginTemplatesConfigHandler extends sfYamlConfigHandler {

    public function execute($configFiles) {
        
        $myConfig = $this->parseYamls($configFiles);
        
        $retval = sprintf("<?php\n" .
                "// auto-generated by %s\n" .
                "// date: %s\nsfConfig::set('dm_images_grid_plugin', \n%s\n);\n?>", __CLASS__, date('Y/m/d H:i:s'), var_export($myConfig, true));

        return $retval;
        
    }

}

