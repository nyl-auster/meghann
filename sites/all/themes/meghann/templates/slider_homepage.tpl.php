<?php
/**
 * Variables :
 * - slides : node utilisés comme slide.
 */
?>

<script>
  jQuery(document).ready(function(){
    jQuery('.homepage-slider').slick({
      arrows: true,
      //centerMode: true,
      //centerPadding: '40px',
      slidesToShow: 1,
      fade: true,
      dots: true
    });
  });
</script>

<style>

  .slick-prev, .slick-next{

    z-index: 999;
  }

  .slick-prev:before, .slick-next:before {
    font-size: 40px;
    color: greenyellow; /*to notice it, is white*/
  }

  .slick-prev {
    left: 40px;
  }

  .slick-next {
    right: 40px;
  }

</style>

<div class="homepage-slider">
  <?php foreach ($slides as $slide) : ?>
    <div>
      <?php
      $image = field_get_items('node', $slide, 'field_image');
      $output = field_view_value('node', $slide, 'field_image', $image[0], array(
          'type' => 'image',
          'settings' => array(
            'image_style' => 'slider_homepage',
          ),
        ));
      echo render($output);
      ?>
    </div>
  <?php endforeach ?>
</div>