<?php
/*
Plugin Name: Google Authorship Widget
Plugin URI: http://digitalfair.tk
Description: Place a Short Bio on your Wordpress blog as a widget containing the required authorship markup.
Version: 1.0
Author: Mallikarjun Yawalkar
Author URI: http://digitalfair.tk
*/

global $theme;

$Google_Authorship_defaults = array(
    'title' => 'Enter Author Name',
    'description' => 'Add your short bio here.',
    'link' => 'Link to your google+ profile',
    'image' => 'Image must be at least 46*46',
    'image_position' => 'before_title',
    'image_align' => 'aligncenter',
    'link_title' => 'true',
    'link_description' => '',
    'link_image' => ''
);

$theme->options['widgets_options']['GA_Bio'] = is_array($theme->options['widgets_options']['GA_Bio'])
    ? array_merge($Google_Authorship_defaults, $theme->options['widgets_options']['GA_Bio'])
    : $Google_Authorship_defaults;
        
add_action('widgets_init', create_function('', 'return register_widget("Google_Authorship");'));

class Google_Authorship extends WP_Widget 
{
    function Google_Authorship() 
    {
        $widget_options = array('description' => __('Highlight a product or service with a custom image.', 'digitalfair') );
        $control_options = array( 'width' => 480);
		$this->WP_Widget('Google_Authorship', '&raquo; Google Authorship Widget', $widget_options, $control_options);
    }

    function widget($args, $instance)
    {
        global $theme;
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
        
        if($instance['image']) {
            $image_align = $instance['image_align'];
            if($instance['image_position'] == 'bottom') {
                $image_align .= ' inbottom';
            }
            if($instance['link_image'] && $instance['link']) {
                $output_image = '<a rel="author" href="' . $instance['link'] .'"><img src="' . $instance['image'] .'" class="' . $image_align . '" /></a>';
            } else {
                $output_image = '<img src="' . $instance['image'] .'" class="' . $image_align . '" />';
            }
        } else {
            $output_image = false;
        }
         
        
        
    ?>
        <ul class="widget-wrap"><li class="Google_Authorship">
            <?php
                if($output_image && $instance['image_position'] == 'before_title') {
                    echo $output_image;
                }
                
                if ($title) {
                    if($instance['link'] && $instance['link_title']) {
                        ?><h3 class="widgettitle"><a rel="author" href="<?php echo $instance['link']; ?>"><?php echo "+".$title; ?></a></h3><?php
                    } else {
                        ?><h3 class="widgettitle"><?php echo $title; ?></h3><?php
                    }
                }
             ?>
            <ul>
        	   <li class="Google_Authorship-description">
                <?php
                    if($output_image && $instance['image_position'] == 'before_description') {
                        echo $output_image;
                    }
                    
                    if($instance['description']) {
                        if($instance['link'] && $instance['link_description']) {
                            echo '<a rel="author" href="' . $instance['link'] .'">' . $instance['description'] .'<a/>';
                        } else {
                            echo $instance['description'];
                        }
                    }
                                        
                    if($output_image && $instance['image_position'] == 'bottom') {
                        echo $output_image;
                    }
                ?>
               </li>
            </ul>
        </li></ul>
        <?php
    }

    function update($new_instance, $old_instance) 
    {

    	$instance = $old_instance;
    	$instance['title'] = strip_tags($new_instance['title']);
        $instance['description'] = $new_instance['description'];
        $instance['link'] = strip_tags($new_instance['link']);
        $instance['image'] = strip_tags($new_instance['image']);
        $instance['image_position'] = strip_tags($new_instance['image_position']);
        $instance['image_align'] = strip_tags($new_instance['image_align']);
        $instance['link_title'] = strip_tags($new_instance['link_title']);
        $instance['link_description'] = strip_tags($new_instance['link_description']);
        $instance['link_image'] = strip_tags($new_instance['link_image']);
        return $instance;
    }
    
    function form($instance) 
    {	
        global $theme;
		$instance = wp_parse_args( (array) $instance, $theme->options['widgets_options']['GA_Bio'] );
        
        ?>
        
        <div class="tt-widget">
            <table width="100%">
                <tr>
                    <td class="tt-widget-label" width="25%"><label for="<?php echo $this->get_field_id('title'); ?>">Author Name:</label></td>
                    <td class="tt-widget-content" width="75%"><input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" /></td>
                </tr>
                
                <tr>
                    <td class="tt-widget-label" width="25%"><label for="<?php echo $this->get_field_id('image'); ?>">Image Url:</label></td>
                    <td class="tt-widget-content" width="75%"><input class="widefat" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>" type="text" value="<?php echo esc_attr($instance['image']); ?>" /></td>
                </tr>
                
                <tr>
                    <td class="tt-widget-label"><label for="<?php echo $this->get_field_id('description'); ?>">Description:</label></td>
                    <td class="tt-widget-content"><textarea class="widefat" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>" style="height: 160px;"><?php echo esc_attr($instance['description']); ?></textarea></td>
                </tr>
                
                <tr>
                    <td class="tt-widget-label" width="25%"><label for="<?php echo $this->get_field_id('link'); ?>">Google Profile Url:</label></td>
                    <td class="tt-widget-content" width="75%"><input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo esc_attr($instance['link']); ?>" /></td>
                </tr>
                                
                <tr>
                    <td class="tt-widget-label">Image Options:</td>
                    <td class="tt-widget-content">
                        Position: <select name="<?php echo $this->get_field_name('image_position'); ?>">
                            <option value="before_title" <?php selected('before_title', $instance['image_position']); ?> >Before Title</option>
                            <option value="before_description"  <?php selected('before_description', $instance['image_position']); ?>>Before Description</option>
                            <option value="bottom" <?php selected('bottom', $instance['image_position']); ?>>Bottom</option>
                        </select>
                        
                         &nbsp; Float: <select name="<?php echo $this->get_field_name('image_align'); ?>">
                            <option value="alignleft" <?php selected('alignleft', $instance['image_align']); ?> >Left</option>
                            <option value="alignright"  <?php selected('alignright', $instance['image_align']); ?>>Right</option>
                            <option value="aligncenter" <?php selected('aligncenter', $instance['image_align']); ?>>Center</option>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <td class="tt-widget-label">Add Links To:</td>
                    <td class="tt-widget-content">
                        <input type="checkbox" name="<?php echo $this->get_field_name('link_title'); ?>"  <?php checked('true', $instance['link_title']); ?> value="true" />  Title
                        <br /><input type="checkbox" name="<?php echo $this->get_field_name('link_description'); ?>"  <?php checked('true', $instance['link_description']); ?> value="true" /> Description
                        <br /><input type="checkbox" name="<?php echo $this->get_field_name('link_image'); ?>"  <?php checked('true', $instance['link_image']); ?> value="true" />  Image
                    </td>
                </tr>
				<tr>
					<td colspan="2"><hr /></td>
				</tr>
				<tr>
					<td colspan="2"><label>If you like this plugin, Please contribute your like on facebook:  </label><br /></td>
				</tr>
				<tr>
					<td colspan="2">
					<iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Ffacebook.com%2Fdigital.fair&amp;send=false&amp;layout=button_count&amp;width=150&amp;show_faces=false&amp;action=subscribe&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:160px; height:21px;" allowTransparency="true"></iframe>		

					<iframe src="http://www.facebook.com/plugins/subscribe.php?href=http://www.facebook.com/yawalkar.nitin&amp;layout=button_count&amp;show_faces=false&amp; width=150&amp;font&amp;colorscheme=light&amp;height=20px" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:160; height:20px;\" allowTransparency="true"></iframe>
					</td>
				</tr>
                
            </table>
          </div>
        <?php 
    }
} 
?>