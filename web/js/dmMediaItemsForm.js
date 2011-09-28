$.fn.dmWidgetMultimediaImagesGridForm = function($widget) {
    var currentCount = 0;
    var self = this;
    var $tab = self.find('#tabs-images');
    var initialElems = $tab.find('input').val();
    
    var globalError = $tab.find('.error_list li').text();
    
    
    $tab.empty();
    var messages = self.find('.dmMediaItemsList .media-items-messages').metadata();
    var $template = self.find('.dmMediaItemsList .media-items-template');
    self.find('.dmMediaItemsList').remove();
    
    
    
    var $itemList = $('<div class="item-list"></div>');
    if (globalError != "") $itemList.prepend('<div class="errors error media_items_list_error">' + globalError + '</div>');
    var appendElement = function(element) {
        $itemList.parent().find('.media_items_list_error').remove();
        
        element = $.extend({
            media_position: 0,
            media_title: '',
            media_link: '',
            media_config: '',
            link_config: ''
        }, element);
        var $newElem = $template.clone();
        $itemList.append($newElem);        
        if (currentCount % 2 == 0) $newElem.addClass('even');
        else $newElem.addClass('odd');
        currentCount++;
        // Parsing...
        $('.media_id', $newElem).val(element.media_id);
        $('.media_position', $newElem).val(element.media_position);
        $('.media_title', $newElem).val(element.media_title); 
        $('.media_link', $newElem).val(element.link).droppable({
            greedy      :       true,
            accept      :       '#dm_media_bar li.file, #dm_page_bar li > a',
            activeClass :       'droppable_active',
            hoverClass  :       'droppable_hover',
            tolerance   :       'pointer',
            drop        :       function(event, ui) {
                var type = 'page:';
                if (ui.draggable.hasClass('file')) $(event.target).val('media:' + ui.draggable.prop('id').replace(/dmm/, '') + ' ' + ui.draggable.find('span.name:first').text().replace(/\s/g, ''));
                else $(event.target).val('page:' + ui.draggable.attr('data-page-id') + ' ' + ui.draggable.text());
                $itemList.droppable('enable').removeClass('droppable_active');
            },
            out         :       function(event, ui) {
                $(event.target).addClass('droppable_active');
            }
        });
        $('.media_config', $newElem).val(element.media_config);
        $('.link_config', $newElem).val(element.link_config);
        
        if (element.errors != undefined) {
            $.each(element.errors, function(){
                $newElem.find('.errors.' + this.field + '_error').text(this.message).addClass('error');
            });
        };
        
        // End parsing
        $('.media-box', $newElem).hover(function(){
            $('.remove-thick', $(this)).css('display', 'inline');
        }, function(){
            $('.remove-thick', $(this)).css('display', 'none');
        });
        
        $('.remove-thick', $newElem).click(function(){
            if (confirm(messages.delete_message)) {
                $newElem.remove();
                currentCount--;
                $itemList.trigger('resort');
            }
        });
        
        $newElem.find('.media-preview').block();
        $.ajax({
            url:     $.dm.ctrl.getHref('+/dmCore/thumbnail'),
            cache:   true,
            data:    {
                source:   'media:'+element.media_id,
                width:    150,
                height:   150,
                quality:  90
            },
            success:    function(html){
                $newElem.find('.media-preview').append(html).unblock();
            },
            error:      function() {
                $newElem.unblock().remove();
            }
        });
        
    }
     
    
    $tab.append($itemList);
    $tab.append('<div class="dm_help">' + messages.media_items_help + '</div>');
    
    $itemList.droppable({
        accept:       '#dm_media_bar li.file.image',
        activeClass:  'droppable_active',
        hoverClass:   'droppable_hover',
        tolerance:    'touch',
        drop:         function(event, ui) {
            appendElement({
                media_id : ui.draggable.prop('id').replace(/dmm/, '')
            });
            $itemList.prop('scrollTop', 999999);
        }
    }).sortable({
        opacity:                0.5,
        distance:               5,
        revert:                 false,
        scroll:                 true,
        tolerance:              'touch',
        stop:                   function(e, ui) {
            $(this).trigger('resort');
        }
    }).bind('resort', function() {
        $('.media-items-template', $itemList).each(function(index) {
            $(this).removeClass('even').removeClass('odd');
            if (index % 2 == 0) $(this).addClass('even');
            else $(this).addClass('odd');
            $('input.media_position', $(this)).val(index);
        });
    });
    
    self.closest('.ui-dialog').css('top','30px');
    try{
        initialElems = $.parseJSON(initialElems);
        $.each(initialElems, function(){
            appendElement(this);
        });
    } catch(e){
        
    }
    
};