<?php
// first add class-wp-list-table.php
if ( ! class_exists( "WP_List_Table" ) ) {
	require_once( ABSPATH . "wp-admin/includes/class-wp-list-table.php" );
}

class Persons_Table extends WP_List_Table {
    // create temparory variable $_items for pagination
    private $_items;
    // construct first
    function __construct( $args = array()) {
        parent::__construct($args);
    }
    // set data
    function set_data() {
        /**
         * Get data from database
         * */
        global $wpdb;
        $table_name = $wpdb->prefix. "product_purcase_data";
        // create table  using Persons_Table class
        $table = new Persons_Table();
        /**
         * get all data from database table
         * */
        $get_all_data = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
        $formatted_data = [];
        if ($get_all_data) {
            foreach ($get_all_data as $data_single) {
                $formatted_data[] = [
                    'id' => $data_single['id'],
                    'username' => $data_single['username'],
                    'mobile_number' => $data_single['mobile_number'],
                    'address' => $data_single['address'],
                    'product_name' => $data_single['product_name'],
                    'product_quantity' => $data_single['product_quantity'],
                    'product_price' => $data_single['product_price'],
                    'product_delivery_price' => $data_single['product_delivery_price'],
                    'product_subtotal' => $data_single['product_subtotal'],
                    'product_total' => $data_single['product_total'],
                ];
            }
        }
        // for pagination
        $this->_items = $formatted_data;
    }
    // set our custom columns
    function get_columns() {
        return [
            'cb' => '<input type="checkbox" />',
            'username' => 'User Name',
            'mobile_number' => 'Mobile Number',
            'address' => 'Address',
            'product_name' => 'Product Name',
            'product_quantity' => 'Product Quantity',
            'product_price' => 'Product Price',
            'product_delivery_price' => 'Product Delevery Price',
            'product_subtotal' => 'Product Subtotal',
            'product_total' => 'Product Total', // final after all calculate
        ];
    }
    function column_cb($item) {
        return "<input type='checkbox' value={$item['id']} />";
    }
    function column_username($item) {
        return "<strong>{$item['username']}</strong>";
    }
    function column_mobile_number($item) {
        return "<strong>{$item['mobile_number']}</strong>";
    }
    function column_address($item) {
        return "<strong>{$item['address']}</strong>";
    }
    function column_product_name($item) {
        return "<strong>{$item['product_name']}</strong>";
    }
    function column_product_quantity($item) {
        return "<strong>{$item['product_quantity']}</strong>";
    }
    function column_product_price($item) {
        return "<strong>{$item['product_price']}</strong>";
    }
    function column_product_delivery_price($item) {
        return "<strong>{$item['product_delivery_price']}</strong>";
    }
    function column_product_subtotal($item) {
        return "<strong>{$item['product_subtotal']}</strong>";
    }
    function column_product_total($item) {
        return "<strong>{$item['product_total']}</strong>";
    }
    function extra_tablenav($which) {
        if('top' == $which) :?>
        <div class="actions alignleft">
            <select name="filter_s" id="filter_s">
                <option value="all">All</option>
                <option value="M">Male</option>
                <option value="F">Female</option>
            </select>
            <?php submit_button('Filter', 'button', 'submits', false); ?>
        </div>
    <?php endif;
    }
    function prepare_items() {
        // get columns
        $this->_column_headers = array($this->get_columns(), array());
        /**
         * Create a pagination
         */
        $total_page = count($this->_items);
        $per_page = 30;
        $paged = $_REQUEST['paged'] ?? 1;
        $data_chunks = array_chunk($this->_items, $per_page);
        $this->items = $data_chunks ? $data_chunks[$paged - 1]: array();
        $this->set_pagination_args([
            'total_items' => $total_page,
            'per_page' => $per_page,
            'total_page' => ceil($total_page / $per_page)
        ]);
    }
    // for insert data into table ( item is each row)
    function column_default($item, $column_name) {
        return $item[$column_name];
    }
}

/***
* handle ajax request
*/

function product_delevary_ajax_func() {
    $data = $_POST['data'];
    $address = $data['address'];
    $phone = $data['phone'];
    $quantity = $data['quantity'];
    $selectedProduct = $data['selectedProduct'];
    $subtotal = $data['subtotal'];
    $total = $data['total'];
    $price = $data['price'];
    $select_delevery_price = $data['select_delevery_price'];
    $username = $data['username'];
    // Always exit to avoid extra output
    /**
     * insert data into database
     * */
    global $wpdb;
    // get wp table prefix
    $table_name = $wpdb->prefix. "product_purcase_data";
    /**
     * Fetch data after form submit
     * */
    $unique_id = bin2hex(random_bytes(16));
    $data = [
            'id' => $unique_id,
            'username' => $username,
            'mobile_number' => $phone,
            'address' => $address,
            'product_name' => $selectedProduct,
            'product_quantity' => $quantity,
            'product_price' => $price,
            'product_delivery_price' => $select_delevery_price,
            'product_subtotal' => $subtotal,
            'product_total' => $total
    ];
    $table_name = $wpdb->prefix . 'product_purcase_data';
    $wpdb->insert($table_name, $data);
     if ($wpdb->insert_id) {
        $response = array(
            'message' => 'Data inserted successfully.',
            'data' => $data
        );
    } else {
        $response = array(
            'message' => 'Error inserting data.',
            'data' => $data
        );
    }
    wp_send_json($response);
    wp_die();
}

// Hook the PHP function to WordPress AJAX action
add_action('wp_ajax_product_delevary_ajax_func', 'product_delevary_ajax_func');
add_action('wp_ajax_nopriv_product_delevary_ajax_func', 'product_delevary_ajax_func'); 