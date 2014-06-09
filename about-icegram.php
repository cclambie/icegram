<?php
/*
 * About Icegram
 */

// Actions for License Validation & Upgrade process
add_action( 'admin_footer', 'icegram_support_ticket_content' );

function icegram_support_ticket_content() {
    global $current_user, $pagenow, $typenow, $icegram_upgrader;

    if ( $pagenow != 'edit.php' ) return;

    if ( $typenow != 'campaign') return;

    if ( !( $current_user instanceof WP_User ) ) return;

    if( isset( $_POST['submit_query'] ) && $_POST['submit_query'] == "Send" ){


        $additional_info = ( isset( $_POST['additional_information'] ) && !empty( $_POST['additional_information'] ) ) ? sanitize_text_field( $_POST['additional_information'] ) : '';
        $additional_info = str_replace( '###', '<br />', $additional_info );
        $additional_info = str_replace( array( '[', ']' ), '', $additional_info );

        $headers = 'From: ';
        $headers .= ( isset( $_POST['client_name'] ) && !empty( $_POST['client_name'] ) ) ? sanitize_text_field( $_POST['client_name'] ) : '';
        $headers .= ' <' . sanitize_text_field( $_POST['client_email'] ) . '>' . "\r\n";
        $headers .= 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

        ob_start();
        echo $additional_info . '<br /><br />';
        echo nl2br($_POST['message']) ;
        $message = ob_get_clean();
        wp_mail( 'hello@icegram.com', $_POST['subject'], $message, $headers ); 
        header('Location: ' . $_SERVER['HTTP_REFERER'] );

    }
    ?>
    <div id="icegram_post_query_form" style="display: none;">
        <?php

            if ( !wp_script_is('jquery') ) {
                wp_enqueue_script('jquery');
                wp_enqueue_style('jquery');
            }

            $first_name = get_user_meta($current_user->ID, 'first_name', true);
            $last_name = get_user_meta($current_user->ID, 'last_name', true);
            $name = $first_name . ' ' . $last_name;
            $customer_name = ( !empty( $name ) ) ? $name : $current_user->data->display_name;
            $customer_email = $current_user->data->user_email;
            $license_key = $icegram_upgrader->license_key;
            $wp_version = ( is_multisite() ) ? 'WPMU ' . get_bloginfo('version') : 'WP ' . get_bloginfo('version');
            $admin_url = admin_url();
            $php_version = ( function_exists( 'phpversion' ) ) ? phpversion() : '';
            $wp_max_upload_size = size_format( wp_max_upload_size() );
            $server_max_upload_size = ini_get('upload_max_filesize');
            $server_post_max_size = ini_get('post_max_size');
            $wp_memory_limit = WP_MEMORY_LIMIT;
            $wp_debug = ( defined( 'WP_DEBUG' ) && WP_DEBUG === true ) ? 'On' : 'Off';
            $this_plugins_version = $icegram_upgrader->plugin_data['Name'] . ' ' . $icegram_upgrader->plugin_data['Version'];
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $additional_information = "Additional Information =>
                                       (WP Version: $wp_version) ###
                                       (Admin URL: $admin_url) ###
                                       (PHP Version: $php_version) ###
                                       (WP Max Upload Size: $wp_max_upload_size) ###
                                       (Server Max Upload Size: $server_max_upload_size) ###
                                       (Server Post Max Size: $server_post_max_size) ###
                                       (WP Memory Limit: $wp_memory_limit) ###
                                       (WP Debug: $wp_debug) ###
                                       (" . $icegram_upgrader->plugin_data['Name'] . " Version: $this_plugins_version) ###
                                       (License Key: $license_key)###
                                       (IP Address: $ip_address)
                                      ";

        ?>
        <form id="icegram_form_post_query" method="POST" action="" enctype="multipart/form-data">
            <script type="text/javascript">
                jQuery(function(){
                    jQuery('input#icegram_submit_query').click(function(e){
                        var error = false;

                        var client_name = jQuery('input#client_name').val();
                        if ( client_name == '' ) {
                            jQuery('input#client_name').css('border-color', 'red');
                            error = true;
                        } else {
                            jQuery('input#client_name').css('border-color', '');
                        }

                        var client_email = jQuery('input#client_email').val();
                        if ( client_email == '' ) {
                            jQuery('input#client_email').css('border-color', 'red');
                            error = true;
                        } else {
                            jQuery('input#client_email').css('border-color', '');
                        }

                        var subject = jQuery('table#icegram_post_query_table input#subject').val();
                        if ( subject == '' ) {
                            jQuery('input#subject').css('border-color', 'red');
                            error = true;
                        } else {
                            jQuery('input#subject').css('border-color', '');
                        }

                        var message = jQuery('table#icegram_post_query_table textarea#message').val();
                        if ( message == '' ) {
                            jQuery('textarea#message').css('border-color', 'red');
                            error = true;
                        } else {
                            jQuery('textarea#message').css('border-color', '');
                        }

                        if ( error == true ) {
                            jQuery('label#error_message').text('* All fields are compulsory.');
                            e.preventDefault();
                        } else {
                            jQuery('label#error_message').text('');
                        }

                    });

                    jQuery(".icegram-contact-us a.thickbox").click( function(){ 
                        setTimeout(function() {
                            jQuery('#TB_ajaxWindowTitle').text('Send your query');
                        }, 0 );
                    });

                    jQuery('div#TB_ajaxWindowTitle').each(function(){
                       var window_title = jQuery(this).text(); 
                       if ( window_title.indexOf('Send your query') != -1 ) {
                           jQuery(this).remove();
                       }
                    });

                    jQuery('input,textarea').keyup(function(){
                        var value = jQuery(this).val();
                        if ( value.length > 0 ) {
                            jQuery(this).css('border-color', '');
                            jQuery('label#error_message').text('');
                        }
                    });

                });
            </script>
            <table id="icegram_post_query_table">
                <tr>
                    <td><label for="client_name"><?php _e('Name', 'translate_icegram'); ?>*</label></td>
                    <td><input type="text" class="regular-text sm_text_field" id="client_name" name="client_name" value="<?php echo $customer_name; ?>" /></td>
                </tr>
                <tr>
                    <td><label for="client_email"><?php _e('E-mail', 'translate_icegram'); ?>*</label></td>
                    <td><input type="email" class="regular-text sm_text_field" id="client_email" name="client_email" value="<?php echo $customer_email; ?>" /></td>
                </tr>
                <!--
                <tr>
                    <td><label for="current_plugin"><?php _e('Product', 'translate_icegram'); ?></label></td>
                    <td><input type="text" class="regular-text sm_text_field" id="current_plugin" name="current_plugin" value="<?php echo $this_plugins_version; ?>" readonly /></td>
                </tr>
                -->
                <input type="hidden" id="current_plugin" name="current_plugin" value="<?php echo $this_plugins_version; ?>" />
                <tr>
                    <td><label for="subject"><?php _e('Subject', 'translate_icegram'); ?>*</label></td>
                    <td><input type="text" class="regular-text sm_text_field" id="subject" name="subject" value="<?php echo ( !empty( $subject ) ) ? $subject : ''; ?>" /></td>
                </tr>
                <tr>
                    <td style="vertical-align: top; padding-top: 12px;"><label for="message"><?php _e('Message', 'translate_icegram'); ?>*</label></td>
                    <td><textarea id="message" name="message" rows="10" cols="60"><?php echo ( !empty( $message ) ) ? $message : ''; ?></textarea></td>
                </tr>
                <tr>
                    <td></td>
                    <td><label id="error_message" style="color: red;"></label></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" class="button" id="icegram_submit_query" name="submit_query" value="Send" /></td>
                </tr>
            </table>
            <input type="hidden" name="license_key" value="<?php echo $license_key; ?>" />
            <input type="hidden" name="sku" value="<?php echo $icegram_upgrader->sku; ?>" />
            <input type="hidden" class="hidden_field" name="wp_version" value="<?php echo $wp_version; ?>" />
            <input type="hidden" class="hidden_field" name="admin_url" value="<?php echo $admin_url; ?>" />
            <input type="hidden" class="hidden_field" name="php_version" value="<?php echo $php_version; ?>" />
            <input type="hidden" class="hidden_field" name="wp_max_upload_size" value="<?php echo $wp_max_upload_size; ?>" />
            <input type="hidden" class="hidden_field" name="server_max_upload_size" value="<?php echo $server_max_upload_size; ?>" />
            <input type="hidden" class="hidden_field" name="server_post_max_size" value="<?php echo $server_post_max_size; ?>" />
            <input type="hidden" class="hidden_field" name="wp_memory_limit" value="<?php echo $wp_memory_limit; ?>" />
            <input type="hidden" class="hidden_field" name="wp_debug" value="<?php echo $wp_debug; ?>" />
            <input type="hidden" class="hidden_field" name="current_plugin" value="<?php echo $this_plugins_version; ?>" />
            <input type="hidden" class="hidden_field" name="ip_address" value="<?php echo $ip_address; ?>" />
            <input type="hidden" class="hidden_field" name="additional_information" value='<?php echo $additional_information; ?>' />
        </form>
    </div>
    <?php
}

if ( !wp_script_is( 'thickbox' ) ) {
    if ( !function_exists( 'add_thickbox' ) ) {
        require_once ABSPATH . 'wp-includes/general-template.php';
    }
    add_thickbox();
} 

?>
<div class="wrap about-wrap icegram">
             
            <h1><?php _e( "Welcome to Icegram", "translate_icegram" ); ?></h1>
            <div class="about-text icegram-about-text">
                <?php _e( "Your sample campaign is ready. We've added a few messages for you to test.", "translate_icegram" )?>
                <?php 
                    $sample_id = get_option('icegram_sample_data_imported');
                    $view_campaign = admin_url( 'post.php?post='.$sample_id[0].'&action=edit' );
                    $preview_url = home_url('?campaign_preview_id='.$sample_id[0]);
                    $assets_base = $icegram->plugin_url . '/assets/images/';
                ?>
                <p class="icegram-actions">
                    <a class="button button-primary button-large" href="<?php echo $view_campaign ; ?>"><?php _e( 'Edit & Publish it', 'translate_icegram' ); ?></a>
                    <?php _e( "OR", "translate_icegram")?>
                    <b><a href="<?php echo $preview_url; ?>" target="_blank"><?php _e( 'Preview Campaign', 'translate_icegram' ); ?></a></b>
                </p>
               
            </div>
            
            <div class="icegram-badge">
               <?php printf(__( "Version: %s", "translate_icegram"), $icegram_upgrader->plugin_data['Version'] ); ?>
            </div>
            <div class="icegram-support">
                    <?php _e( 'Questions? Need Help?', "translate_icegram" ); ?>
                    <div id="icegram-contact-us" class="icegram-contact-us"><a  class="thickbox"  href="<?php echo admin_url() . "#TB_inline?inlineId=icegram_post_query_form&post_type=icegram" ?>"><?php _e("Contact Us", "translate_icegram"); ?></a></div>
            </div>
            <hr>
            <div class="changelog">

                <div class="about-text">
                <?php _e("Do read Icegram's core concepts below to understand how you can use Icegram to inspire, convert and engage your audience.", "translate_icegram"); ?>
                </div>

                <div class="feature-section col three-col">
                        <div class="col-1">
                                
                                <h2 class="icegram-dashicons dashicons-testimonial"><?php _e( "Messages", "translate_icegram" ); ?></h2>
                                <!--<img src="//s.w.org/images/core/3.9/editor.jpg?0">-->
                                <p><?php _e("A 'Message' is a communication you want to deliver to your audience.","translate_icegram"); ?></p>
                                <p><?php _e("And Icegram comes with not one, but four message types.","translate_icegram"); ?></p>
                                <p><?php _e("Different message types look and behave differently, but they all have many common characteristics. For instance, most message types will allow you to set a headline, a body text, label for the ‘call to action’ button, a link for that button, theme and styling options, animation effect and position on screen where that message should show.","translate_icegram"); ?></p>
                        </div>
                        <div class="col-2">
                                <h4><?php _e("Action Bar", "translate_icegram"); ?></h4>
                                <img src="<?php echo $assets_base; ?>/sketch-action-bar.png" width="180" height="145">
                                <p><?php _e("An action bar is a proven attention grabber. It shows up as a solid bar either at top or bottom. Use it for your most important messages or time sensitive announcements. Put longer content in it and it acts like a collapsible panel!", "translate_icegram"); ?></p>
                                <h4><?php _e("Messenger", "translate_icegram"); ?></h4>
                                <img src="<?php echo $assets_base; ?>/sketch-messenger.png" width="180" height="145">
                                <p><?php _e("A messenger is best used to invoke interest while your visitor is reading your content. Users perceive it as something new, important and urgent and are highly likely to click on it.", "translate_icegram"); ?></p>
                        </div>
                        <div class="col-3 last-feature">
                                <h4><?php _e("Toast Notification", "translate_icegram"); ?></h4>
                                <img src="<?php echo $assets_base; ?>/sketch-toast-notification.png" width="180" height="145">
                                <p><?php _e("Want to alert your visitor about some news, an update from your blog, a social proof or an offer? Use Icegram’s unique toast notification, it will catch their attention, let them click on the message, and disappear after a while.", "translate_icegram"); ?></p>
                               <h4><?php _e("Popup", "translate_icegram"); ?></h4>
                                <img src="<?php echo $assets_base; ?>/sketch-popup.png" width="180" height="145">
                                <p><?php _e("Lightbox popup windows are most widely used for lead capture, promotions and additional content display. Ask visitors to sign up to your newsletter, or like you on social networks, or tell them about a special offer...", "translate_icegram"); ?></p>
                        
                        </div>
                </div>
                
                <hr>
                
                <div class="feature-section col three-col">
                        <div class="col-1">                                
                                <h2 class="icegram-dashicons dashicons-megaphone"><?php _e("Campaigns", "translate_icegram"); ?></h2>
                                <p><?php _e("Campaign = Messages + Rules", "translate_icegram"); ?></p>
                                <p><?php _e("A campaign allows sequencing multiple messages and defining targeting rules. Create different campaigns for different marketing goals. Icegram supports showing multiple campaigns on any page.", "translate_icegram"); ?></p>
								<p><?php _e("You can always preview your campaign to ensure campaign works the way you want, before making it live.", "translate_icegram"); ?></p>
                        </div>
                        <div class="col-2">
                                <h4><?php _e("Multiple Messages & Sequencing", "translate_icegram"); ?></h4>
                                <img src="<?php echo $assets_base; ?>/sketch-multiple-sequence.png" width="180" height="145">
                                <p><?php _e("Add one or as many messages to a campaign as you want. Also choose the number of seconds after which each message should show up. Showing multiple messages for same goal, but with slightly different content / presentation, greatly improves conversions.", "translate_icegram"); ?></p>
                        </div>
                        <div class="col-3 last-feature">                                
                                <h4><?php _e("Targeting Rules", "translate_icegram"); ?></h4>
                                <img src="<?php echo $assets_base; ?>/sketch-rules.png" width="180" height="145">
                                <p><?php _e("You can control who sees a campaign – and on what device, which pages does it show on, and what time period will it stay active for. You can run different campaigns with different rules to maximize engagement.", "translate_icegram"); ?></p>
                        </div>
                </div>

                <hr>
                
                <div class="feature-section col two-col">
                        <div class="col-1">
                            <h2 class="icegram-dashicons dashicons-editor-help"><?php _e("FAQ / Common Problems", "translate_icegram"); ?></h2>

                                <h4><?php _e("Messages look broken / formatting is weird...", "translate_icegram"); ?></h4>
                                <p><?php _e("This is most likely due to CSS conflicts with current theme. We suggest using simple formatting for messages. You can also write custom CSS in your theme to fix any problems.", "translate_icegram"); ?></p>

                                <h4><?php _e("Extra Line Breaks / Paragraphs in messages...", "translate_icegram"); ?></h4>
                                <p><?php _e("Go to HTML mode in content editor and pull your custom HTML code all together in one line. Don't leave blank lines between two tags. That should fix it.", "translate_icegram"); ?></p>

                                <h4><?php _e("How do I add custom CSS for messages?", "translate_icegram"); ?></h4>
                                <p><?php _e("You can use custom CSS/JS inline in your message HTML. You can also use your theme's custom JS / CSS feature to add your changes.", "translate_icegram"); ?></p>

                                <h4><?php _e("Optin Forms / Mailing service integration...", "translate_icegram"); ?></h4>
                                <p><?php _e("You can embed any optin / subscription form to your Icegram messages using HTML code. You may even use a shortcode if you are using a WP plugin from your newsletter / lead capture service.", "translate_icegram"); ?></p>

                        </div>
                        <div class="col-2 last-feature">                                
                                <h4><?php _e("Preview does not work / not refreshing...", "translate_icegram"); ?></h4>
                                <p><?php _e("Doing a browser refresh while previewing will not show your most recent changes. Click 'Preview' button to see a preview with your latest changes.", "translate_icegram"); ?></p>

                                <h4><?php _e("Can I use shortcodes in a message?", "translate_icegram"); ?></h4>
                                <p><?php _e("Yes! Messages support shortcodes. You may need to adjust CSS so the shortcode output looks good in your message.", "translate_icegram"); ?></p>


                                <h4><?php _e("I can't find a way to do X...", "translate_icegram"); ?></h4>
                                <p><?php _e("Icegram is actively developed. If you can't find your favorite feature (or have a suggestion) contact us. We'd love to hear from you.", "translate_icegram"); ?></p>

                                <h4><?php _e("I'm facing a problem and can't find a way out...", "translate_icegram"); ?></h4>
                                <p><a class="thickbox"  href="<?php echo admin_url() . "#TB_inline?inlineId=icegram_post_query_form&post_type=icegram" ?>"><?php _e("Contact Us", "translate_icegram"); ?></a><?php _e(", provide as much detail of the problem as you can. We will try to solve the problem ASAP.", "translate_icegram"); ?></p>

                                <h4><?php _e("", "translate_icegram"); ?></h4>
                                <p><?php _e("", "translate_icegram"); ?></p>
                        </div>
                </div>


            </div>
            
</div>