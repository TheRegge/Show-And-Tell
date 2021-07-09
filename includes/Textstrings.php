<?php

namespace showAndTell\includes;

class Textstrings
{
    public static $strings = array();


    public static function getall()
    {
        self::$strings['Accepts multiple submissions']               = __('Accepts multiple submissions', 'show-and-tell');
        self::$strings['Close Editor Modal Title']                   = __('Your Form was not saved!', 'show-and-tell');
        self::$strings['Close Without Saving']                       = __('Close Without Saving', 'show-and-tell');
        self::$strings['close_editor_message']                       = __("If you click 'Close Without Saving,'  any changes you made will be lost. Click 'Continue Editing' to continue editing your form.", 'show_and_tell');
        self::$strings['Continue Editing']                           = __('Continue Editing', 'show-and-tell');
        self::$strings['Copy Shortcode']                             = __('Copy shortcode', 'show-and-tell');
        self::$strings['Create new form']                            = __('Create new form', 'show-and-tell');
        self::$strings['Delete']                                     = __('Delete', 'show-and-tell');
        self::$strings['Disable form after this date']               = __('Disable form after this date', 'show-and-tell');
        self::$strings['Disabled']                                   = __('Expires', 'show-and-tell');
        self::$strings['Edit']                                       = __('Edit', 'show-and-tell');
        self::$strings['Form Title']                                 = __('Form Title', 'show-and-tell');
        self::$strings['Maximum number of characters exceeded']      = __('Maximum number of character exceeded', 'show-and-tell');
        self::$strings['New Form']                                   = __('New Form', 'show-and-tell');
        self::$strings['No form was selected']                       = __('No form was selected', 'show-and-tell');
        self::$strings['Paragraph Text']                             = __('Paragraph of Text', 'show-and-tell');
        self::$strings['Please edit this form']                      = __('Please edit this form', 'show-and-tell');
        self::$strings['Please enter a title']                       = __('Please enter a title.', 'daton-showcase.');
        self::$strings['Please enter a valid date']                  = __('Please enter a valide date.', 'show-and-tell');
        self::$strings['Save form']                                  = __('Save Form', 'show-and-tell');
        self::$strings['Select a day']                               = __('Select a Day', 'show-and-tell');
        self::$strings['Single checkbox']                            = __('Single Checkbox', 'show-and-tell');
        self::$strings['Single line text']                           = __('Single Line of Text', 'show-and-tell');
        self::$strings['Status']                                     = __('Status', 'show-and-tell');
        self::$strings['Submissions']                                = __('Submissions', 'show-and-tell');
        self::$strings['The form was created']                       = __('The form was created.', 'show-and-tell');
        self::$strings['The form was updated']                       = __('The form was updated.', 'show-and-tell');
        self::$strings['The shortcode was copied to your clipboard'] = __('The shortcode was copied to your clipboard', 'show-and-tell');
        self::$strings['Your form was not saved']                    = __('Your form was not saved!', 'show-and-tell');

        return json_encode(self::$strings);
    }
}
