<?php
/** Prevent direct access */

defined( 'ABSPATH' ) or exit;


// API
require_once 'api/api-events.php';
require_once 'api/api-locations.php';
require_once 'api/api-partners.php';
require_once 'api/api-sessions.php';
require_once 'api/api-speakers.php';


// Custom Post Types
require_once 'register/post-types/post-type-speaker.php';
require_once 'register/post-types/post-type-session.php';
require_once 'register/post-types/post-type-partner.php';
require_once 'register/post-types/post-type-exhibition-space.php';


// Custom Taxonomies
require_once 'register/taxonomies/taxonomy-partnership.php';
require_once 'register/taxonomies/taxonomy-event.php';
require_once 'register/taxonomies/taxonomy-exhibition-package.php';
require_once 'register/taxonomies/taxonomy-location.php';
