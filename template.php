<?php
/**
 * Implements hook_html_head_alter().
 * We are overwriting the default meta character type tag with HTML5 version.
 */
function grass_html_head_alter(&$head_elements) {
    $head_elements['system_meta_content_type']['#attributes'] = array(
        'charset' => 'utf-8'
    );
}
/**
 * Return a themed breadcrumb trail.
 *
 * @param $breadcrumb
 *   An array containing the breadcrumb links.
 * @return a string containing the breadcrumb output.
 */
function grass_breadcrumb($variables) {
    $breadcrumb = $variables['breadcrumb'];

    if (!empty($breadcrumb)) {
        $breadcrumbs = '<ul class="breadcrumb">';

        $count = count($breadcrumb) - 1;
        foreach ($breadcrumb as $key => $value) {
            if ($count != $key) {
                $breadcrumbs .= '<li>' . $value . '</li>';
            } else {
                $breadcrumbs .= '<li>' . $value . '</li>';
            }
        }
        $breadcrumbs .= '</ul>';

        return $breadcrumbs;
    }
}
/**
 * Preprocess variables for page.tpl.php
 *
 * @see page.tpl.php
 */
function grass_preprocess_page(&$variables) {
    $variables['search'] = FALSE;
    if (theme_get_setting('toggle_search') && module_exists('search')){
        $variables['search'] = drupal_get_form('_twitter_bootstrap_search_form');
    }
    $variables['primary_nav'] = FALSE;
    if ($variables['main_menu']) {
        $tree = menu_tree_page_data(variable_get('menu_main_links_source', 'main-menu'));
        $variables['main_menu']   = grass_menu_navigation_links($tree);
        $variables['primary_nav'] = theme('twitter_bootstrap_links', array(
            'links'      => $variables['main_menu'],
            'attributes' => array(
                'id'    => 'main-menu',
                'class' => array('nav'),
            ),
            'heading' => array(
                'text' => t('Main menu'),
                'level' => 'h2',
                'class' => array('element-invisible'),
            ),
        ));
    }
    $variables['secondary_nav'] = FALSE;
    if ($variables['secondary_menu']) {
        $secondary_menu = menu_load(variable_get('menu_secondary_links_source', 'user-menu'));
        $tree           = menu_tree_page_data($secondary_menu['menu_name']);
        $variables['secondary_menu'] = grass_menu_navigation_links($tree);
        $variables['secondary_nav']  = theme('twitter_bootstrap_btn_dropdown', array(
            'links' => $variables['secondary_menu'],
            'label' => $secondary_menu['title'],
            'type'  => 'success',
            'attributes' => array(
                'id'     => 'user-menu',
                'class'  => array('pull-right'),
            ),
            'heading' => array(
                'text'  => t('Secondary menu'),
                'level' => 'h2',
                'class' => array('element-invisible'),
            ),
        ));
    }
}
/**
 * Override or insert variables into the node template.
 */
function grass_preprocess_node(&$variables) {
    if ($variables['teaser']) {
        $variables['classes_array'][] = 'row';
    }
}
/**
 * Preprocess variables for region.tpl.php
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("region" in this case.)
 */
function grass_preprocess_region(&$variables, $hook) {
    // Use a bare template for the content region.
    if ($variables['region'] == 'content') {
        $variables['theme_hook_suggestions'][] = 'region__bare';
    }
}
/**
 * Override or insert variables into the block templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("block" in this case.)
 */
function grass_preprocess_block(&$variables, $hook) {
    if ($variables['block_html_id'] == 'block-system-main') {
        $variables['theme_hook_suggestions'][] = 'block__bare';
    }
    $variables['title_attributes_array']['class'][] = 'block-title';
}
/**
 * Override or insert variables into the block templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("block" in this case.)
 */
function grass_process_block(&$variables, $hook) {
    $variables['title'] = $variables['block']->subject;
}
//FORMULARIO
/**
 * Changes the search form to use the "search" input element of HTML5.
 */
function grass_preprocess_search_block_form(&$vars) {
    $vars['search_form'] = str_replace('type="text"', 'type="search"', $vars['search_form']);
}
/**
 * 
 */
function grass_search_form($form, &$form_state) {
    $form = search_form($form, $form_state);
    $form['#attributes']['class'][] = 'navbar-search';
    $form['#attributes']['class'][] = 'pull-left';
    $form['basic']['keys']['#title'] = '';
    $form['basic']['keys']['#attributes']['class'][] = 'search-query';
    $form['basic']['keys']['#attributes']['class'][] = 'span2';
    $form['basic']['keys']['#attributes']['placeholder'] = t('Search');
    unset($form['basic']['submit']);
    unset($form['basic']['#type']);
    unset($form['basic']['#attributes']);
    $form += $form['basic'];
    unset($form['basic']);
    return $form;
}
/**
 * Returns navigational links based on a menu tree
 */
function grass_menu_navigation_links($tree, $lvl = 0) {
    $result = array();
    if (count($tree) > 0) {
        foreach ($tree as $id => $item) {
            $new_item = array('title' => $item['link']['title'], 'link_path' => $item['link']['link_path'], 'href' => $item['link']['href']);
            if ($lvl < 1){
                $new_item['below'] = grass_menu_navigation_links($item['below'], $lvl + 1);
            }
            $result['menu-' . $item['link']['mlid']] = $new_item;
        }
    }
    return $result;
}
/**
 * Implements hook_form_alter().
 */
function grass_form_alter(&$form, &$form_state, $form_id) {
    $form_ids = array(
        'node_form',
        'system_site_information_settings',
        'user_profile_form',
        'node_delete_confirm',
    );
    if (isset($form['#form_id']) && !in_array($form['#form_id'], $form_ids) && !isset($form['#node_edit_form'])) {
        $form['actions']['#theme_wrappers'] = array();
    }
}
/**
 * Returns HTML for a form element label and required marker.
 */
function grass_form_element_label(&$variables) {
    $element = $variables['element'];
    $t = get_t();

    if ((!isset($element['#title']) || $element['#title'] === '') && empty($element['#required'])) {
        return '';
    }
    $required = !empty($element['#required']) ? theme('form_required_marker', array('element' => $element)) : '';
    $title = filter_xss_admin($element['#title']);
    $attributes = array();
    if ($element['#title_display'] == 'after') {
        $attributes['class'][] = 'option';
        $attributes['class'][] = $element['#type'];
    }elseif ($element['#title_display'] == 'invisible') {
        $attributes['class'][] = 'element-invisible';
    }
    if (!empty($element['#id'])) {
        $attributes['for'] = $element['#id'];
    }
    if ($element['#type'] != 'radio') {
        $attributes['class'][] = 'control-label';
    }
    $output = '';
    if (isset($variables['#children'])) {
        $output .= $variables['#children'];
    }
    $output .= $t('!title !required', array('!title' => $title, '!required' => $required));
    return ' <label' . drupal_attributes($attributes) . '>' . $output . "</label>\n";
}
/**
 * Preprocessor for theme('button').
 */
function grass_preprocess_button(&$vars) {
    $vars['element']['#attributes']['class'][] = 'btn';

    if (isset($vars['element']['#value'])) {
        $classes = array(
            t('Save and add')      => 'btn-info',
            t('Add another item')  => 'btn-info',
            t('Add effect')        => 'btn-default',
            t('Add and configure') => 'btn-default',
            t('Update style')      => 'btn-default',
            t('Download feature')  => 'btn-primary',
            t('Save')              => 'btn-default',
            t('Apply')             => 'btn-primary',
            t('Create')            => 'btn-default',
            t('Confirm')           => 'btn-info',
            t('Submit')            => 'btn-default',
            t('Export')            => 'btn-default',
            t('Import')            => 'btn-default',
            t('Restore')           => 'btn-default',
            t('Rebuild')           => 'btn-default',
            t('Search')            => 'btn-default',
            t('Add')               => 'btn-info',
            t('Update')            => 'btn-info',
            t('Delete')            => 'btn-danger',
            t('Remove')            => 'btn-danger',
            t('Log in')            => 'btn-info',
            t('E-mail new password') => 'btn-info',
        );
        foreach ($classes as $search => $class) {
            if (strpos($vars['element']['#value'], $search) !== FALSE) {
                $vars['element']['#attributes']['class'][] = $class;
                break;
            }
        }
    }
}
//PAGE
/**
 * 
 */
function grass_item_list($variables) {
    $items      = $variables['items'];
    $title      = $variables['title'];
    $type       = $variables['type'];
    $attributes = $variables['attributes'];
    $output     = '';
    if (isset($title)) {
        $output .= '<h3>' . $title . '</h3>';
    }
    if (!empty($items)) {
        $output .= "<$type" . drupal_attributes($attributes) . '>';
        $num_items = count($items);
        foreach ($items as $i => $item) {
            $attributes = array();
            $children = array();
            $data = '';
            if (is_array($item)) {
                foreach ($item as $key => $value) {
                    if ($key == 'data') {
                        $data = $value;
                    } elseif ($key == 'children') {
                        $children = $value;
                    } else {
                        $attributes[$key] = $value;
                    }
                }
            } else {
                $data = $item;
            }
            if (count($children) > 0) {
                $data .= theme_item_list(
                        array(
                            'items'      => $children, 
                            'title'      => NULL, 
                            'type'       => $type, 
                            'attributes' => $attributes
                        )
                );
            }
            if ($i == 0) {
                $attributes['class'][] = 'first';
            }
            if ($i == $num_items - 1) {
                $attributes['class'][] = 'last';
            }
            $output .= '<li' . drupal_attributes($attributes) . '>' . $data . "</li>\n";
        }
        $output .= "</$type>";
    }
    return $output;
}
/**
 * Returns HTML for status and/or error messages, grouped by type.
 */
function grass_status_messages($variables) {
    $display = $variables['display'];
    $output  = '';
    $status_heading = array(
        'status'  => t('Status message'),
        'error'   => t('Error message'),
        'warning' => t('Warning message'),
        'info'    => t('Info message'),
    );
    $status_class = array(
        'status'  => 'alert alert-success',
        'error'   => 'alert alert-danger',
        'warning' => 'alert alert-warning',
        'info'    => 'alert alert-info',
    );
    foreach (drupal_get_messages($display) as $type => $messages) {
        $class = (isset($status_class[$type])) ? $status_class[$type] : '';
        $output .= "<div class=\"$class\">\n";
        if (!empty($status_heading[$type])) {
            $output .= '<h2 class="element-invisible">' . $status_heading[$type] . "</h2>\n";
        }
        if (count($messages) > 1) {
            $output .= " <ul>\n";
            foreach ($messages as $message) {
                $output .= '  <li>' . $message . "</li>\n";
            }
            $output .= " </ul>\n";
        } else {
            $output .= $messages[0];
        }
        $output .= "</div>\n";
    }
    return $output;
}
/**
 * 
 */
function grass_preprocess_table(&$variables) {
    if (isset($variables['attributes']['class']) && is_string($variables['attributes']['class'])) {
        $variables['attributes']['class'] = explode(' ', $variables['attributes']['class']);
    }
    $variables['attributes']['class'][] = 'table';
    $variables['attributes']['class'][] = 'table-striped';
}
//MENU
/**
 * Returns HTML for primary and secondary local tasks.
 */
function grass_menu_local_tasks(&$vars) {
    $output = '';
    if (!empty($vars['primary'])) {
        $vars['primary']['#prefix'] = '<ul class="nav nav-tabs">';
        $vars['primary']['#suffix'] = '</ul>';
        $output .= drupal_render($vars['primary']);
    }
    if (!empty($vars['secondary'])) {
        $vars['secondary']['#prefix'] = '<ul class="nav nav-pills">';
        $vars['secondary']['#suffix'] = '</ul>';
        $output .= drupal_render($vars['secondary']);
    }
    return $output;
}
/**
 * Returns HTML for primary and secondary local tasks.
 *
 * @ingroup themeable
 */
function grass_menu_local_task($variables) {
    $link      = $variables['element']['#link'];
    $link_text = $link['title'];
    $classes   = array();

    if (!empty($variables['element']['#active'])) {
        $active = '<span class="element-invisible">' . t('(active tab)') . '</span>';
        if (empty($link['localized_options']['html'])) {
            $link['title'] = check_plain($link['title']);
        }
        $link['localized_options']['html'] = TRUE;
        $link_text = t('!local-task-title!active', array(
                '!local-task-title' => $link['title'], 
                '!active' => $active
            )
        );
        $classes[] = 'active';
    }
    $children = '';
    if (element_children($variables['element'])) {
        $link['localized_options']['attributes']['class'][]       = 'dropdown-toggle';
        $link['localized_options']['attributes']['data-toggle'][] = 'dropdown';
        $classes[] = 'dropdown';
        $children  = drupal_render_children($variables['element']);
        $children  = '</b><ul class="secondary-tabs dropdown-menu">' . $children . "</ul>";
        return '<li class="' . implode(' ', $classes) . '"><a href="#"' . drupal_attributes($link['localized_options']['attributes']) . '>' . $link_text . '<b class="caret"></a>' . $children . "</li>\n";
    } else {
        return '<li class="' . implode(' ', $classes) . '">' . l($link_text, $link['href'], $link['localized_options']) . $children . "</li>\n";
    }
}
/**
 * 
 */
function grass_menu_tree(&$variables) {
    return '<ul class="menu nav">' . $variables['tree'] . '</ul>';
}
/**
 * 
 */
function grass_menu_link(array $variables) {
    $element  = $variables['element'];
    $sub_menu = '';
    $element['#title'] = check_plain($element['#title']);
    if ($element['#below']) {
        unset($element['#below']['#theme_wrappers']);
        $sub_menu = '<ul>' . drupal_render($element['#below']) . '</ul>';
        $element['#localized_options']['html'] = TRUE;
        $element['#href'] = "";
    }
    $output = l($element['#title'], $element['#href'], $element['#localized_options']);
    return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}