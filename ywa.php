<?php
/*
Plugin Name: Yahoo Web Analytics
Plugin URI: http://www.rudishumpert.com/projects
Description: Add Yahoo! Analytics to your blog with all setting controlled in the admin section.
Version: 0.1.8
Author: Rudi Shumpert
Author URI: http://www.rudishumpert.com/
*/
session_start();
define('ywa_version', '0.1.8', true);

$ywa_options = get_option('ywa_admin_options'); 

// set an Yahoo Web Analytics option in the options table of WordPress
function ywa_set_option($option_name, $option_value) {
  $ywa_options = get_option('ywa_admin_options');
  $ywa_options[$option_name] = $option_value;
  update_option('ywa_admin_options', $ywa_options);
}

function ywa_get_option($option_name) {
  $ywa_options = get_option('ywa_admin_options'); 
  if (!$ywa_options || !array_key_exists($option_name, $ywa_options)) {
    $ywa_default_options=array();

    $ywa_default_options['account_id']                 = 'Your ID Here: 8675309';  
    $ywa_default_options['js_script_path']             = 'http://d.yimg.com/mi/ywa.js';  
    $ywa_default_options['img_script_path']            = 'http://a.analytics.yahoo.com';
    $ywa_default_options['domain_list']                = '*.yourdomain.com,*.yourotherdomain.com';
    $ywa_default_options['ywa_cf_num_loggedin']        = '1';
    $ywa_default_options['ywa_action_comment']         = '02';
    $ywa_default_options['ywa_track_admins']    	   = 'false' ;   



    add_option('ywa_admin_options', $ywa_default_options, 
               'Settings for Yahoo Web Analytics plugin');

    $result = $ywa_default_options[$option_name];
  } else {
    $result = $ywa_options[$option_name];
  }
  
  return $result;
}


function ywa_admin() {

  if (function_exists('add_options_page')) {
    add_options_page('Yahoo Web Analytics' /* page title */, 
                     'YWA' /* menu title */, 
                     8 /* min. user level */, 
                     basename(__FILE__) /* php file */ , 
                     'ywa_options' /* function for subpanel */);
  }

}


function ywa_options() {
  if (isset($_POST['advanced_options'])) {
    ywa_set_option('advanced_config', true);
  }
  if (isset($_POST['simple_options'])) {
    ywa_set_option('advanced_config', false);
  }
  if (isset($_POST['factory_settings'])) {
    $ywa_factory_options = array();
    update_option('ywa_admin_options', $ywa_factory_options);
    ?><div class="updated"><p><strong><?php _e('Default settings restored, remember to set Project ID', 'ywa')?></strong></p></div><?php
  }
  if (isset($_POST['info_update'])) {
    ?><div class="updated"><p><strong><?php 
    // process options form
    $ywa_options = get_option('ywa_admin_options');
    $ywa_options['account_id']           		 = $_POST['account_id'];
    $ywa_options['js_script_path']       		 = $_POST['js_script_path'];
    $ywa_options['domain_list']          		 = $_POST['domain_list'];
    $ywa_options['img_script_path']       		 = $_POST['img_script_path'];
    $ywa_options['ywa_cf_num_loggedin']   		 = $_POST['ywa_cf_num_loggedin'];
    $ywa_options['ywa_track_admins']   			 = $_POST['ywa_track_admins'];
    $ywa_options['ywa_action_comment']   		 = $_POST['ywa_action_comment'];

    update_option('ywa_admin_options', $ywa_options);

    _e('Options saved', 'ywa')
    ?></strong></p></div><?php
	} 
	// Admin Page Form

	?>
<div class=wrap>
  <form method="post">
    <h2>Yahoo Web Analytics</h2>
    <fieldset class="options" name="general">
      <legend><?php _e('General settings', 'ywa') ?></legend>
      <table width="100%" cellspacing="2" cellpadding="5" class="editform">
        <tr>
          <th nowrap valign="top" width="33%" align="left"><?php _e('Project ID', 'ywa') ?></th>
          <td><input name="account_id" type="text" id="account_id" value="<?php echo ywa_get_option('account_id'); ?>" size="50" />
            <br />Enter your Yahoo Analytics Project ID.
          </td>
        </tr>
        <tr>
          <th nowrap valign="top" width="33%" align="left"><?php _e('JS Script Path', 'ywa') ?></th>
          <td><input name="js_script_path" type="text" id="js_script_path" value="<?php echo ywa_get_option('js_script_path'); ?>" size="100" />
            <br />Enter your YWA Script Path (ie. http://d.yimg.com/mi/ywa.js ).
          </td>
        </tr>
        <tr>
          <th nowrap valign="top" width="33%" align="left"><?php _e('IMG Script Path', 'ywa') ?></th>
          <td><input name="img_script_path" type="text" id="img_script_path" value="<?php echo ywa_get_option('img_script_path'); ?>" size="100" />
            <br />Enter your YWA Image Path (ie. http://a.analytics.yahoo.com ).
          </td>
        </tr>
        <tr>
          <th nowrap valign="top" width="33%" align="left"><?php _e('Domain(s)', 'ywa') ?></th>
          <td><input name="domain_list" type="text" id="domain_list" value="<?php echo ywa_get_option('domain_list'); ?>" size="100" />
            <br />This variables helps you track sites with different domains/subdomains like www.rudishumpert.com,blog.rudishumpert.com.
            You can use * as a wildcard. *.rudishumpert.com will cover all variations.  This is key for exit link tracking.
          </td>
        </tr>
        <tr>
          <th nowrap valign="top" width="33%" align="left"><?php _e('Custom Field: Logged In', 'ywa') ?></th>
          <td><input name="ywa_cf_num_loggedin" type="text" id="ywa_cf_num_loggedin" value="<?php echo ywa_get_option('ywa_cf_num_loggedin'); ?>" size="5" />
            <br />Enter your YWA Custom Field Number to track if users are logged in.
          </td>
        </tr>
         <tr>
          <th nowrap valign="top" width="33%" align="left"><?php _e('Action|Comment Added: Logged In', 'ywa') ?></th>
          <td><input name="ywa_action_comment" type="text" id="ywa_action_comment" value="<?php echo ywa_get_option('ywa_action_comment'); ?>" size="5" />
            <br />Enter your YWA Action Number To Track Comments (ie. 01, 02, 03 etc//)
          </td>
        </tr>
        <tr>
          <th nowrap valign="top" width="33%" align="left"><?php _e('Track Admins', 'ywa') ?></th>
          <td><input name="ywa_track_admins" type="checkbox" id="ywa_track_admins" value="true" <?php if (ywa_get_option('ywa_track_admins')) echo "checked"; ?>  />
            <br />Check to enable tracking of blog administrators.
          </td>
        </tr>
      </table>
    </fieldset>
  
    <div class="submit">
      <input type="submit" name="info_update" value="<?php _e('Update options', 'ywa') ?>" />
	  </div>
  </form>
</div><?php
 
}

function ywa_insert_html_once($location, $html) {
  global $ywa_footer_hooked;
  global $ywa_html_inserted;
    $ywa_footer_hooked = true;
    if (!$ywa_html_inserted) {
      echo $html;
      }
}

function ywa_insert_js($location, $html) {  
      echo $html;
}

function ywa_get_tracker() {
  
  $result='<!-- user not tracked-->';

  if(is_home()) { 
	  $pageName = $category = $pageType = 'Blog Home';
  } elseif (is_page()) {
      $pageName = $category = the_title('', '', false);
      $pageType = 'Static Page';
  } elseif (is_single()) { 
      $categories = get_the_category();
      $pageName =  the_title('', '', false);
              $category = $categories[0]->name;
              $pageType = 'Article';
  } elseif (is_category()) {
     $pageName = $category = single_cat_title('', false);
     $pageName = 'Category: ' . $pageName;
	 $pageType = 'Category';
  } elseif (is_tag()) { 
 	 $pageName = $category = single_tag_title('', false);
  	 $pageType = 'Tag';
  } elseif (is_month()) { 
     list($month, $year) = split(' ', the_date('F Y', '', '', false));
     $pageName = 'Month Archive: ' . $month . ' ' . $year;
     $category = $pageType = 'Month Archive';
  } elseif (is_404()) {
  	$pageName = '404:'.$_SERVER["REQUEST_URI"];
  	$category = '404';
  }
  
  global $internal_search_value;
  $internal_search_value  =  $_GET["s"];
  if ( $internal_search_value == '' )
  	 {
  	 	$internal_search = '';
  	 	$internal_search_count = ''; 
  	 }
  else
   { 
   	  global $wp_query;
	  $ywa_count_total .= $wp_query->found_posts;
	  $internal_search_count = 'YWATracker.setISR("'.$ywa_count_total.'");';
   	  $internal_search = 'YWATracker.setISK("'.$internal_search_value.'");YWATracker.setAction("INTERNAL_SEARCH");';
   	  $pageName = 'Internal Search'; 
   	  $category = 'Internal Search';        		
   }
  
  if ( is_user_logged_in() ) {
      $loggedin = 'Yes';
  } else {
      $loggedin = 'No';
  }; 
      // tracking code to be added to page
  global $commentadded;
 
 if(isset($_SESSION['ywacommentflag']) && $_SESSION['ywacommentflag'] == 1) {
    $ywa_comment = 'YWATracker.setAction("'.ywa_get_option('ywa_action_comment').'");';  
    $_SESSION['ywacommentflag'] = 0 ;
 } else {
    $ywa_comment = ''; 
 }
 

  
  if (!ywa_get_option('ywa_track_admins') && (current_user_can('manage_options') )) {
       $result='<!-- user not tracked by Yahoo Analytics plugin v'.ywa_version.': http://www.rudishumpert.com/projects/-->';
    } else {
    	 	$result='
			<!-- tracker added by Yahoo Analytics plugin v'.ywa_version.': http://www.rudishumpert.com/projects/ -->
			
			<!-- Yahoo! Web Analytics - All rights reserved -->
			<script type="text/javascript" src="'.ywa_get_option('js_script_path').'"></script>
			<script type="text/javascript">
			var YWATracker = YWA.getTracker("'.ywa_get_option('account_id').'");
			YWATracker.setDocumentName("'.$pageName.'");
			YWATracker.setDocumentGroup("'.$category.'");
			YWATracker.setDomains("'.ywa_get_option('domain_list').'");
			YWATracker.setCF('.ywa_get_option('ywa_cf_num_loggedin').',"'.$loggedin.'");
			'.$internal_search.'
			'.$internal_search_count.'
			'.$ywa_comment.'
			YWATracker.submit();
			</script>
			
			<noscript>
			<div><img src="'.ywa_get_option('img_script_path').'/p.pl?a='.ywa_get_option('account_id').'&amp;js=no" width="1" height="1" alt="" /></div>
			</noscript>
			
			';
    }

  return $result;
}

function ywa_track_comment() {
	session_start();
    $_SESSION['ywacommentflag'] = 1;
}

function ywa_wp_track_comment($YWACommentSluggo)
 {
  ywa_insert_js('footer', ywa_track_comment());
  return $YWACommentSluggo;
}

function ywa_wp_footer_track($YWASluggo) {
  ywa_insert_html_once('footer', ywa_get_tracker());
  return $YWASluggo;
}
// **************
// initialization
global $ywa_footer_hooked;
$ywa_footer_hooked=false;
add_action('admin_menu', 'ywa_admin');
add_action('wp_footer', 'ywa_wp_footer_track');
add_action('comment_post', 'ywa_track_comment');
?>