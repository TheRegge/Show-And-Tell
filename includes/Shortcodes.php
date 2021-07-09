<?php
namespace showAndTell\includes;

/**
 * Class to register custom shortcodes
 *
 * @since       1.0.0
 *
 * @package     Show_And_Tell
 * @subpackage  Show_And_Tell/includes
 */

class Shortcodes
{
    /**
     * Is login required for this form?
     *
     * @var boolean
     */
    private $_login_required = true;

    /**
     * Shortcode to display a grid of all answers
     * from one or multiple forms.
     *
     * @since 1.0.0
     * @access public
     *
     * @param array $atts   All arguments are recieved as strings. Shortcode Accepts:
     *                      * posts_per_page: the number of posts to display in
     *                                              one page. The grid is paginated.
     *                      * form_id:        One or multiple comma-separated form IDs to
     *                                        query the answers from.
     * @return string       The HTML markup of all the queried answers in a grid layout.
     */
    public function shortcode_show_posts_grid($atts=[])
    {
        global $wp_query;

        extract(shortcode_atts([
             'form_id'        => false,
             'posts_per_page' =>   9,
         ], $atts));

        $output = '';

        $form_id = array_map('trim', explode(',', $form_id));

        if (!$form_id) {
            return 'form id required';
        }

        if (null === $wp_query) {
            $paged = 1;
        } else {
            $paged =  isset($wp_query->query['paged']) ? $wp_query->query['paged'] : 1;
        }

        $args = array(
            'posts_per_page' => (int)$posts_per_page,
            'paged'          => $paged,
            'meta_key'       => 'show-and-tell-post',
            'meta_value'     => $form_id,
            'compare'        => 'IN'
        );

        if (isset($_GET['sat-tag'])) {
            if (isset($_GET['sat-filter-tags-nonce']) && wp_verify_nonce($_GET['sat-filter-tags-nonce'], 'ds_filter_tags')) {
                $args['tag'] = strtolower(sanitize_title_with_dashes($_GET['sat-tag']));
            }
        }

        $posts_query = new \WP_Query($args);
        $total_pages = $posts_query->max_num_pages;

        $output .= '<div class="sat-grid">';

        while ($posts_query->have_posts()) {
            $posts_query->the_post();
            global $post;
            $post_author = get_user_by('ID', (int)$post->post_author);
            $link        = get_the_permalink();
            $title       = get_the_title();
            $featured    = get_the_post_thumbnail(null, 'featured-image');
            $excerpt     = get_the_excerpt();
            $author      = $post_author->first_name . ' ' . $post_author->last_name;

            $output .= <<<EOT
<div class="sat-grid_card">
    <a href="$link">
        <div class="sat-grid_image">
        $featured
        </div>
        <div class="sat-grid_content">
            <h3 class="title">$title</h3>
            <span class="text-muted text-small">by $author </span>
            <p class="sat-excerpt">$excerpt</p>
        </div>
    </a>
</div>
EOT;
        }

        $output .= '</div>' . PHP_EOL;
        $output .= $this->_pagination($paged, $total_pages);

        wp_reset_postdata();

        return $output;
    }

    /**
     * Helper method to create the pagination markup.
     *
     * @since 1.0.0
     * @access private
     *
     * @param string $paged     The page number to display.
     * @param string $max_page  An insanely large maximum nubmer of pages.
     * @return string           The pagination HTML markup.
     */
    private function _pagination($paged = '', $max_page = '')
    {
        $big = 999999999; // need an unlikely integer
        if (! $paged) {
            $paged = get_query_var('paged');
        }


        return paginate_links(array(
            'base'       => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format'     => '?paged=%#%',
            'current'    => max(1, $paged),
            'total'      => $max_page,
            'mid_size'   => 1,
            'prev_text'  => __('«'),
            'next_text'  => __('»'),
            'type'       => 'list'
        ));
    }

    /**
     * Required shortcode. Output markup for the beguinning of the form. All forms
     * must start with this shortcode.
     *
     * @since 1.0.0
     * @access public
     *
     * @param array $atts Array of shortdode attributes. Only 'id' is optionaly used to allow
     * for a custom form ID.
     *
     * @return string|void
     */
    public function shortcode_start_form($atts=[])
    {
        global $post;
        $output = '';

        if (!Shortcodes::is_submitting()) {
            // Set default attributes
            $uid = uniqid('show-and-tell-form_');
            extract(shortcode_atts([
             'id' => $uid,
             'login' => true,
             'login_message' => __('Login required.', 'show-and-tell'),
         ], $atts));

            $this->_login_required = $login;

            if ($this->_user_can_view()) {
                $current_blog_id = get_current_blog_id();
                $current_user    = wp_get_current_user();
                $action_url      = esc_url($_SERVER['REQUEST_URI']);

                $post_id = get_post_field('ID');
                $current_user_id = $current_user->ID;
                $status = Utils::get_user_submission_status($current_user_id, $post_id);

                if ($status === SAT_SUBMISSION_STATUS_PUBLISHED) {
                    $msg     = __('Your form data was already published. Please contact an administrator to make edits.', 'show-and-tell');
                    $type    = SAT_ADMIN_NOTICE_WARNING;
                    $output .= Utils::make_admin_notice($msg, $type, false);
                }

                $saved_data = $this->_get_saved_data();

                // Check for backward compatibility, before status
                // was systematically added. Remove for future
                // versions of plugin and just use status from
                // saved_data.
                if (!isset($saved_data['status'])) {
                    $status = __(SAT_SUBMISSION_STATUS_SUBMITTED, 'show-and-tell');
                } else {
                    $status = $saved_data ? $saved_data['status'] : __(SAT_SUBMISSION_STATUS_SUBMITTED, 'show-and-tell');
                }
                $output .= "<h3>Hi {$current_user->display_name},</h3>" . PHP_EOL;
                $output .= "<form action=\"{$action_url}\" id=\"{$id}\" method=\"POST\" enctype=\"multipart/form-data\" multiple class=\"show-and-tell-form {$uid}\">" . PHP_EOL;

                if (is_multisite()) {
                    $output .= "\t<input type=\"hidden\" name=\"wp-blog-id\" value=\"{$current_blog_id}\">" . PHP_EOL;
                }

                // Is there a published_id? (id of the published post)
                if (isset($saved_data['published_id'])) {
                    $published_id = sanitize_text_field($saved_data['published_id']);
                    $output .= "\t<input type=\"hidden\" name=\"published_id\" value=\"{$published_id}\">" . PHP_EOL;
                }

                $output .= "\t<input type=\"hidden\" name=\"wp-post-id\" value=\"{$post->ID}\">" . PHP_EOL;
                $output .= "\t<input type=\"hidden\" name=\"wp-user-id\" value=\"{$current_user->ID}\">" . PHP_EOL;
                $output .= "\t<input type=\"hidden\" name=\"status\" value=\"{$status}\">" . PHP_EOL;
            } else {
                $output .= "\t<div class=\"sat-form sat-form_message\">" . PHP_EOL;
                $output .= "\t\t<p><a href=\"" . wp_login_url() . "\">{$login_message}</a></p>". PHP_EOL;
                $output .= "\t</div>" . PHP_EOL;
            }
        }
        return $output;
    }

    /**
     * Required shortcode. All forms must end with this shortcode.
     * Outputs markup to close the form and enables submitting the form.
     * *
     * @since 1.0.0
     * @access public
     *
     * @return void
     */
    public function shortcode_end_form($atts=[])
    {
        extract(shortcode_atts([
            "btn_classes" => ''
         ], $atts));
        ob_start();
        if ($this->_get_saved_data()) {
            $this->_make_update_form();
        }

        if (Shortcodes::is_submitting()) {
            $this->submit_form();
        } else {
            $this->_make_submit_button($btn_classes);
        }
        return ob_get_clean();
    }

    /**
     * Shortcode to create an input of type 'text'.
     *
     * @since 1.0.0
     * @access public
     *
     * @param array $atts Accepts name, label and classes. Name and label default to a
     * unique ID. If no 'classes' passed, only the default `sat-form` and `sat-form_input` classes
     * will be used.
     *
     * @return string The input text markup.
     */
    public function shortcode_input_text($atts=[])
    {
        $output = '';
        if (!Shortcodes::is_submitting() && !$this->_get_saved_data() && $this->_user_can_view()) {
            // Set default attributes
            $uid = uniqid();
            $field_type = SAT_INPUT_TEXT;

            extract(shortcode_atts(array(
             'classes' => false,
             'info'    => '',
             'label'   => 'item '. $uid,
             'name'    => $field_type,
         ), $atts));

            if ($name !== $uid) {
                $name = $this->_sanitize_text_field($name);
            }

            $classes_name  = $this->_make_field_name($uid, $field_type, $name, 'sat-classes');
            $classes_value = sanitize_html_class($classes);
            $field_name    = $this->_make_field_name($uid, $field_type, $name);
            $field_value   = '';
            $info_name     = $this->_make_field_name($uid, $field_type, $name, 'sat-info');
            $info_value    = sanitize_text_field($info);
            $label_name    = $this->_make_field_name($uid, $field_type, $name, 'sat-label');
            $label_value   = sanitize_text_field($label);
            $disabled      = '';

            $classes_value = Utils::make_shortcode_classes_value($classes, array(
                'sat-form',
                'sat-form_input',
                'sat-form_inputext'
            ));

            include('templates/input_text.template.php');
        }
        return $output;
    }

    /**
     * Shortcode to create a textarea from field.
     *
     * @since 1.0.0
     * @access public
     *
     * @param array $atts
     * @return string The textarea html markup.
     */
    public function shortcode_input_textarea($atts=[])
    {
        $output = '';

        if (!Shortcodes::is_submitting() && !$this->_get_saved_data() && $this->_user_can_view()) {
            $uid = uniqid();
            $field_type = SAT_INPUT_TEXTAREA;

            extract(shortcode_atts(array(
                'classes' => false,
                'cols'    => 30,
                'info'    => false,
                'label'   => 'item' . $uid,
                'name'    => $field_type,
                'rows'    => 10,
            ), $atts));

            if ($name !== $uid) {
                $name = $this->_sanitize_text_field($name);
            }

            $classes_name  = $this->_make_field_name($uid, $field_type, $name, 'sat-classes');
            $classes_value = sanitize_html_class($classes);
            $cols_name     = $this->_make_field_name($uid, $field_type, $name, 'sat-cols');
            $cols_value    = sanitize_text_field($cols);
            $field_name    = $this->_make_field_name($uid, $field_type, $name);
            $field_value   = '';
            $info_name     = $this->_make_field_name($uid, $field_type, $name, 'sat-info');
            $info_value    = sanitize_text_field($info);
            $label_name    = $this->_make_field_name($uid, $field_type, $name, 'sat-label');
            $label_value   = sanitize_text_field($label);
            $rows_name     = $this->_make_field_name($uid, $field_type, $name, 'sat-rows');
            $rows_value    = sanitize_text_field($rows);

            $classes_value   = $classes_value ?  explode(',', $classes_value): [];
            $classes_value[] = 'sat-form';
            $classes_value[] = 'sat-form_input';
            $classes_value[] = 'sat-form_textarea';
            $classes_value   = implode(' ', $classes_value);

            include('templates/input_textarea.template.php');
        }
        return $output;
    }

    /**
     * Shortcode to create a simple 'info' text.
     *
     * @since 1.0.0
     * @access public
     *
     * @param array $atts
     * @param string $content  The text to display.
     * @return string          The html markup for this info text.
     */
    public function shortcode_output_text($atts=[], $content=null)
    {
        $output = '';
        if (!Shortcodes::is_submitting() && !$this->_get_saved_data() && $this->_user_can_view()) {
            extract(shortcode_atts(array(
                 'classes' => false,
             ), $atts));

            $classes   = $classes ? explode(',', sanitize_html_class($classes)): [];
            $classes[] = 'sat-form';
            $classes[] = 'sat-form_output';
            $classes[] = 'sat-form_text';
            $classes   = implode(' ', $classes);

            $output .= "\t<div class=\"sat-form_item\">" . PHP_EOL;
            $output .= "\t\t<p class=\"{$classes}\">" . sanitize_text_field($content) . "</p>" . PHP_EOL;
            $output .= "\t</div>" . PHP_EOL;
        }
        return $output;
    }

    /**
     * Shortcode to create an image uploader.
     *
     * @since 1.0.0
     * @access public
     *
     * @param array $atts
     * @return string The markup for the image uploader.
     */
    public function shortcode_input_uploadimage($atts=[])
    {
        $output = '';
        if (!Shortcodes::is_submitting() && !$this->_get_saved_data() && $this->_user_can_view()) {
            $uid = uniqid();
            $field_type = SAT_INPUT_UPLOADIMAGE;

            extract(shortcode_atts(array(
                 'classes' => false,
                 'info'    => '',
                 'label'   => $field_type . '_' . $uid,
                 'name'    => $field_type,
             ), $atts));

            if ($name !== $uid) {
                $name = $this->_sanitize_text_field($name);
            }

            $classes_name  = $this->_make_field_name($uid, $field_type, $name, 'sat-classes');
            $classes_value = sanitize_html_class($classes);
            $field_name    = $this->_make_field_name($uid, $field_type, $name);
            $field_value   = '';
            $info_name     = $this->_make_field_name($uid, $field_type, $name, 'sat-info');
            $info_value    = sanitize_text_field($info);
            $label_name    = $this->_make_field_name($uid, $field_type, $name, 'sat-label');
            $label_value   = sanitize_text_field($label);

            $status = '';

            $classes_value = Utils::make_shortcode_classes_value($classes, array(
                'sat-form',
                'sat-form_input',
                'sat-form_uploadimage'
            ));

            include('templates/input_uploadimage.template.php');
        }
        return $output;
    }

    /**
     * Shortcode to create an input form field for WordPress
     * Video embeds.
     *
     * @since 1.0.0
     * @access public
     *
     * @param array $atts   Shortcode params. Accepts:
     *                      - classes   CSS classes to add to the form element
     *                      - info      Descriptive instructional text for form user
     *                      - label     The form label
     *                      - name      A custom unique and descriptive identifyer (used in final templating)
     * @return string
     */
    public function shortcode_input_video($atts=[])
    {
        $output = '';
        if (!Shortcodes::is_submitting() && !$this->_get_saved_data() && $this->_user_can_view()) {
            $uid = uniqid();
            $field_type = SAT_INPUT_VIDEO;

            extract(shortcode_atts(array(
                  'classes' => false,
                  'info'    => false,
                  'label'   => 'item ' . $uid,
                  'name'    => $field_type,
             ), $atts));

            if ($name !== $uid) {
                $name = $this->_sanitize_text_field($name);
            }

            $classes_name  = $this->_make_field_name($uid, $field_type, $name, 'sat-classes');
            $classes_value = sanitize_html_class($classes);
            $field_name    = $this->_make_field_name($uid, $field_type, $name);
            $field_value   = '';
            $info_name     = $this->_make_field_name($uid, $field_type, $name, 'sat-info');
            $info_value    = sanitize_text_field($info);
            $label_name    = $this->_make_field_name($uid, $field_type, $name, 'sat-label');
            $label_value   = sanitize_text_field($label);
            $disabled      = '';

            $classes_value = Utils::make_shortcode_classes_value($classes, array(
                'sat-form',
                'sat-form_input',
                'sat-form_input_video'
            ));

            include('templates/input_video.template.php');
        }
        return $output;
    }

    /**
     * Shortcode to create an pulldown menu form field.
     *
     * @since 1.0.0
     * @access public
     *
     * @param array $atts   Shortcode params. Accepts:
     *                      - classes   CSS classes to add to the form element
     *                      - info      Descriptive instructional text for form user
     *                      - label     The form label
     *                      - name      A custom unique and descriptive identifyer (used in final templating)
     * @return string
     */
    public function shortcode_input_pulldown($atts=[], $content='')
    {
        $output = '';
        if (!Shortcodes::is_submitting() && !$this->_get_saved_data() && $this->_user_can_view()) {
            $uid = uniqid();
            $field_type = SAT_INPUT_PULLDOWN;

            extract(shortcode_atts(array(
                 'classes' => false,
                 'info'    => '',
                 'label'   => 'item ' . $uid,
                 'name'    => $field_type,
             ), $atts));

            if ($name !== $uid) {
                $name = $this->_sanitize_text_field($name);
            }

            $classes_name  = $this->_make_field_name($uid, $field_type, $name, 'sat-classes');
            $classes_value = sanitize_html_class($classes);
            $field_name    = $this->_make_field_name($uid, $field_type, $name);
            $field_value   = '';
            $info_name     = $this->_make_field_name($uid, $field_type, $name, 'sat-info');
            $info_value    = sanitize_text_field($info);
            $label_name    = $this->_make_field_name($uid, $field_type, $name, 'sat-label');
            $label_value   = sanitize_text_field($label);
            $options_name  = $this->_make_field_name($uid, $field_type, $name, 'sat-options');
            $options_value = preg_replace("/\r|\n/", "", sanitize_text_field($content));

            $disabled = '';

            $classes_value = Utils::make_shortcode_classes_value($classes, array(
                'sat-form',
                'sat-form_input',
                'sat-form_pulldown'
            ));

            $options_arr = explode(',', $options_value);

            include('templates/input_pulldown.template.php');
        }
        return $output;
    }


    /**
     * Shortcode to display an embeded video in the form.
     *
     * @since 1.0.0
     * @access public
     *
     * @param array $atts      The shortcode optional attributes
     * @param string $content  The url of the video to embed (Youtube or Vimeo)
     * @return string          The HTML markup embeding the video.
     */
    public function shortcode_output_video($atts=[], $content=null)
    {
        global $wp_embed;

        $output = '';
        if (!Shortcodes::is_submitting() && !$this->_get_saved_data() && $this->_user_can_view()) {
            extract(shortcode_atts(array(
                  'classes' => false,
             ), $atts));

            $classes   = $classes ? explode(',', sanitize_html_class($classes)) : [];
            $classes[] = 'sat-form';
            $classes[] = 'sat-form_output';
            $classes[] = 'sat-form_video';
            $classes   = implode(' ', $classes);

            $output .= "\t<div class=\"sat-form-item\">" . PHP_EOL;
            $output .= "\t\t<div class=\"{$classes}\">" . PHP_EOL;
            $output .= "\t\t\t" . $wp_embed->run_shortcode('[embed]' . esc_url_raw($content) . '[/embed]') . PHP_EOL;
            $output .= "\t\t</div>" . PHP_EOL;
            $output .= "\t</div>";
        }
        return $output;
    }

    /**
     * Shortcode to create a form to filter showcased posts
     *
     * Currently works only with 'Filter by tag'
     *
     * @since 1.0.0
     * @access public
     *
     * @param array $atts   Shortcode params. Accepts:
     *                      - classes: CSS classes to add to the form element
     *                      - current_filter_label: The label for the display of the current filter
     *                      - info: Descriptive instructional text for form user
     *                      - label: The form label
     *                      - tags: Currently a single filter (tag)
     * @return string
     */
    public function shortcode_show_filters($atts=[])
    {
        $output = '';

        extract(shortcode_atts(array(
            'classes'              => [],
            'current_filter_label' => __('Tags:', 'show-and-tell'),
            'info'                 => '',
            'label'                => '',
            'submit_label'         => __('Filter', 'show-and-tell'),
            'tags'                 => '',
        ), $atts));

        $classes_value      = Utils::make_shortcode_classes_value($classes, ['show-and-tell', 'sat-show_filters']);
        $action_url         = esc_url($_SERVER['REQUEST_URI']);
        $nonce_feild        = wp_nonce_field('ds_filter_tags', 'sat-filter-tags-nonce', false, false);
        $submit_label_value = sanitize_text_field($submit_label);
        $info_value         = sanitize_text_field($info);
        $label_value        = sanitize_text_field($label);

        $current_tag = '';
        if (isset($_GET['sat-tag']) && '' !== $_GET['sat-tag']) {
            if (isset($_GET['sat-filter-tags-nonce']) && wp_verify_nonce($_GET['sat-filter-tags-nonce'], 'ds_filter_tags')) {
                $current_tag .= '<div class="sat-show_filters">' . PHP_EOL;
                $current_tag .= sanitize_text_field($current_filter_label) . '&nbsp;';
                $current_tag .= '<strong>' . Utils::remove_slashes($_GET['sat-tag']) . '</strong>' . PHP_EOL;
                $current_tag .= '</div>' . PHP_EOL;
            }
        }

        $display_info = '';
        // if ('' !== $info_value) {
        $display_info .= '<div class="sat-show_info">' . PHP_EOL;
        $display_info .= "\t" . $info_value . PHP_EOL;
        $display_info .= '</div>' . PHP_EOL;
        // }

        $output .= <<<EOT
        $display_info
<div class="$classes_value">
    <form action="$action_url" method="GET" class="show-and-tell-form">
    <label for="tag">$label_value</label>
    <input type="text" name="sat-tag" class="sat-input">
    <input type="submit" class="sat-btn sat-btn_primary" value="$submit_label_value">
    $nonce_feild
    </form>
    </div>
    $current_tag
EOT;
        return $output;
    }


    /**
     * Creates the markup for the submit button
     *
     * @since 1.0.0
     * @access private
     *
     * @return void Outputs submit button html markup.
     */
    private function _make_submit_button($btn_classes='')
    {
        $post_id         = get_post_field('ID');
        $current_user_id = get_current_user_id();
        $status          = Utils::get_user_submission_status($current_user_id, $post_id);
        $disabled        = '';

        if ($status === SAT_SUBMISSION_STATUS_PUBLISHED) {
            $disabled = 'disabled';
        }

        if ($this->_user_can_view()) {
            $classes = '';
            if ($btn_classes !== '') {
                $classes = "class=\"{$btn_classes}\"";
            }
            echo "\t<div class=\"sat-form_item sat-form_submit\">" . PHP_EOL;
            echo "\t\t<input type=\"submit\" name=\"show-and-tell-submitted\" {$classes}";
            if ($this->_get_saved_data()) {
                echo  " value=\"Update\" ";
            }
            echo " $disabled";
            echo ">" . PHP_EOL;
            echo "\t</div>" . PHP_EOL;
            echo "</form>" . PHP_EOL;
            echo "\n";
        }
    }

    /**
     * Add new option to db or update existing option
     * for current post/current author combination.
     *
     * @since 1.0.0
     * @access public
     *
     * @return void
     */
    public function submit_form()
    {
        global $post;

        // WordPress Post ID:
        if (isset($_POST['wp-post-id'])) {
            $data['wp-post-id'] = absint($_POST['wp-post-id']);
            $post_id = $_POST['wp-post-id'];
        } else {
            $data['wp-post-id'] = $post->ID;
        }

        // WordPress Blog ID
        if (isset($_POST['wp-blog-id'])) {
            $data['wp-blog-id'] = absint($_POST['wp-blog-id']);
        }

        // Submission status
        if (isset($_POST['status'])) {
            $data['status'] = sanitize_text_field($_POST['status']);
        }

        // Published post id
        if (isset($_POST['published_id'])) {
            $data['published_id'] = sanitize_text_field($_POST['published_id']);
        }

        $data['shortcodes'] = array();

        foreach ($_POST as $key => $value) {
            $field_parts = $this->_get_sanitized_key_parts($key);

            if ($field_parts) {
                extract($field_parts); // $meta, $name, $type, $uid

                // Sanitize the user input
                $value = $this->_sanitize_user_input($value, $type, $meta);

                if (!isset($data['shortcodes'][$uid][$meta])) {
                    $data['shortcodes'][$uid][$meta] = array();
                }

                $shortcode = &$data['shortcodes'][$uid][$meta];
                $shortcode['meta']  = $meta;
                $shortcode['name']  = $name;
                $shortcode['type']  = $type;
                $shortcode['value'] = $value;

                // upload images
                if ($meta === 'sat-field' && $type === SAT_INPUT_UPLOADIMAGE) {
                    $shortcode['value'] = $this->_process_uploadimage($this->_make_field_name($uid, $type, $name), $value);
                }
            }
        }

        if ($this->_get_saved_data()) {
            $save_option = update_option($this->get_option_name($data), $data);
            $is_update = true;
        } else {
            $save_option = add_option($this->get_option_name($data), $data);
            $is_update = false;
        }

        if ($save_option) {
            $msg = __('Thank you for your submission.', 'show-and-tell');
            Utils::make_admin_notice($msg, SAT_ADMIN_NOTICE_SUCCESS);
        } elseif (!$is_update) {
            $msg = __('There was an error submitting the data, please reload the form and try again.', 'show-and-tell');
            echo Utils::make_admin_notice($msg, SAT_ADMIN_NOTICE_ERROR);
        } else {
            $msg = __('The data was not updated. Did you want to make any changes?', 'show-and-tell');
            echo Utils::make_admin_notice($msg, SAT_ADMIN_NOTICE_WARNING);
        }

        if (!isset($_POST['sat-is-admin'])) {
            echo '<p>' . __('Please reload the form and check your answers. If your embeded videos do not show, make sure you have the correct link.', 'show-and-tell') . '</p>' . PHP_EOL;
            echo '<p><a href="' . get_permalink($post_id) . '">' . __('Reload Form. ', 'show-and-tell') . '</a></p>' . PHP_EOL;
        }
        return;
    }

    /**
     * Uploads an image.
     *
     * Handles uploading functionality to WordPress core.
     *
     * @since 1.0.0
     * @access private
     *
     * @return string  The wordpress attachment id of the uploaded file.
     */
    private function _process_uploadimage($filesIndex, $savedValue)
    {
        require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

        if (isset($_FILES[$filesIndex]) && $_FILES[$filesIndex]['name'] !== '') {
            if (!$_FILES[$filesIndex]['error']) {
                // Do some file size validation here
                // if ($_FILES[$upload_field_name]['size'] > (300000)) {
                // wp_die('Your file size is too large.');
                //  }
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                require_once(ABSPATH . 'wp-admin/includes/media.php');

                // Let WordPress handle the upload
                $file_id = media_handle_upload($filesIndex, 0);

                if (is_wp_error($file_id)) {
                    wp_die(__('Error loading the file!', 'show-and-tell'));
                }
                return $file_id;
            } else {
                wp_die('Error: ' . $_FILES[$filesIndex]['error']);
            }
        } else {
            return $savedValue;
        }
    }

    /**
     * Creates form markup for all saved fields.
     *
     * @since 1.0.0
     * @access private
     *
     * @return void Outputs form fields markup.
     */
    private function _make_update_form()
    {
        if (($data = $this->_get_saved_data()) && $this->_user_can_view()) {
            if (! Shortcodes::is_submitting()) {
                foreach ($data['shortcodes'] as $key => $value) {
                    echo $this->_make_update_field($key, $value);
                }
            }
        }
    }

    /**
     * Utility method to create the update form in the admin.
     *
     * @since 1.0.0
     * @access public
     *
     * @param array $data   The saved option data form the answers of one form.
     * @return string       The HTML markup for the form
     */
    public function make_admin_update_form($data)
    {
        foreach ($data['shortcodes'] as $key => $value) {
            echo $this->_make_update_field($key, $value);
        }
    }

    /**
     * This method dynamically calls specific methods
     * in order to create the different form fields according
     * to the data previously saved as a WordPress option.
     *
     * @since 1.0.0
     * @access private
     *
     * @param string|any $uid      The unique ID for the shortcode. If param is not of type
     *                             string, the method returns it unchanged.
     * @param array $field         The saved values for the shortcode
     * @return string              The string returned by the dynamically called function.
     * @throws Exception           Throws an exception if calls a method that doesn't exist.
     */
    private function _make_update_field($uid, $field)
    {
        $f_name = '_make_update_'. str_replace('-', '_', $field['sat-field']['type']);
        try {
            return $this->{$f_name}($uid, $field);
        } catch (\Throwable $th) {
            throw new \Exception("Error Processing Request. No method with name {$f_name}", 1);
        }
    }

    /**
     * Creates a form text field loaded with saved value.
     *
     * @since 1.0.0
     *
     * @param string $uid      The unique ID for the shortcode
     * @param array $field     The saved values for the shortcode
     * @return string          The input text markup loaded with saved value.
     */
    private function _make_update_ds_input_text($uid, $field)
    {
        $output = '';

        $classes_name  = $this->_make_field_name($uid, $field['sat-classes']['type'], $field['sat-classes']['name'], 'sat-classes');
        $classes_value = Utils::remove_slashes($field['sat-classes']['value']);
        $field_name    = $this->_make_field_name($uid, $field['sat-field']['type'], $field['sat-field']['name']);
        $field_value   = Utils::remove_slashes($field['sat-field']['value']);
        $info_name     = $this->_make_field_name($uid, $field['sat-field']['type'], $field['sat-info']['name'], 'sat-info');
        $info_value    = Utils::remove_slashes($field['sat-info']['value']);
        $label_name    = $this->_make_field_name($uid, $field['sat-label']['type'], $field['sat-label']['name'], 'sat-label');
        $label_value   = Utils::remove_slashes($field['sat-label']['value']);

        $post_id         = get_post_field('ID');
        $current_user_id = get_current_user_id();
        $status          = Utils::get_user_submission_status($current_user_id, $post_id);
        $disabled        = '';

        if ($status === SAT_SUBMISSION_STATUS_PUBLISHED) {
            $disabled = 'disabled';
        }

        include('templates/input_text.template.php');
        return $output;
    }

    /**
     * Creates the markup for a pulldown menu
     * loaded with the saved user content.
     *
     * @param string $uid   The unique id of the shortcode.
     * @param array $field  The saved values for the shortcode.
     * @return string       The HTML markup for the pulldown menu.
     */
    private function _make_update_ds_input_pulldown($uid, $field)
    {
        $output = '';

        $classes_name  = $this->_make_field_name($uid, $field['sat-classes']['type'], $field['sat-classes']['name'], 'sat-classes');
        $classes_value = Utils::remove_slashes($field['sat-classes']['value']);
        $field_name    = $this->_make_field_name($uid, $field['sat-field']['type'], $field['sat-field']['name']);
        $field_value   = Utils::remove_slashes($field['sat-field']['value']);
        $info_name     = $this->_make_field_name($uid, $field['sat-field']['type'], $field['sat-info']['name'], 'sat-info');
        $info_value    = Utils::remove_slashes($field['sat-info']['value']);
        $label_name    = $this->_make_field_name($uid, $field['sat-label']['type'], $field['sat-label']['name'], 'sat-label');
        $label_value   = Utils::remove_slashes($field['sat-label']['value']);
        $options_name  = $this->_make_field_name($uid, $field['sat-options']['type'], $field['sat-options']['name'], 'sat-options');
        $options_value = Utils::remove_slashes($field['sat-options']['value']);

        $options_arr = explode(',', $options_value);

        $post_id         = get_post_field('ID');
        $current_user_id = get_current_user_id();
        $status          = Utils::get_user_submission_status($current_user_id, $post_id);
        $disabled        = '';

        if ($status === SAT_SUBMISSION_STATUS_PUBLISHED) {
            $disabled = 'disabled';
        }

        include('templates/input_pulldown.template.php');
        return $output;
    }

    /**
     * Creates a form field to input the url of a video to embed,
     * loaded with saved values.
     *
     * @since 1.0.0
     *
     * @param string $uid      The unique ID for the shortcode
     * @param array $field     The saved values for the shortcode
     * @return string          The input text markup loaded with saved value, and
     *                         the markup embeding the saved video if previously saved.
     */
    private function _make_update_ds_input_video($uid, $field)
    {
        global $wp_embed;

        $field_name    = $this->_make_field_name($uid, $field['sat-field']['type'], $field['sat-field']['name']);
        $field_value   = Utils::remove_slashes($field['sat-field']['value']);
        $info_name     = $this->_make_field_name($uid, $field['sat-field']['type'], $field['sat-info']['name'], 'sat-info');
        $info_value    = Utils::remove_slashes($field['sat-info']['value']);
        $label_name    = $this->_make_field_name($uid, $field['sat-label']['type'], $field['sat-label']['name'], 'sat-label');
        $label_value   = Utils::remove_slashes($field['sat-label']['value']);
        $classes_name  = $this->_make_field_name($uid, $field['sat-classes']['type'], $field['sat-classes']['name'], 'sat-classes');
        $classes_value = Utils::remove_slashes($field['sat-classes']['value']);

        $post_id         = get_post_field('ID');
        $current_user_id = get_current_user_id();
        $status          = Utils::get_user_submission_status($current_user_id, $post_id);
        $disabled        = '';

        if ($status === SAT_SUBMISSION_STATUS_PUBLISHED) {
            $disabled = 'disabled';
        }

        include('templates/input_video.template.php');
        return $output;
    }

    /**
     * Creates a form textarea field loaded with saved value.
     *
     * @since 1.0.0
     *
     * @param string $uid      The unique ID for the shortcode
     * @param array $field     The saved values for the shortcode
     * @return string          The markup for the textarea loaded with the saved value.
     */
    private function _make_update_ds_input_textarea($uid, $field)
    {
        $output = '';

        $classes_name  = $this->_make_field_name($uid, $field['sat-classes']['type'], $field['sat-classes']['name'], 'sat-classes');
        $classes_value = Utils::remove_slashes($field['sat-classes']['value']);
        $cols_name     = $this->_make_field_name($uid, $field['sat-cols']['type'], $field['sat-cols']['name'], 'sat-cols');
        $cols_value    = Utils::remove_slashes($field['sat-cols']['value']);
        $field_name    = $this->_make_field_name($uid, $field['sat-field']['type'], $field['sat-field']['name']);
        $field_value   = Utils::remove_slashes($field['sat-field']['value']);
        $info_name     = $this->_make_field_name($uid, $field['sat-field']['type'], $field['sat-info']['name'], 'sat-info');
        $info_value    = Utils::remove_slashes($field['sat-info']['value']);
        $label_name    = $this->_make_field_name($uid, $field['sat-label']['type'], $field['sat-label']['name'], 'sat-label');
        $label_value   = Utils::remove_slashes($field['sat-label']['value']);
        $rows_name     = $this->_make_field_name($uid, $field['sat-rows']['type'], $field['sat-rows']['name'], 'sat-rows');
        $rows_value    = Utils::remove_slashes($field['sat-rows']['value']);

        $post_id         = get_post_field('ID');
        $current_user_id = get_current_user_id();
        $status          = Utils::get_user_submission_status($current_user_id, $post_id);
        $disabled        = '';

        if ($status === SAT_SUBMISSION_STATUS_PUBLISHED) {
            $disabled = 'disabled';
        }
        include('templates/input_textarea.template.php');

        return $output;
    }

    /**
     * Creates the markup for an image upload form with the saved image displayed.
     *
     * @since 1.0.0
     *
     * @param string $uid      The unique ID for the shortcode
     * @param array $field     The saved values for the shortcode
     * @return string          The markup for the image display and the image upload button
     */
    private function _make_update_ds_input_uploadimage($uid, $field)
    {
        $output = '';

        $classes_name  = $this->_make_field_name($uid, $field['sat-classes']['type'], $field['sat-classes']['name'], 'sat-classes');
        $classes_value = Utils::remove_slashes($field['sat-classes']['value']);
        $field_name    = $this->_make_field_name($uid, $field['sat-field']['type'], $field['sat-field']['name']);
        $field_value   = Utils::remove_slashes($field['sat-field']['value']);
        $info_name     = $this->_make_field_name($uid, $field['sat-field']['type'], $field['sat-info']['name'], 'sat-info');
        $info_value    = Utils::remove_slashes($field['sat-info']['value']);
        $label_name    = $this->_make_field_name($uid, $field['sat-label']['type'], $field['sat-label']['name'], 'sat-label');
        $label_value   = Utils::remove_slashes($field['sat-label']['value']);

        $post_id         = get_post_field('ID');
        $current_user_id = get_current_user_id();
        $status          = Utils::get_user_submission_status($current_user_id, $post_id);

        include('templates/input_uploadimage.template.php');
        return $output;
    }

    /**
     * Helper method to sanitizes the string with WordPress functionality and,
     * formats the string to work with saving the data in the db.
     *
     * @param string $text The original
     * @uses WordPress\sanitize_text_field
     *
     * @return string
     */
    private function _sanitize_text_field($text)
    {
        return str_replace(['_', ' '], '-', sanitize_text_field($text));
    }

    /**
     * Simple user input sanitization.
     *
     * This method only accept data belonging to
     * one of the pre-defined types constants:
     * - SAT_INPUT_PULLDOWN
     * - SAT_INPUT_TEXT
     * - SAT_INPUT_TEXTAREA
     * - SAT_INPUT_UPLOADIMAGE
     * - SAT_INPUT_VIDEO
     *
     * @since 1.0.0
     * @access private
     *
     * @param string $value    Value comes from $_POST so is always a string
     * @param string $type     One of the predefined constant types
     * @return string|false    The sanitized value or `false` if the type is not
     *                         one of the pre-defined constant types.
     */
    private function _sanitize_user_input($value, $type, $meta='sat-field')
    {
        if ($type === SAT_INPUT_TEXT || $type === SAT_INPUT_PULLDOWN) {
            return sanitize_text_field($value);
        }

        if ($type === SAT_INPUT_TEXTAREA) {
            if ($meta === 'sat-field') {
                return wp_kses($value, array(
                    'a' => array(
                        'href' => array(),
                        'title' => array()
                    ),
                    'br' => array(),
                    'em' => array(),
                    'strong' => array(),
                    'blockquote' => array(),
                    'ol' => array(),
                    'ul' => array(),
                    'li' => array(),
                ));
            } else {
                return sanitize_text_field($value);
            }
        }

        if ($type === SAT_INPUT_UPLOADIMAGE) {
            if ($meta === 'sat-field') {
                return intval($value);
            } else {
                return sanitize_text_field($value);
            }
        }

        if ($type === SAT_INPUT_VIDEO) {
            if ($meta === 'sat-field') {
                return esc_url_raw($value);
            } else {
                return sanitize_text_field($value);
            }
        }

        return false;
    }

    /**
     * Helper method to get the name of an option.
     *
     * The name is created from either the data
     * the saved in the option or with the post ID,
     * and possibly the blog ID for multi-site installs.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @param mixed        $data       The data saved in the database if any. Use 'null' if not available.
     * @param mixed|null   $post_id    Optional. The post ID
     * @param mixed|null   $blog_id    Optional. The blog id if it is a Multi-site install of WordPress.
     * @param mixed|null   $user_id    Optional. An arbitrary WP user id.
     * @return string                  The name of the option
     * @throws Exception
     */
    public static function get_option_name($data, $post_id=null, $blog_id=null, $user_id=null)
    {
        if (null === $post_id) {
            global $post;

            if (Shortcodes::is_submitting()) {
                $post_id = $_POST['wp-post-id'];
            } else {
                $post_id = $post->ID;
            }
        }

        if (null === $user_id) {
            if (isset($_POST['wp-user-id'])) {
                $user_id = $_POST['wp-user-id'];
            } else {
                $user_id = get_current_user_id();
            }
        }

        $makeString = array('show-and-tell');

        if ($data) {
            if (isset($data['wp-blog-id'])) {
                $makeString[] = $data['wp-blog-id'];
            }

            $makeString[] = $data['wp-post-id'];
        } else {
            if ($blog_id) {
                $makeString[] = $blog_id;
            }
            if ($post_id) {
                $makeString[] = $post_id;
            } else {
                throw new \Exception("Error Processing Request", 1);
            }
        }
        $makeString[] = $user_id;
        return implode('_', $makeString);
    }

    /**
     * Helper method, wraps wp's method `get_current_blog_id`
     *
     * Returns the blog id for Multi-site install, and `null`
     * for single-site installations of WordPress.
     *
     * @since 1.0.0
     * @access private
     *
     * @return integer|null The blog id or null if on single-site WordPress install.
     */
    private function _get_blog_id()
    {
        return is_multisite() ? get_current_blog_id() : null;
    }

    /**
     * Helper method to fetch the saved data for this form.
     *
     * @since 1.0.0
     * @access private
     *
     * @return array|null
     */
    private function _get_saved_data()
    {
        global $post;
        if (Shortcodes::is_submitting()) {
            $post_id = $_POST['wp-post-id'];
        } else {
            $post_id = $post->ID;
        }
        return get_option($this->get_option_name(null, $post_id, $this->_get_blog_id()));
    }

    /**
     * Helper method to check if the form is being submitted.
     *
     * Just after the user clicks submit, if true,
     * the data needs to be appropriately saved/updated.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @return boolean True if user just submitted the form.
     */
    public static function is_submitting()
    {
        return isset($_POST['show-and-tell-submitted']);
    }

    /**
     * Helper method to filter content depending on the login
     * status of the current user.
     *
     * @since 1.0.0
     * @access private
     *
     * @return boolean True if login is not required or if user is logged in.
     */
    private function _user_can_view()
    {
        return !$this->_login_required || (get_current_user_id() !== 0);
    }

    /**
     * Creates a well-formatted name for a form field.
     *
     * @since 1.0.0
     * @access private
     *
     * @param string $uid      The shortcode's unique ID
     * @param string $type     The shortcode type ('sat-input-text', 'sat-output-text', etc.)
     * @param string $name     The name for the form field. Will resolve
     *                         to the field type concatenated with the shortcode unique ID if the name
     *                         was not passed as an argument to the shortcode.
     * @param string $meta     Optional. Used to create the secondary fields associated with the
     *                         main field of the shortcode. A meta field can create a label, columns and
     *                         rows for a textarea etc
     * @return string          The well-formatted field name.
     */
    private function _make_field_name($uid, $type, $name, $meta='sat-field')
    {
        return "{$meta}_{$name}_{$type}_{$uid}";
    }

    /**
     * Breaks a well-formatted field name into an array
     * of its meanningful parts (meta, name, type, and uid).
     *
     * @since 1.0.0
     * @access private
     *
     * @param string $key  The well-formatted field name.
     *                     The key string must have at least
     *                     three parts separated by an underscore.
     *                     ex: meta_name_type_uid
     *                     or: name_type_uid
     *                     Only the 'meta' chunch is optional.
     *
     * @return array|false An array of meanningful parts,
     *                     with four keys: uid, type, name, and meta.
     *                     Returns `false` when the key string is not
     *                     a well-formatted field name.
     */
    private function _get_sanitized_key_parts($key)
    {
        $arr = explode('_', $key);
        $l = count($arr);
        if ($l < 2) {
            return false;
        }
        return array(
             'meta' => $l >= 4 ? sanitize_text_field($arr[$l - 4]) : null,
             'name' => $l >= 3 ? sanitize_text_field($arr[$l - 3]) : null,
             'type' => $l >= 2 ? sanitize_text_field($arr[$l - 2]) : null,
             'uid'  => $l >= 1 ? sanitize_text_field($arr[$l - 1]) : null
         );
    }
}
