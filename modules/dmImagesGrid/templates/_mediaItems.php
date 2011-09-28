<div class="dmMediaItemsList" style="display: none;">
    <?php echo _tag('div.media-items-messages', array("json"=>array(
        'delete_message' => __('Do you realy want to remove this item?'),
        'media_items_help' => __('Drag & drop images here from the MEDIA panel on your right side.')
    ))); ?>        
    <div class="media-items-template ui-corner-all">
        <div class="media-item">
            <div class="errors media_id_error">
                
            </div>
            <table>
                <tr>
                    <td class="media-box">
                        <div class="remove-thick" style="display: none">
                            <img src="/dmImagesGridPlugin/images/remove.png" alt="Remove" width="29" height="29" />
                        </div>
                        <div class="media-preview">
                        </div>
                        <div class="media-settings">
                            <input type="hidden" class="media_id" name="<?php echo $form_name; ?>[media_item][media_id][]" />
                            <input class="media_position" type="hidden" name="<?php echo $form_name; ?>[media_item][media_position][]" />
                        </div>
                    </td>
                    <td>
                        
                        <table>
                            <tr>
                                <td class="errors media_title_error" colspan="2">

                                </td>
                            </tr>
                            <tr>
                                <td class="label">
                                    Title
                                </td>
                                <td class="field">
                                    <input class="media_title" type="text" name="<?php echo $form_name; ?>[media_item][media_title][]" />
                                </td>
                            </tr>                
                            <tr>
                                <td class="dm_help" colspan="2">
                                    TITLE & ALT tag for the image
                                </td>
                            </tr>

                            <tr>
                                <td class="errors media_link_error" colspan="2">

                                </td>
                            </tr>
                            <tr>
                                <td class="label">
                                    Link
                                </td>
                                <td class="field">
                                    <input class="media_link" type="text" name="<?php echo $form_name; ?>[media_item][media_link][]" />
                                </td>
                            </tr>
                            <tr>
                                <td class="dm_help" colspan="2">
                                    Link to page, media item or external resource
                                </td>
                            </tr>

                            <tr>
                                <td class="errors media_config_error" colspan="2">

                                </td>
                            </tr>                
                            <tr>
                                <td class="label">
                                    Media configuration
                                </td>
                                <td class="field">
                                    <input class="media_config" type="text" name="<?php echo $form_name; ?>[media_item][media_config][]" />
                                </td>
                            </tr>
                            <tr>
                                <td class="dm_help" colspan="2">
                                    Additional configuration parameters for the image in YAML format
                                </td>
                            </tr>

                            <tr>
                                <td class="errors link_config_error" colspan="2">

                                </td>
                            </tr>
                            <tr>
                                <td class="label">
                                    Link configuration
                                </td>
                                <td class="field">
                                    <input class="link_config" type="text" name="<?php echo $form_name; ?>[media_item][link_config][]" />
                                </td>
                            </tr>
                            <tr>
                                <td class="dm_help" colspan="2">
                                    Additional configuration parameters for the link in YAML format
                                </td>
                            </tr>
                        </table>
                        
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
