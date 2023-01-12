<?php
class BM_DBhandler {

    public function insert_row( $identifier, $data, $format = null ) {
        global $wpdb;
        $bm_activator = new Booking_Management_Activator();
        $table        = $bm_activator->get_db_table_name( $identifier );
        $result       = $wpdb->insert( $table, $data, $format );

        if ( $result !== false ) {
			return $wpdb->insert_id; } else {
			return false; }
    }

    public function update_row( $identifier, $unique_field, $unique_field_value, $data, $format = null, $where_format = null ) {
        global $wpdb;
        $bm_activator = new Booking_Management_Activator();
        $table        = $bm_activator->get_db_table_name( $identifier );
        if ( $unique_field === false ) {
            $unique_field = $bm_activator->get_db_table_unique_field_name( $identifier );
        }

        if ( is_numeric( $unique_field_value ) ) {
            $unique_field_value = (int) $unique_field_value;
            $query              = $wpdb->prepare( "SELECT * from $table where $unique_field = %d", $unique_field_value );
        } else {
            $query = $wpdb->prepare( "SELECT * from $table where $unique_field = %s", $unique_field_value );
        }

        if ( $query != null ) {
            $result = $wpdb->get_row( $query );
        }

        if ( $result === null ) {
			return false; }

		$where = array( $unique_field => $unique_field_value );
        return $wpdb->update( $table, $data, $where, $format, $where_format );
    }

    public function remove_row( $identifier, $unique_field, $unique_field_value, $where_format = null ) {
        global $wpdb;
        $bm_activator = new Booking_Management_Activator();
        $table        = $bm_activator->get_db_table_name( $identifier );
        if ( $unique_field === false ) {
			$unique_field = $bm_activator->get_db_table_unique_field_name( $identifier );
        }

        if ( is_numeric( $unique_field_value ) ) {
            $unique_field_value = (int) $unique_field_value;
            $query              = $wpdb->prepare( "SELECT * from $table WHERE $unique_field = %d", $unique_field_value );
        } else {
            $query = $wpdb->prepare( "SELECT * from $table WHERE $unique_field = %s", $unique_field_value );
        }

        if ( $query != null ) {
            $result = $wpdb->get_row( $query );
        }

        if ( $result === null ) {
			return false; }

		$where = array( $unique_field => $unique_field_value );
        return $wpdb->delete( $table, $where, $where_format );
    }

    public function get_row( $identifier, $unique_field_value, $unique_field = false, $output_type = 'OBJECT' ) {
        global $wpdb;
        $bm_activator = new Booking_Management_Activator();
        $table        = $bm_activator->get_db_table_name( $identifier );
        $result       = null;
        if ( $unique_field === false ) {
			$unique_field = $bm_activator->get_db_table_unique_field_name( $identifier );
        }

        if ( is_numeric( $unique_field_value ) ) {
            $unique_field_value = (int) $unique_field_value;
            $query              = $wpdb->prepare( "SELECT * from $table where $unique_field = %d", $unique_field_value );
        } else {
            $query = $wpdb->prepare( "SELECT * from $table where $unique_field = %s", $unique_field_value );
        }

        if ( $query != null ) {
            $result = $wpdb->get_row( $query, $output_type );
        }

        if ( $result != null ) {
			return $result; }
    }

    public function get_value( $identifier, $field, $unique_field_value, $unique_field = false ) {
         global $wpdb;
        $bm_activator = new Booking_Management_Activator();
        $table        = $bm_activator->get_db_table_name( $identifier );

        if ( $unique_field === false ) {
			$unique_field = $bm_activator->get_db_table_unique_field_name( $identifier );
        }

        if ( is_numeric( $unique_field_value ) ) {
            $unique_field_value = (int) $unique_field_value;
            $query              = $wpdb->prepare( "SELECT $field from $table where $unique_field = %d", $unique_field_value );
        } else {
            $query = $wpdb->prepare( "SELECT $field from $table where $unique_field = %s", $unique_field_value );
        }

        if ( $query != null ) {
            $result = $wpdb->get_var( $query );
        }

        if ( isset( $result ) && $result != null ) {
			return $result; }
    }

    public function get_value_with_multicondition( $identifier, $field, $where ) {
         global $wpdb;
        $bm_activator = new Booking_Management_Activator();
        $table        = $bm_activator->get_db_table_name( $identifier );
        $qry          = "SELECT $field from $table where";
        $i            = 0;
        $args         = array();
        foreach ( $where as $column_name => $column_value ) {

			if ( $i !== 0 ) {
				$qry .= ' AND'; }

                $format = $bm_activator->get_db_table_field_type( $identifier, $column_name );
                $qry   .= " $column_name = $format";

			if ( is_numeric( $column_value ) ) {
				$args[] = (int) $column_value; } else {
				$args[] = $column_value; }

                $i++;
		}
             $results = $wpdb->get_var( $wpdb->prepare( $qry, $args ) );
             return $results;
    }

    public function get_all_result( $identifier, $column = '*', $where = 1, $result_type = 'results', $offset = 0, $limit = false, $sort_by = null, $descending = false, $additional = '', $output = 'OBJECT', $distinct = false ) {
        global $wpdb;
        $bm_activator   = new Booking_Management_Activator();
        $table          = $bm_activator->get_db_table_name( $identifier );
        $unique_id_name = $bm_activator->get_db_table_unique_field_name( $identifier );
        $args           = array();
        if ( !$sort_by ) {
            $sort_by = $unique_id_name;
        }
        if ( is_string( $column ) && strpos( $column, 'distinct' ) ) {
            $column   = str_replace( 'distinct ', '', $column );
            $distinct = true;
        } elseif ( is_string( $column ) && strpos( $column, 'DISTINCT' ) ) {
            $column   = str_replace( 'DISTINCT ', '', $column );
            $distinct = true;
        }

        if ( $column != '' && !is_array( $column ) && $distinct == false ) {
            $qry = "SELECT $column FROM $table WHERE";
        } elseif ( $column != '' && !is_array( $column ) && $distinct == true ) {
            $qry = "SELECT DISTINCT $column FROM $table WHERE";
        } elseif ( is_array( $column ) ) {
            $qry = 'SELECT ' . implode( ', ', $column ) . " FROM $table WHERE";
        }

        if ( is_array( $where ) ) {
            $i = 0;
            foreach ( $where as $column_name => $column_value ) {

                if ( $i !== 0 ) {
					$qry .= ' AND'; }

                $format = $bm_activator->get_db_table_field_type( $identifier, $column_name );
                $qry   .= " $column_name = $format";

                if ( is_numeric( $column_value ) ) {
					$args[] = (int) $column_value; } else {
					$args[] = $column_value; }

					$i++;
            }
			if ( $additional!='' ) {
                $qry .= ' ' . $additional;
			}
        } elseif ( $where == 1 ) {
            if ( $additional!='' ) {
                $qry .= ' ' . $additional;
            } else {
                $qry .= ' 1';
            }
        }

        if ( $descending === false ) {
            $qry .= " ORDER BY $sort_by";
        } else {
            $qry .= " ORDER BY $sort_by DESC";
        }

		if ( $limit===false ) {
            $qry .= '';
        } else {
            $qry .= " LIMIT $limit OFFSET $offset";
        }

        if ( $result_type === 'results' || $result_type === 'row' || $result_type === 'var' ) {
            $method_name = 'get_' . $result_type;
            if ( count( $args ) === 0 ) {
                if ( $result_type === 'results' ) :
                    $results = $wpdb->$method_name( $qry, $output );
                else :
                    $results = $wpdb->$method_name( $qry );
                endif;
            } else {
                if ( $result_type === 'results' ) :
                    $results = $wpdb->$method_name( $wpdb->prepare( $qry, $args ), $output );
                else :
                    $results = $wpdb->$method_name( $wpdb->prepare( $qry, $args ) );
                endif;
            }
        } else {
            return null;
        }

        if ( is_array( $results ) && count( $results )===0 ) {
            return null;
        }
        return $results;
    }

    public function bm_count( $identifier, $where = 1, $data_specifiers = '' ) {
        global $wpdb;
        $bm_activator = new Booking_Management_Activator();
        $table_name   = $bm_activator->get_db_table_name( $identifier );
        if ( $data_specifiers=='' ) {
            $unique_id_name = $bm_activator->get_db_table_unique_field_name( $identifier );
            if ( $unique_id_name === false ) {
				return false; }
        } else {
			$unique_id_name = $data_specifiers; }

        $qry = "SELECT COUNT($unique_id_name) FROM $table_name WHERE ";

        if ( is_array( $where ) ) {
            $i =0;
            foreach ( $where as $column_name => $column_value ) {
                if ( $i!=0 ) {
					$qry .= 'AND '; }
                if ( is_numeric( $column_value ) ) {
                    $column_value = (int) $column_value;
                    $qry         .= $wpdb->prepare( "$column_name = %d ", $column_value );
                } else {
                    $qry .= $wpdb->prepare( "$column_name = %s ", $column_value );
                }
            }
        } elseif ( $where == 1 ) {
			$qry .= '1 '; }

        $count = $wpdb->get_var( $qry );

        if ( $count === null ) {
			return false; }

        return (int) $count;
    }

    public function get_global_option_value( $option, $default = '' ) {
            $value = get_option( $option, $default );
		if ( !isset( $value ) || $value=='' ) {
			$value = $default; }
            $value = maybe_unserialize( $value );
            return $value;
    }

    public function update_global_option_value( $option, $value ) {
            update_option( $option, $value );
    }

	public function bm_get_pagination( $num_of_pages, $pagenum, $base = '' ) {
		if ( $pagenum=='' ) {
			$pagenum =1; }
        if ( $base=='' ) {
			$base = esc_url_raw( add_query_arg( 'pagenum', '%#%' ) ); }
		$args = array(
			'base'               => $base,
			'format'             => '',
			'total'              => $num_of_pages,
			'current'            => $pagenum,
			'show_all'           => false,
			'end_size'           => 1,
			'mid_size'           => 2,
			'prev_next'          => true,
			'prev_text'          => __( '&laquo;', 'profilegrid-user-profiles-groups-and-communities' ),
			'next_text'          => __( '&raquo;', 'profilegrid-user-profiles-groups-and-communities' ),
			'type'               => 'list',
			'add_args'           => false,
			'add_fragment'       => '',
			'before_page_number' => '',
			'after_page_number'  => '',
		);

		$page_links = paginate_links( $args );
		return $page_links;
	}
}
