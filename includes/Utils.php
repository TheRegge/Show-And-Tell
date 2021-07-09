<?php
namespace showAndTell\includes;

class Utils
{
    /**
     * Utility method to get the option's basename.
     *
     * This basename is concatenated with a user id to get
     * the full option name.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @return string The options base name
     */
    public static function get_option_basename()
    {
        $parts = ['show-and-tell'];

        if (is_multisite()) {
            $parts[] = get_current_blog_id();
        }

        return implode("_", $parts);
    }

    /**
     * Utility method to get an array of named variables
     * from an option array.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @param array|string  $option Either the option array or the option's name string.
     * @return array                An array of key/values with the following keys:
     *                              `post_id`, `user_id`, and, if multisite, `blog_id`.
     */
    public static function get_optionname_parts($option)
    {
        $wp_option_name = is_array($option) ? $option['option_name'] : $option;
        $arr = explode("_", $wp_option_name);
        $parts = array(
            'basename' => $arr[0]
        );

        $offset = 0;
        if (is_multisite()) {
            $parts['blog_id'] = $arr[1];
            $offset = 1;
        }
        $parts['post_id'] = $arr[1 + $offset];
        $parts['user_id'] = $arr[2 + $offset];

        return $parts;
    }

    /**
     * Utility method to create a WordPress admin notice.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @param string $msg           The message to be displayed
     * @param string $type          One of the four built-in WordPress
     *                              notice types `notice-info`, `notice-warning`,
     *                              `notice-success`, and `notice-error`. Using
     *                              predefined constants to prevent errors.
     * @param boolean $dismissible  Is the notice dissmissible by the user. Defaults to true.
     * @return string               The HTML markup for a WordPress admin notice.
     */
    public static function make_admin_notice(string $msg, string $type=SAT_ADMIN_NOTICE_INFO, bool $dismissible=true)
    {
        $is_dismissible = $dismissible ? ' is-dismissible' : '';
        return <<<EOT
<div class="notice {$type}{$is_dismissible}">
<p>{$msg}</p>
</div>
EOT;
    }

    /**
     * Utility method to create a properly formatted meta value used to query
     * all the posts published from one showcase data entry form.
     *
     * Only one parameter is needed, either the full option array or just
     * the option name string. If both parameters are given, the option array
     * will be used.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @param array $option         The array obtained by querying one form submission.
     * @param string $option_name   An option name (for a form submission).
     * @return string               A string containing the form's post id and the form's
     *                              blog id for multisite installs.
     */
    public static function get_post_meta_value(array $option=null, string $option_name=null)
    {
        $chunks = [];
        if ($option) {
            if (!isset($option['wp-post-id'])) {
                return false;
            }
            if (isset($option['wp-blog-id'])) {
                $chunks[] = $option['wp-blog-id'];
            }
            $chunks[] = $option['wp-post-id'];
        } elseif ($option_name) {
            $arr = explode('_', $option_name);
            $chunks[] = $arr[1];
            if (count($arr) === 4) { // multisite
                $chunks[] = $arr[2];
            }
        }

        return implode('_', $chunks);
    }

    /**
     * Shortens a sentence string to the word closest
     * to `$max_length`.
     *
     * This version only works for left to right languages so far.
     * Adds an HTML ellipsis by default, unless `$add_ellipsis` parameter
     * is set to false;
     *
     * @param string $sentence          The string to shorten.
     * @param integer $max_length       The character count closest to the last word
     *                                  to keep in the sentence.
     * @param boolean $add_ellipsis     Defaults to true. Add an ellipsis at the end of the
     *                                  shortenned sentence.
     * @return string                   The shortenned sentence string.
     */
    public static function shorten_sentence(string $sentence, int $max_length, bool $add_ellipsis=true, string $word_delimiter=' ')
    {
        if (strlen($sentence) > $max_length) {
            $arr = explode($word_delimiter, $sentence);
            $l   = 0;
            $hold = [];

            foreach ($arr as $index => $word) {
                $word_length = strlen($word);
                if (($l + $word_length + 1) <= $max_length) {
                    $hold[] = $word;
                    $l = $l + $word_length + 1;
                } else {
                    break;
                }
            }
            $ellipsis = $add_ellipsis ? '&hellip;' : '';
            return implode(' ', $hold) . $ellipsis;
        } else {
            return $sentence;
        }
    }

    /**
     * Creates the string value for an HTML element's
     * classes attribute
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @param array|string $user_classes    The classes typically passed by the user of a shortcode.
     * @param array|string $plugin_classes  The extra classes added by the plugin.
     * @return string                       A concatenated string of user classes and shortcode classes.
     */
    public static function make_shortcode_classes_value($user_classes, $plugin_classes=[])
    {
        $value = '';
        $plugin_classes = self::trim_is_array_or_explode($plugin_classes);
        $user_classes   = self::trim_is_array_or_explode($user_classes);

        $arr = array_merge($plugin_classes, $user_classes);
        return implode(' ', $arr);
    }

    /**
     * Takes an array or a string and explodes it into an array
     * and returns an array with all elements trimmed.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @param mixed $arr            The array (or string) to trim the string elements.
     * @param string $delimiter     If passed value is a string, where to explode it on.
     * @return array                An array with string elements trimmed.
     */
    public static function trim_is_array_or_explode($arr, $delimiter=',')
    {
        $arr = is_array($arr) ?
            $arr :
            explode($delimiter, $arr);
        return self::trim_array_items($arr);
    }

    /**
     * Utility method. Trims all the string
     * elements of an array.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @param array     $arr The array to process
     * @return array    An array with all elements of type 'string' trimmed;
     *
     */
    public static function trim_array_items($arr)
    {
        $newArr = [];
        foreach ($arr as $el) {
            $newArr[] = self::trim_if_string($el);
        }
        return $newArr;
    }

    /**
     * Returns a trimmed passed value if it is a string.
     * Returns the unchange value if not a string.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @param mixed $str   Expects a string. Ignores and returns other
     *                     value types unchanged.
     * @return mixed       A trimmed string or the unchanged non-string
     *                     passed value.
     */
    public static function trim_if_string($str)
    {
        return is_string($str) ?  trim($str) : $str;
    }

    public static function slugify_tags_string($tags)
    {
        $tags_arr = self::trim_is_array_or_explode($tags);
        $arr = [];
        foreach ($tags_arr as $tag) {
            $arr[] = sanitize_title_with_dashes($tag);
        }
        return implode(', ', $arr);
    }

    public static function get_user_submission_status($user_id, $form_id)
    {
        $option_base_name = self::get_option_basename();
        $option_name = $option_base_name . '_' . $form_id . '_' . $user_id;

        $option = get_option($option_name);

        return isset($option['status']) ? $option['status'] : '';
    }

    /**
     * Returns an array of requested parameters
     * if they were present either in the Request
     *
     * This methods simplifies getting parameters from the request.
     * Instead of testing for the existence of the parameters in the
     * request, get them all in one array if they exist.
     *
     * @static
     * @since   1.0.0
     * @access  public
     *
     * @param   array   $params An array of the requested parameters
     * @param   object  $request The server request
     * @param   string  $type   Request type (defaults to 'GET')
     * @return  array           An array of the requested parameters if they exists.
     */
    public static function get_array_from_request($params, $request, $type='GET')
    {
        $results = array();

        if (!is_array($params)) {
            return $results;
        }

        if ($_SERVER["REQUEST_METHOD"] === $type) {
            foreach ($params as $param) {
                if (isset($request[$param])) {
                    $results[$param] = self::sanitize_text_field_recursive($request[$param]);
                }
            }
        }
        return $results;
    }

    public static function sanitize_text_field_recursive($item)
    {
        if (is_string($item)) {
            return sanitize_text_field($item);
        }

        if (is_array($item)) {
            $l = count($item);
            foreach ($item as $key => $value) {
                $item[$key] = self::sanitize_text_field_recursive($value);
            }
            return $item;
        }

        return $item;
    }

    /**
     * Remove escape sequences that where added
     * by functions like sanitize_text_field.
     *
     * @since 1.0.0
     * @access public
     *
     * @param string $str
     * @return string
     */
    public static function remove_slashes($str)
    {
        if (!is_string($str)) {
            return $str;
        }

        $exp = '/' . '\\' . '\\+' . '\\' . '"/';
        $str = preg_replace($exp, '"', $str, -1);

        $exp = '/' . '\\' . '\\+' . '\\' . "'/";
        $str = preg_replace($exp, "'", $str, -1);
        return $str;
    }
}
