<?php
/*
 * Grid template.
 *
 * This template can be overriden by copying this file to your-theme/bs-grid-main/sc-grid.php
 *
 * @author 	  bootScore
 * @package   bS Grid
 * @version   5.2.1.0

Post/Page/CPT Grid Shortcodes

Posts: 
[bs-grid type="post" category="cars, boats" order="ASC" orderby="date" posts="6"]

Child-pages: 
[bs-grid type="page" post_parent="21" order="ASC" orderby="title" posts="6"]

Custom post types:
[bs-grid type="isotope" tax="isotope_category" terms="dogs, cats" order="DESC" orderby="date" posts="5"]

Single items:
[bs-grid type="post" id="1, 15"]
[bs-grid type="page" id="2, 25"]
[bs-grid type="isotope" id="33, 31"]
*/


// Grid Shortcode
add_shortcode('bs-grid', 'bootscore_grid');
function bootscore_grid($atts) {

  ob_start();
  extract(shortcode_atts(array(
    'type' => 'post',
    'order' => 'date',
    'orderby' => 'date',
    'posts' => -1,
    'category' => '',
    'post_parent'    => '',
    'tax' => '',
    'terms' => '',
    'id' => ''
  ), $atts));

  $options = array(
    'post_type' => $type,
    'order' => $order,
    'orderby' => $orderby,
    'posts_per_page' => $posts,
    'category_name' => $category,
    'post_parent' => $post_parent,
  );

  $tax = trim($tax);
  $terms = trim($terms);
  if ($tax != '' && $terms != '') {
    $terms = explode(',', $terms);
    $terms = array_map('trim', $terms);
    $terms = array_filter($terms);
    $terms = array_unique($terms);
    unset($options['category_name']);
    $options['tax_query'] = array(array(
      'taxonomy' => $tax,
      'field'    => 'name',
      'terms'    => $terms,
    ));
  }

  if ($id != '') {
    $ids = explode(',', $id);
    $ids = array_map('intval', $ids);
    $ids = array_filter($ids);
    $ids = array_unique($ids);
    $options['post__in'] = $ids;
  }

  $query = new WP_Query($options);
  if ($query->have_posts()) { ?>

    <div class="row">
      <?php while ($query->have_posts()) : $query->the_post(); ?>

        <div class="column">
            <div class="card d-flex">
                <?php the_post_thumbnail('medium', array('class' => 'card-img-top')); ?>
                <div class="d-flex flex-column">    
                <h2 class="blog-post-title">
                    <a href="<?php the_permalink(); ?>">
                    <?php the_title(); ?>
                    </a>
                </h2>
                    <div class="card-text">
                        <?php //the_excerpt(); ?>
                    </div>
                    <div class="mt-auto d-flex">
                <a class="read-more" href="<?php the_permalink(); ?>"><?php _e('Xem chi tiết >', 'bootscore'); ?></a>
                <?php $post_date = get_the_date( 'g:i d/m/Y' ); echo $post_date; ?>
            </div>
                </div>
            </div>
        </div>

      <?php endwhile;
      wp_reset_postdata(); ?>
    </div><!-- .row -->

<?php $myvariable = ob_get_clean();
    return $myvariable;
  }
}

// Grid Shortcode End