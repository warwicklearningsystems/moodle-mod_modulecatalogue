<?php

/**
 * Defines the view event.
 *
 * @package    mod_modulecatalogue
 * @copyright  2016 University of Warwick <learnsys@warwick.ac.uk>
 */

namespace mod_modulecatalogue\event;

defined('MOODLE_INTERNAL') || die();

/**
 * The mod_modulecatalogue instance viewed event class
 *
 * If the view mode needs to be stored as well, you may need to
 * override methods get_url() and get_legacy_log_data(), too.
 *
 * @package    mod_modulecatalogue
 * @copyright  2016 University of Warwick <learnsys@warwick.ac.uk>
 */
class course_module_viewed extends \core\event\course_module_viewed {

    /**
     * Initialize the event
     */
    protected function init() {
        $this->data['objecttable'] = 'modulecatalogue';
        parent::init();
    }
}
