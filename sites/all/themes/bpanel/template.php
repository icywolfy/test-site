<?php

function bpanel_preprocess_page(&$vars) {
  if (!empty($vars['node'])) {
    $vars['theme_hook_suggestions'][] = 'page__' . $vars['node']->type;
  }
}
