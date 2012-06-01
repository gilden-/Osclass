<?php
    /**
     * OSClass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2010 OSCLASS
     *
     * This program is free software: you can redistribute it and/or modify it under the terms
     * of the GNU Affero General Public License as published by the Free Software Foundation,
     * either version 3 of the License, or (at your option) any later version.
     *
     * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
     * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
     * See the GNU Affero General Public License for more details.
     *
     * You should have received a copy of the GNU Affero General Public
     * License along with this program. If not, see <http://www.gnu.org/licenses/>.
     */

    $dateFormats = array('F j, Y', 'Y/m/d', 'm/d/Y', 'd/m/Y') ;
    $timeFormats = array('g:i a', 'g:i A', 'H:i') ;

    $aLanguages  = __get('aLanguages') ;
    $aCurrencies = __get('aCurrencies') ;

    //customize Head
    function customHead(){
        echo '<script type="text/javascript" src="'.osc_current_admin_theme_js_url('jquery.validate.min.js').'"></script>';
        ?>
<script type="text/javascript">
$(document).ready(function(){
    // Code for form validation
    $("form[name=comments_form]").validate({
        rules: {
            num_moderate_comments: {
                required: true,
                digits: true
            },
            comments_per_page: {
                required: true,
                digits: true
            }
        },
        messages: {
            num_moderate_comments: {
                required: "<?php _e("Moderated comments: this field is required"); ?>.",
                digits: "<?php _e("Moderated comments: this field has to be numeric only"); ?>."
            },
            comments_per_page: {
                required: "<?php _e("Comments per page: this field is required"); ?>.",
                digits: "<?php _e("Comments per page: this field has to be numeric only"); ?>."
            }
        },
        wrapper: "li",
        errorLabelContainer: "#error_list",
        invalidHandler: function(form, validator) {
            $('html,body').animate({ scrollTop: $('h1').offset().top }, { duration: 250, easing: 'swing'});
        }
    });

    if( !$('input[name="moderate_comments"]').is(':checked') ) {
        $('.comments_approved').css('display', 'none') ;
    }

    $('input[name="moderate_comments"]').bind('change', function() {
        if( $(this).is(':checked') ) {
            $('.comments_approved').css('display', '') ;
        } else {
            $('.comments_approved').css('display', 'none') ;
        }
    }) ;
}) ;
</script>
        <?php
    }
    osc_add_hook('admin_header','customHead');

    function render_offset(){
        return 'row-offset';
    }
    osc_add_hook('admin_page_header','customPageHeader');
    function customPageHeader(){ ?>
        <h1><?php _e('Comments Settings') ; ?></h1>
    <?php
    }
?>
<?php osc_current_admin_theme_path( 'parts/header.php' ) ; ?>
<div id="general-setting">
    <!-- settings form -->
                    <div id="general-settings">
                        <h2 class="render-title"><?php _e('Comments Settings') ; ?></h2>
                            <ul id="error_list" style="display: none;"></ul>
                            <form name="comments_form" action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
                                <input type="hidden" name="page" value="settings" />
                                <input type="hidden" name="action" value="comments_post" />
                                <fieldset>
                                    <div class="form-horizontal">
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('Default comment settings') ; ?></div>
                                        <div class="form-controls">
                                            <div class="form-label-checkbox"><input type="checkbox" <?php echo ( osc_comments_enabled() ? 'checked="checked"' : '' ) ; ?> name="enabled_comments" value="1" /><?php _e('Allow people to post comments on listings') ; ?></div>
                                        <div class="form-label-checkbox"><input type="checkbox" <?php echo ( osc_reg_user_post_comments() ? 'checked="checked"' : '' ) ; ?> name="reg_user_post_comments" value="1" />
                                            <?php _e('Users must be registered and logged in to comment') ; ?></div>
                                        <div class="form-label-checkbox"><input type="checkbox" <?php echo ( ( osc_moderate_comments() == -1 ) ? '' : 'checked="checked"' ) ; ?> name="moderate_comments" value="1" />
                                            <?php _e('A comment is held for moderation') ; ?></div>
                                        <div class="form-label-checkbox-offset">
                                            <?php printf( __('Before a comment appears, comment author must have %s previously approved comment'), '<input type="text" class="input-small" name="num_moderate_comments" value="' . ( (osc_moderate_comments() == -1 ) ? '' : osc_esc_html( osc_moderate_comments() ) ) . '" />' ) ; ?>
                                            <div class="help-box"><?php _e('If the value is zero an administrator must always approve the comment') ; ?></div>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('Other comment settings') ; ?></div>
                                        <div class="form-controls">
                                                <?php printf( __('Break comments into pages with %s comments per page'), '<input type="text" class="input-small" name="comments_per_page" value="' . osc_esc_html( osc_comments_per_page() ) . '" />' ) ; ?>
                                                <div class="help-box"><?php _e('If the value is zero all the comments are shown' ) ; ?></div>
                                        </div>
                                    </div>
                                    <h2 class="render-title"><?php _e('Notifications') ; ?></h2>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('E-mail admin whenever') ?></div>
                                        <div class="form-controls">
                                            <div class="form-label-checkbox">
                                                <input type="checkbox" <?php echo ( osc_notify_new_comment() ? 'checked="checked"' : '' ) ; ?> name="notify_new_comment" value="1" />
                                                <?php _e("There is a new comment") ; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label"><?php _e('E-mail user whenever') ?></div>
                                        <div class="form-controls">
                                            <div class="form-label-checkbox">
                                                <input type="checkbox" <?php echo ( osc_notify_new_comment_user() ? 'checked="checked"' : '' ) ; ?> name="notify_new_comment_user" value="1" />
                                                <?php _e("There is a new comment in his listing") ; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-actions">
                                        <input type="submit" value="<?php echo osc_esc_html( __('Save changes') ) ; ?>" class="btn btn-submit" />
                                    </div>
                                </div>
                        </fieldset>
                    </form>
                </div>
                <!-- /settings form -->
</div>
<?php osc_current_admin_theme_path( 'parts/footer.php' ) ; ?>                