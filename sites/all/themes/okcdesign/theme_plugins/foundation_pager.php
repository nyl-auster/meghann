<?php

/**
 * Thanks to zurb_foundation theme for this !
 * Class foundation_pager
 */
class foundation_pager extends theme_plugin {

  /**
   * Replace Drupal pagers with Foundation pagers.
   */
  function hook_pager(&$variables) {

    $tags = $variables['tags'];
    $element = $variables['element'];
    $parameters = $variables['parameters'];
    $quantity = $variables['quantity'];
    global $pager_page_array, $pager_total;

    // Calculate various markers within this pager piece:
    // Middle is used to "center" pages around the current page.
    $pager_middle = ceil($quantity / 2);
    // Current is the page we are currently paged to.
    $pager_current = $pager_page_array[$element] + 1;
    // First is the first page listed by this pager piece (re-quantify).
    $pager_first = $pager_current - $pager_middle + 1;
    // Last is the last page listed by this pager piece (re-quantify)
    $pager_last = $pager_current + $quantity - $pager_middle;
    // Max is the maximum page number.
    $pager_max = $pager_total[$element];
    // End of marker calculations.

    // Prepare for generation loop.
    $i = $pager_first;
    if ($pager_last > $pager_max) {
      // Adjust "center" if at end of query.
      $i = $i + ($pager_max - $pager_last);
      $pager_last = $pager_max;
    }
    if ($i <= 0) {
      // Adjust "center" if at start of query.
      $pager_last = $pager_last + (1 - $i);
      $i = 1;
    }
    // End of generation loop preparation.

    $li_first = theme('pager_first', array('text' => (isset($tags[0]) ? $tags[0] : t('« first')), 'element' => $element, 'parameters' => $parameters));
    $li_previous = theme('pager_previous', array('text' => (isset($tags[1]) ? $tags[1] : t('‹ previous')), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
    $li_next = theme('pager_next', array('text' => (isset($tags[3]) ? $tags[3] : t('next ›')), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
    $li_last = theme('pager_last', array('text' => (isset($tags[4]) ? $tags[4] : t('last »')), 'element' => $element, 'parameters' => $parameters));

    if ($pager_total[$element] > 1) {
      if ($li_first) {
        $items[] = array(
          'class' => array('arrow'),
          'data' => $li_first,
        );
      }
      if ($li_previous) {
        $items[] = array(
          'class' => array('arrow'),
          'data' => $li_previous,
        );
      }

      // When there is more than one page, create the pager list.
      if ($i != $pager_max) {
        if ($i > 1) {
          $items[] = array(
            'class' => array('unavailable'),
            'data' => '<a href="">&hellip;</a>',
          );
        }
        // Now generate the actual pager piece.
        for (; $i <= $pager_last && $i <= $pager_max; $i++) {
          if ($i < $pager_current) {
            $items[] = array(
              'data' => theme('pager_previous', array('text' => $i, 'element' => $element, 'interval' => ($pager_current - $i), 'parameters' => $parameters)),
            );
          }
          if ($i == $pager_current) {
            $items[] = array(
              'class' => array('current'),
              'data' => '<a href="">' . $i . '</a>',
            );
          }
          if ($i > $pager_current) {
            $items[] = array(
              'data' => theme('pager_next', array('text' => $i, 'element' => $element, 'interval' => ($i - $pager_current), 'parameters' => $parameters)),
            );
          }
        }
        if ($i < $pager_max) {
          $items[] = array(
            'class' => array('unavailable'),
            'data' => '<a href="">&hellip;</a>',
          );
        }
      }
      // End generation.
      if ($li_next) {
        $items[] = array(
          'class' => array('arrow'),
          'data' => $li_next,
        );
      }
      if ($li_last) {
        $items[] = array(
          'class' => array('arrow'),
          'data' => $li_last,
        );
      }

      $pager_links = array(
        '#theme' => 'item_list',
        '#items' => $items,
        '#attributes' => array('class' => array('pagination', 'pager')),
      );

      if (theme_get_setting('zurb_foundation_pager_center')) {
        $pager_links['#prefix'] = '<div class="pagination-centered">';
        $pager_links['#suffix'] = '</div>';
      }

      $pager_links = drupal_render($pager_links);

      return '<h2 class="element-invisible">' . t('Pages') . '</h2>' . $pager_links;
    }



  }

}