<?php
/**
* Functions used to render HTML & Javascript
*
* @package ResourceSpace
*/

/*
TO DO: add here other functions used for rendering such as:
- render_search_field from search_functions.php
*/

/**
* Renders sort order functionality as a dropdown box
*
*/
function render_sort_order(array $order_fields)
    {
    global $order_by, $baseurl_short, $lang, $search, $archive, $restypes, $k, $sort;
    ?>

    <select id="sort_order_selection">
    
    <?php
    $options = '';
    foreach($order_fields as $name => $label)
        {
        $fixed_order = $name == 'relevance';
        $selected    = $order_by == $name;

        // Build the option:
        $option = '<option value="' . $name . '"';

        if(($selected && $fixed_order) || $selected)
            {
            $option .= ' selected';
            }

        $option .= sprintf('
                data-url="%spages/search.php?search=%s&amp;order_by=%s&amp;archive=%s&amp;k=%s&amp;restypes=%s"
            ',
            $baseurl_short,
            urlencode($search),
            $name,
            urlencode($archive),
            urlencode($k),
            urlencode($restypes)
        );

        $option .= '>';
        $option .= $label;
        $option .= '</option>';

        // Add option to the options list
        $options .= $option;
        }

        hook('render_sort_order_add_option', '', array($options));
        echo $options;
    ?>
    
    </select>
    <select id="sort_selection">
        <option value="ASC" <?php if($sort == 'ASC') {echo 'selected';} ?>><?php echo $lang['sortorder-asc']; ?></option>
        <option value="DESC" <?php if($sort == 'DESC') {echo 'selected';} ?>><?php echo $lang['sortorder-desc']; ?></option>
    </select>
    
    <script>
    jQuery('#sort_order_selection').change(function() {
        var selected_option      = jQuery('#sort_order_selection option[value="' + this.value + '"]');
        var selected_sort_option = jQuery('#sort_selection option:selected').val();
        var option_url           = selected_option.data('url');

        if('ASC' === selected_sort_option)
            {
            option_url += '&sort=ASC';
            }

        CentralSpaceLoad(option_url);
    });

    jQuery('#sort_selection').change(function() {
        var selected_option                = this.value;
        var selected_sort_order_option     = jQuery('#sort_order_selection option:selected');
        var selected_sort_order_option_url = selected_sort_order_option.data('url');

        selected_sort_order_option_url += '&sort=' + selected_option;

        CentralSpaceLoad(selected_sort_order_option_url);
    });
    </script>
    <?php
    return;
    }

/**
* Renders a dropdown option
* 
*/
function render_dropdown_option($value, $label, array $data_attr = array(), $extra_tag_attributes  = '')
    {
    $result = '<option value="' . $value . '"';

    // Add any extra tag attributes
    if(trim($extra_tag_attributes) !== '')
        {
        $result .= ' ' . $extra_tag_attributes;
        }

    // Add any data attributes you may need
    foreach($data_attr as $data_attr_key => $data_attr_value)
        {
        $data_attr_key = str_replace(' ', '_', $data_attr_key);

        $result .= ' data-' . $data_attr_key . '="' . $data_attr_value . '"';
        }

    $result .= '>' . $label . '</option>';

    return $result;
    }


/**
* Renders search actions functionality as a dropdown box
* 
*/
function render_actions(array $collection_data, $top_actions = true, $two_line = true, $id = '')
    {
    if(hook('prevent_running_render_actions'))
        {
        return;
        }

    global $baseurl, $lang, $k, $pagename;

    // No need for dropdown actions when sharing externally
    if(trim($k) !== '')
        {
        return;
        }

    // globals that could also be passed as a reference
    global $result /*search result*/;

    $action_selection_id = $pagename . '_action_selection' . $id;
    if(!$top_actions)
        {
        $action_selection_id .= '_bottom';
        }
    if(isset($collection_data['ref']))
        {
        $action_selection_id .= '_' . $collection_data['ref'];
        }
        ?>

    <div class="ActionsContainer  <?php if($top_actions) { echo 'InpageNavLeftBlock'; } ?>">
		<?php
		if (!hook("modifyactionslabel"))
			{
			?>
			<div class="DropdownActionsLabel"><?php echo $lang['actions']; ?>:</div>
			<?php
			}

    if($two_line)
        {
        ?>
        <br />
        <?php
        }
        ?>
        <select id="<?php echo $action_selection_id; ?>" <?php if(!$top_actions) { echo 'class="SearchWidth"'; } ?>>
            <option class="SelectAction" value=""></option>
            <?php

            // Collection Actions
            $collection_actions_array = compile_collection_actions($collection_data, $top_actions);

            // Usual search actions
            $search_actions_array = compile_search_actions($top_actions);
            
            $actions_array = array_merge($collection_actions_array, $search_actions_array);

            // loop and display
			$options='';
			for($a = 0; $a < count($actions_array); $a++)
				{
				if(!isset($actions_array[$a]['data_attr']))
					{
					$actions_array[$a]['data_attr'] = array();
					}

				if(!isset($actions_array[$a]['extra_tag_attributes']))
					{
					$actions_array[$a]['extra_tag_attributes'] = '';
					}

				$options .= render_dropdown_option($actions_array[$a]['value'], $actions_array[$a]['label'], $actions_array[$a]['data_attr'], $actions_array[$a]['extra_tag_attributes']);

				$add_to_options = hook('after_render_dropdown_option', '', array($actions_array, $a));
				if($add_to_options != '')
					{
					$options .= $add_to_options;
					}
				}

			echo $options;
            ?>
        </select>
        <script>
        jQuery('#<?php echo $action_selection_id; ?>').change(function() {

            if(this.value == '')
                {
                return false;
                }

            switch(this.value)
                {
            <?php
            if(!empty($collection_data))
                {
                ?>
                case 'select_collection':
                    ChangeCollection(<?php echo $collection_data['ref']; ?>, '');
                    break;

                case 'remove_collection':
                    if(confirm("<?php echo $lang['removecollectionareyousure']; ?>")) {
                        // most likely will need to be done the same way as delete_collection
                        document.getElementById('collectionremove').value = '<?php echo urlencode($collection_data["ref"]); ?>';
                        document.getElementById('collectionform').submit();
                    }
                    break;

                case 'purge_collection':
                    if(confirm('<?php echo $lang["purgecollectionareyousure"]; ?>'))
                        {
                        document.getElementById('collectionpurge').value='".urlencode($collections[$n]["ref"])."';
                        document.getElementById('collectionform').submit();
                        }
                    break;
                <?php
                }

            if(!$top_actions || !empty($collection_data))
                {
                ?>
                case 'delete_collection':
                    if(confirm('<?php echo $lang["collectiondeleteconfirm"]; ?>')) {
                        var post_data = {
                            ajax: true,
                            dropdown_actions: true,
                            delete: <?php echo urlencode($collection_data['ref']); ?> 
                        };

                        jQuery.post('<?php echo $baseurl; ?>/pages/collection_manage.php', post_data, function(response) {
                            if(response.success === 'Yes')
                                {
                                CollectionDivLoad('<?php echo $baseurl; ?>/pages/collections.php?collection=' + response.redirect_to_collection + '&k=' + response.k + '&nc=' + response.nc);
                                CentralSpaceLoad('<?php echo $baseurl; ?>/pages/search.php?search=!collection' + response.redirect_to_collection, true);
                                }
                        }, 'json');    
                    }
                    break;
                <?php
                }

            // Add extra collection actions javascript case through plugins
            // Note: if you are just going to a different page, it should be easily picked by the default case
            $extra_options_js_case = hook('render_actions_add_option_js_case');
            if(trim($extra_options_js_case) !== '')
                {
                echo $extra_options_js_case;
                }
            ?>

                case 'save_search_to_collection':
                    var option_url = jQuery('#<?php echo $action_selection_id; ?> option:selected').data('url');
                    CollectionDivLoad(option_url);
                    break;

                case 'save_search_to_dash':
                    var option_url  = jQuery('#<?php echo $action_selection_id; ?> option:selected').data('url');
                    var option_link = jQuery('#<?php echo $action_selection_id; ?> option:selected').data('link');

                    // Dash requires to have some search paramenters (even if they are the default ones)
                    if((window.location.href).replace(window.baseurl, '') != '/pages/search.php')
                        {
                        option_link = (window.location.href).replace(window.baseurl, '');
                        }

                    option_url    += '&link=' + option_link;

                    CentralSpaceLoad(option_url);
                    break;

                case 'save_search_smart_collection':
                    var option_url = jQuery('#<?php echo $action_selection_id; ?> option:selected').data('url');
                    CollectionDivLoad(option_url);
                    break;

                case 'save_search_items_to_collection':
                    var option_url = jQuery('#<?php echo $action_selection_id; ?> option:selected').data('url');
                    CollectionDivLoad(option_url);
                    break;

                case 'empty_collection':
                    if(!confirm('<?php echo $lang["emptycollectionareyousure"]; ?>'))
                        {
                        break;
                        }

                    var option_url = jQuery('#<?php echo $action_selection_id; ?> option:selected').data('url');
                    CollectionDivLoad(option_url);
                    break;

            <?php
            if(!$top_actions)
                {
                ?>
                case 'delete_all_in_collection':
                    if(confirm('<?php echo $lang["deleteallsure"]; ?>'))
                        {
                        var post_data = {
                            submitted: true,
                            ref: '<?php echo $collection_data["ref"]; ?>',
                            name: '<?php echo urlencode($collection_data["name"]); ?>',
                            public: '<?php echo $collection_data["public"]; ?>',
                            deleteall: 'on'
                        };

                        jQuery.post('<?php echo $baseurl; ?>/pages/collection_edit.php?ajax=true', post_data, function()
                            {
                            CollectionDivLoad('<?php echo $baseurl; ?>/pages/collections.php?collection=<?php echo $collection_data["ref"] ?>');
                            });
                        }
                    break;
                <?php
                }
                ?>

                case 'csv_export_results_metadata':
                    var option_url = jQuery('#<?php echo $action_selection_id; ?> option:selected').data('url');
                    window.location.href = option_url;
                    break;

                default:
                    var option_url = jQuery('#<?php echo $action_selection_id; ?> option:selected').data('url');
                    CentralSpaceLoad(option_url, true);
                    break;
                }

                // Go back to no action option
                jQuery('#<?php echo $action_selection_id; ?> option[value=""]').attr('selected', 'selected');

        });
        </script>
    </div>
    
    <?php
    return;
    }
