<?php
/**
 * @file
 * Create drush commands to quickly :
 * - create a subtheme depending on okcdesign
 * - create a new plugin for okcdesign.
 */

/**
 * Implementation of hook_drush_command().
 */
function okclayout_drush_command() {
  $items = array();
  $items['okc-layout'] = array(
    'description' => 'Create an OKC Layout sub-theme',
    //'aliases' => array('okc-layout'),
    'arguments' => array(
      'name'         => 'Your sub-theme name.',
      'machine_name' => 'A machine-readable name for your theme, optional only  [a-z, 0-9] ',
    ),
    'options' => array(
      'description'  => 'Your sub-theme description.',
      'machine-name' => '[a-z, 0-9] A machine-readable name for your theme.',
    ),
    'examples' => array(
      'drush okc-layout "homepage"' => 'Create an okclayout_homepage subtheme in layouts directory.',
    ),
  );
  return $items;
}

/**
 * Create a Zurb foundation sub-theme.
 */
function drush_okclayout_okc_layout($name = NULL, $machine_name = NULL, $description = NULL) {
  if (empty($name)) {
    drush_set_error(dt("Please provide a name for the sub-theme.\nUSAGE:\tdrush ost [name] [machine_name !OPTIONAL] [description !OPTIONAL]\n"));
    return;
  }
  //Filter everything but letters, numbers, underscores, and hyphens
  $machine_name = !empty($machine_name) ? preg_replace('/[^a-z0-9_-]+/', '', strtolower($machine_name)) : preg_replace('/[^a-z0-9_-]+/', '', strtolower($name));
  // Eliminate hyphens
  $machine_name = str_replace('-', '_', $machine_name);
  // prefix machine name with "okclayout_"
  $machine_name = variable_get('theme_default') . '_layout_' . $machine_name;

  // Find default theme default
  $default_theme_path = drush_locate_root() . '/' . drupal_get_path('theme', variable_get('theme_default'));

//  drush_print(dirname($default_theme_path));

  $subtheme_path = $default_theme_path . '/layouts/' . $machine_name;
  drush_print($subtheme_path);

  // create layouts directory, containing subthemes that will be used as a layout
  if (!file_exists("$default_theme_path/layouts")) {
    drush_op('mkdir', "$default_theme_path/layouts");
  }

  if (!file_exists($subtheme_path)) {
    drush_op('mkdir', "$subtheme_path");
  }

  // create subtheme info file
  $lines = array(
    "name = $machine_name",
    "description = Layout for ". variable_get('theme_default'),
    "base theme = " . variable_get('theme_default'),
    "okclayout = TRUE",
    "engine = phptemplate",
    "core = 7.x",
    "",
  );
  // copy regions from base theme.
  $regions = system_region_list(variable_get('theme_default'), REGIONS_VISIBLE);
  foreach ($regions as $id => $name) {
    if (!in_array($id, array('dashboard_sidebar', 'dashboard_inactive', 'dashboard_main'))) {
      $lines[] = "regions[$id] = $name";
    }
  }

  $file = "$subtheme_path/$machine_name.info";
  drush_op('file_put_contents', $file, implode($lines, "\r\n"));


  $lines = array(
    '<?php',
    '',
    '/**',
    ' * Implements hook_okclayout_condition()',
    ' * @see okclayout.module',
    ' * ',
    ' */',
    'function ' . $machine_name . '_okclayout_condition() {',
    '  // This theme will be active only when this function return TRUE. Example : ',
    '  // if (drupal_is_front_page()) return TRUE;',
    '}',
    '',
    '',
  );
  $file = "$subtheme_path/template.php";
  drush_op('file_put_contents', $file, implode($lines, "\r\n"));

  drush_pm_enable_validate($machine_name);
  drush_pm_enable($machine_name);

  // Notify user of the newly created theme.
  drush_print(dt("\nSubtheme layout !name was created. \n",
    array(
      '!name' => $machine_name,
    )
  ));

}

/**
 * Implements hook_drush_help().
 */
function okclayout_drush_help($section) {
  switch ($section) {
    case 'drush:okc-layout':
      return dt("Create an OKC layout in default theme layouts directory");
  }
}

/**
 * Internal helper: Replace strings in a file.
 */
function okclayout_file_str_replace($file_path, $find, $replace) {
  $file_contents = file_get_contents($file_path);
  $file_contents = str_replace($find, $replace, $file_contents);
  file_put_contents($file_path, $file_contents);
}
