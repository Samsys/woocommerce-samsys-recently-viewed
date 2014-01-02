<?php 
/**
 * Creates Samsys Catalog widgets.
 *
 * @see WP_Widget::widget()
 *
 * @package   samsys_WC_recently_viewed
 * @author    Ricardo Correia, Samsys <ricardo.correia@samsys.pt>
 * @license   GPL-2.0+
 * @link      http://samsys.pt
 * @copyright 2013 Samsys - Consultoria e Soluções Informáticas, Lda.
 */
function register_ssys_WC_recently_viewed_widget() {
    register_widget( 'Ssys_WC_Recently_Viewed' );
}
add_action( 'widgets_init', 'register_ssys_WC_recently_viewed_widget' );

/**
 * Initializes featured posts widget.
 *
 * @see WP_Widget::widget()
 * 
 * @since   1.0.0
 */
class Ssys_WC_Recently_Viewed extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		
		parent::__construct(
			'Ssys_WC_Recently_Viewed', // Base ID
			__('WooCommerce Recently Viewed Products from all visitors by Samsys' , 'samsys-WC-recently-viewed'), // Name
			array( 'description' => __( 'Adds a widget with the recently viewed products from all website visitors. ' , 'Ssys_WC_Recently_Viewed' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		global $woocommerce;
		
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];
		//Print Title if defined
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
		
		//Get Recently Viewed Products
		$args = array(
				   'post_type' => 'product',
				   'post_status' => array( 'publish' ),
				   'posts_per_page' => $instance['num_prod'],
				   'orderby' => 'meta_value_num',
    			   'order' => 'DESC',
    			   'meta_key' => '_ssys_Last_Viewed_Date'
				);
				
		$recent_query = new WP_Query($args);
		
		echo '<ul class="product_list_widget">';
		
		if($recent_query->have_posts()) :
		
			while ($recent_query->have_posts()) : $recent_query->the_post();
			
				global $product;
				
				if($instance['force_including_tax']){
						
					$price = woocommerce_price($product->get_price_including_tax()); 
				
				}else{
				
					$price = $product->get_price_html(); 
				
				}
				
				echo '<li>
					<a href="' . get_permalink() . '">
						' . ( has_post_thumbnail() ? get_the_post_thumbnail( $post->ID, 'shop_thumbnail' ) : woocommerce_placeholder_img( 'shop_thumbnail' ) ) . ' ' . get_the_title() . '
					</a> ' . $price . '
				</li>';
			
			endwhile; 
			wp_reset_postdata();
			
		endif;
		
		echo '</ul>';
		
		echo $args['after_widget'];
		
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = esc_attr($instance[ 'title' ]);
		}
		else {
			$title = __( 'New title', 'Ssys_WC_Recently_Viewed' );
		}
		
		if ( isset( $instance[ 'num_prod' ] ) ) {
			$num_prod = esc_attr($instance[ 'num_prod' ]);
		}
		else {
			$num_prod = __( '4', 'Ssys_WC_Recently_Viewed' );
		}
		
		if ( isset( $instance[ 'force_including_tax' ] ) ) {
			$force_including_tax = esc_attr($instance[ 'force_including_tax' ]);
		}
		else {
			$force_including_tax = '';
		}
		
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:','Ssys_WC_Recently_Viewed' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>
		
		<p>
		<label for="<?php echo $this->get_field_id( 'num_prod' ); ?>"><?php _e( 'Number of products to display:','Ssys_WC_Recently_Viewed' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'num_prod' ); ?>" name="<?php echo $this->get_field_name( 'num_prod' ); ?>" type="text" value="<?php echo esc_attr( $num_prod ); ?>" />
		</p>
		
		<p>
		<input id="<?php echo $this->get_field_id( 'force_including_tax' ); ?>" name="<?php echo $this->get_field_name( 'force_including_tax' ); ?>" type="checkbox" value="1" <?php checked( '1', $force_including_tax ); ?> />
		<label for="<?php echo $this->get_field_id( 'force_including_tax' ); ?>"><?php _e( 'Force Prices Including Tax','Ssys_WC_Recently_Viewed' ); ?></label>
		</p>
		
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['num_prod'] = ( ! empty( $new_instance['num_prod'] ) ) ? strip_tags( $new_instance['num_prod'] ) : '';
		$instance['force_including_tax'] = ( ! empty( $new_instance['force_including_tax'] ) ) ? strip_tags( $new_instance['force_including_tax'] ) : '';
		
		return $instance;
	}

} // class Ssys_WC_Recently_Viewed
