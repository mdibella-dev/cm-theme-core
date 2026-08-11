		public function current_screen( $screen ) {
			// Determine if the current page being viewed is "ACF" related.
			if ( isset( $screen->post_type ) && in_array( $screen->post_type, acf_get_internal_post_types(), true ) ) {
				add_action( 'in_admin_header', array( $this, 'in_admin_header' ) );
				add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ) );
				add_filter( 'update_footer', array( $this, 'admin_footer_version_text' ) );
				$this->maybe_show_import_from_cptui_notice();
			}
		}

					add_action( 'current_screen', array( $this, 'current_screen' ) );


					https://github.com/WordPress/secure-custom-fields/blob/trunk/includes/admin/admin.php
