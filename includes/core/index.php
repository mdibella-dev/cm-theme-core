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
require_once 'post-types/post-type-speaker.php';
require_once 'post-types/post-type-session.php';
require_once 'post-types/post-type-partner.php';
require_once 'post-types/post-type-exhibition-space.php';


// Custom Taxonomies
require_once 'taxonomies/taxonomy-partnership.php';
require_once 'taxonomies/taxonomy-event.php';
require_once 'taxonomies/taxonomy-exhibition-package.php';
require_once 'taxonomies/taxonomy-location.php';
