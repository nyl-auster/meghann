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
    color: silver; /*to notice it, is white*/
    z-index: 999;
  }

  .slick-prev:before, .slick-next:before {
    font-size: 40px;
  }

  .slick-prev {
    left: 40px;
  }

  .slick-next {
    right: 40px;
  }

</style>
<?php
/**
 * variables :
 * $slides : nodes servant de slide
 */


?>

<div class="homepage-slider">
  <?php foreach ($slides as $slide) : ?>
    <div>
      <?php
      $image = field_get_items('node', $slide, 'field_slide_image');
      $output = field_view_value('node', $slide, 'field_slide_image', $image[0], array(
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