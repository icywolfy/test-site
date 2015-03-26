<?php
/**
 * @file
 * Contains the theme's functions to manipulate Drupal's default markup.
 *
 * Complete documentation for this file is available online.
 * @see https://drupal.org/node/1728096
 */


/**
 * Override or insert variables into the maintenance page template.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("maintenance_page" in this case.)
 */
/* -- Delete this line if you want to use this function
function STARTERKIT_preprocess_maintenance_page(&$variables, $hook) {
  // When a variable is manipulated or added in preprocess_html or
  // preprocess_page, that same work is probably needed for the maintenance page
  // as well, so we can just re-use those functions to do that work here.
  STARTERKIT_preprocess_html($variables, $hook);
  STARTERKIT_preprocess_page($variables, $hook);
}
// */

/**
 * Override or insert variables into the html templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("html" in this case.)
 */
/* -- Delete this line if you want to use this function
function STARTERKIT_preprocess_html(&$variables, $hook) {
  $variables['sample_variable'] = t('Lorem ipsum.');

  // The body tag's classes are controlled by the $classes_array variable. To
  // remove a class from $classes_array, use array_diff().
  //$variables['classes_array'] = array_diff($variables['classes_array'], array('class-to-remove'));
}
// */

/**
 * Override or insert variables into the page templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("page" in this case.)
 */
function pooltech_preprocess_page(&$variables, $hook) {
  if (!empty($variables['node'])) {
    $variables['theme_hook_suggestions'][] = 'page__' . $variables['node']->type;
  }
}

function pooltech_templates_preprocess(&$variables, $hook) {
  var_export($variables, $hook);
}

/**
 * Override or insert variables into the node templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("node" in this case.)
 */
function pooltech_preprocess_node(&$variables, $hook) {
  // Optionally, run node-type-specific preprocess functions, like
  // STARTERKIT_preprocess_node_page() or STARTERKIT_preprocess_node_story().
dpm($variables);
  if (!isset($variables['node']->view_mode)) {
    $variables['node']->view_mode = null;
  }
  $functionType = __FUNCTION__ . '_' . $variables['node']->type;
  $functionTypeView = $functionType . '_' . ($variables['node']->view_mode ?: 'undefined');

  if (function_exists($functionTypeView)) {
    $functionTypeView($variables, $hook);
  } elseif (function_exists($functionType)) {
    $functionType($variables, $hook);
  }
}

function _pooltech_preprocess_node_h_section_common(&$variables, $hook) {
  $node = $variables['node'];
  $variables += array(
    'x_color_scheme' => null,
    'x_splash_image' => null,
    'x_body' => null,
    'background_image_url' => null,
    'x_content_alignment' => null,
    'x_background_image' => null,
    'css_color_style' => null,
    'callout_text' => null,
    'callout_url' => '#',
  );

  // Body Content
  $body = _pooltech_get_value($node, 'body');
  $variables['x_body'] = $body;

  // Color Scheme
  if (!empty($variables['field_color_scheme'])) {
    $variables['x_color_scheme'] = _pooltech_get_value($node, 'field_color_scheme');
    if ($variables['x_color_scheme'] === 'manual') {
      $variables['x_color_scheme'] = null;
    }
  }

  // Manual CSS Colors
  if (empty($variables['x_color_scheme'])) {
    $backgroundColor = _pooltech_get_value($node, 'field_background_color');
    $contentColor = _pooltech_get_value($node, 'field_content_color');
    $colorStyleRenders = array_filter(array('background-color' => render($backgroundColor),
        'color' => render($contentColor)));
    $colorStyle = array();
    foreach ($colorStyleRenders as $key => $value) {
      $colorStyle[] = "$key: $value";
    }
    $colorStyle = implode(';', $colorStyle);
    $variables['css_color_style'] = $colorStyle;
  }

  // Splash Image
  if (!empty($variables['field_splash_image'])) {
    $splashImage = _pooltech_get_value($node, 'field_splash_image');
    $splashImage['#item']['attributes']['class'] = 'splash';
    $variables['x_splash_image'] = $splashImage;
  }

  if (!empty($variables['field_background_image'])) {
    $splashImage = _pooltech_get_value($node, 'field_background_image');
    dpm($splashImage);
    $variables['x_background_image'] = $splashImage;
    $variables['background_image_url'] = file_create_url($splashImage['#item']['uri']);
  }

  // Field Alignment
  if (!empty($variables['field_content_alignment'])) {
    $variables['x_content_alignment'] = _pooltech_get_value($node, 'field_content_alignment');
    $variables['classes_array'][] = 'content-align-' . ($variables['x_content_alignment'] ?: 'unknown');
  }

  // Callout
  if (!empty($variables['field_callout_button'])) {
    $calloutButton = _pooltech_get_value($node, 'field_callout_button');
    $title = $calloutButton['#element']['title'];
    $url = url($calloutButton['#element']['url']);
    $variables['callout_text'] = $title;
    $variables['callout_url'] = $url;
  }

  // Background Styles
  if (!empty($variables['css_color_style'])) {
    $variables['attributes_array']['style'] = $variables['css_color_style'];
  }
  // Background Image Styles
  $variables['container_styles'] = '';
  $variables['container_styles'] .= "background-image: url({$variables['background_image_url']});";

  if (empty($variables['background_image_url']) && empty($variables['x_splash_image'])) {
    $variables['classes_array'][] = 'no-background-image';
  }


}

function pooltech_preprocess_node_h_section_simple_content(&$variables, $hook) {
  _pooltech_preprocess_node_h_section_common($variables, $hook);
}

// */

/**
 * Override or insert variables into the comment templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("comment" in this case.)
 */
/* -- Delete this line if you want to use this function
function STARTERKIT_preprocess_comment(&$variables, $hook) {
  $variables['sample_variable'] = t('Lorem ipsum.');
}
// */

/**
 * Override or insert variables into the region templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("region" in this case.)
 */
/* -- Delete this line if you want to use this function
function STARTERKIT_preprocess_region(&$variables, $hook) {
  // Don't use Zen's region--sidebar.tpl.php template for sidebars.
  //if (strpos($variables['region'], 'sidebar_') === 0) {
  //  $variables['theme_hook_suggestions'] = array_diff($variables['theme_hook_suggestions'], array('region__sidebar'));
  //}
}
// */

/**
 * Override or insert variables into the block templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("block" in this case.)
 */
/* -- Delete this line if you want to use this function
function STARTERKIT_preprocess_block(&$variables, $hook) {
  // Add a count to all the blocks in the region.
  // $variables['classes_array'][] = 'count-' . $variables['block_id'];

  // By default, Zen will use the block--no-wrapper.tpl.php for the main
  // content. This optional bit of code undoes that:
  //if ($variables['block_html_id'] == 'block-system-main') {
  //  $variables['theme_hook_suggestions'] = array_diff($variables['theme_hook_suggestions'], array('block__no_wrapper'));
  //}
}
// */

function _pooltech_get_value($node, $fieldName) {
  $items = field_get_items('node', $node, $fieldName);
  $value = field_view_value('node', $node, $fieldName, $items[0]);
  return $value;
}
