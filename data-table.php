<?php
/**
 * @package data_table
 */
/*
Plugin Name: Data table
Plugin URI: https://ptc.com/
Description: Data table plugin is a light weight for count words.
Version: 1.0
Requires at least: 1.0
Requires PHP: 5.2
Author: HemalRika(HR) Foundation
Author URI: https://hemalrika-hr.com
License: GPLv2 or later
Text Domain: data_table
*/
require_once "class.persons-table.php";
function datatable_admin_menu() {
    // create a menu on admin page
    add_menu_page("Data Table", "Data Table", "manage_options", "datatable", "datatable_display_func");
}
add_action("admin_menu", "datatable_admin_menu");


function datatable_display_func() {
    include_once "dataset.php";
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
        $formatted_data = [];

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
    // insert data into table
    $table->set_data($formatted_data);

    // now prepare all items
    $table->prepare_items();
    ?>
    <div class="wrap">
        <h2>Product Purchase Data</h2>
        <form method="GET">
            <?php
            $table->display();
            ?>
        </form>
    </div>
    <?php 
}



/**
    create new table for save data
**/

function dbdemo_init() {
    // for deal with db we need first get $wpdb variable
    global $wpdb;
    // get wp table prefix
    $table_name = $wpdb->prefix. "product_purcase_data";
    // sql query for create table and field
    $sql = "CREATE TABLE {$table_name} (
        id INT NOT NULL AUTO_INCREMENT,
        username VARCHAR(250),
        mobile_number VARCHAR(250),
        address VARCHAR(250),
        product_name VARCHAR(250),
        product_quantity VARCHAR(250),
        product_price VARCHAR(250),
        product_delivery_price VARCHAR(250),
        product_subtotal VARCHAR(250),
        product_total VARCHAR(250),
        PRIMARY KEY(id)
    )";
    require_once (ABSPATH. 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'dbdemo_init');



/**
    * purchase form by sortcode
*/
function product_purchase_table_form() {
    // HTML content to be displayed
    return
        '<section class="form-section-wrapper">
            <div class="form-section-inner-wrap">
                <div class="form-left-sec">
                    <h3 class="form-title">Billing details</h3>
                    <div class="input-form-wrap">
                        <div class="">
                            <label> আপনার নাম <span>*</span>
                                <input type="text" name="name" id="" class="sg-form-control">
                            </label>
                        </div>
            
                        <div class="">
                            <label> মোবাইল নাম্বার <span>*</span>
                                <input type="text" name="mobile-number" id="" class="sg-form-control">
                            </label>
                        </div>
            
                        <div class="">
                            <label> বিস্তারিত ঠিকানা <span>*</span>
                                <input type="text" name="address" placeholder="House/street" id="" class="sg-form-control">
                            </label>
                        </div>
            
                        <div class="">
                            <label class="margin-bottom-0"> Country / Region <span>*</span>
                                <div class="country-name">
                                    Bangladesh
                                </div>
                            </label>
                            
                        </div>

                    </div>
                </div>
                <div class="form-right-sec">
                    <h3 class="form-title">Your order</h3>
                    <div class="product-subtitle-title">
                        <span>Product</span>
                        <span>Subtotal</span>
                    </div>
                    <div class="product-select-check-box">
                        <div class="single-product-select-wrap">
                            <label for="talbina-half-kg">
                                <div class="product-talbina-wrap">
                                    <div class="product-img-title">
                                        <input type="radio" checked id="talbina-half-kg" name="selected_product" data-price="800" data-combo_product="false" value="0.5 Kg Talbina Combo Pack">
                                        <div class="product-img-con">
                                            <img src="http://uiexpertz.com/wp-content/uploads/2023/09/uiexpertz-product-img.jpg" alt="talbina" class="product-img">
                                        </div>
                                        <div class="product-cart-title-subtitle">
                                            <h5>
                                                0.5 Kg Talbina Combo Pack
                                            </h5>
                                            <p>
                                                0.5 Kg Talbina Mix + Half Kg Mustard Flower Honey
                                            </p>
                                        </div>
                                    </div>
                                    <div class="">
                                        <div class="num-of-product-price-wrap">
                                            <div class="quantity-form-wrap">
                                                <input class="product-quantity" id="number" aria-invalid="false" value="1" type="number" name="number-of-product">
                                                <div class="inc-dic-btn-wrappper">
                                                    <div class="value-button" id="increase" onclick="increaseValue()" value="Increase Value">
                                                        <p><svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <g clip-path="url(#clip0_62_94)">
                                                            <path d="M8.7876 5.84814H7.00635V4.06689C7.00635 4.01533 6.96416 3.97314 6.9126 3.97314H6.3501C6.29854 3.97314 6.25635 4.01533 6.25635 4.06689V5.84814H4.4751C4.42354 5.84814 4.38135 5.89033 4.38135 5.94189V6.50439C4.38135 6.55596 4.42354 6.59814 4.4751 6.59814H6.25635V8.37939C6.25635 8.43096 6.29854 8.47314 6.3501 8.47314H6.9126C6.96416 8.47314 7.00635 8.43096 7.00635 8.37939V6.59814H8.7876C8.83916 6.59814 8.88135 6.55596 8.88135 6.50439V5.94189C8.88135 5.89033 8.83916 5.84814 8.7876 5.84814Z" fill="#82817D"></path>
                                                            <path d="M6.63135 0.973145C3.73213 0.973145 1.38135 3.32393 1.38135 6.22314C1.38135 9.12236 3.73213 11.4731 6.63135 11.4731C9.53057 11.4731 11.8813 9.12236 11.8813 6.22314C11.8813 3.32393 9.53057 0.973145 6.63135 0.973145ZM6.63135 10.5825C4.22432 10.5825 2.27197 8.63018 2.27197 6.22314C2.27197 3.81611 4.22432 1.86377 6.63135 1.86377C9.03838 1.86377 10.9907 3.81611 10.9907 6.22314C10.9907 8.63018 9.03838 10.5825 6.63135 10.5825Z" fill="#82817D"></path>
                                                            </g>
                                                            <defs>
                                                            <clipPath id="clip0_62_94">
                                                            <rect width="12" height="12" fill="white" transform="translate(0.631348 0.223145)"></rect>
                                                            </clipPath>
                                                            </defs>
                                                        </svg>
                                                        </p>
                                                    </div>
                                                    <div class="value-button" id="decrease" onclick="decreaseValue()" value="Decrease Value">
                                                        <p><svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <g clip-path="url(#clip0_62_95)">
                                                            <path d="M8.7876 5.84814H4.4751C4.42354 5.84814 4.38135 5.89033 4.38135 5.94189V6.50439C4.38135 6.55596 4.42354 6.59814 4.4751 6.59814H8.7876C8.83916 6.59814 8.88135 6.55596 8.88135 6.50439V5.94189C8.88135 5.89033 8.83916 5.84814 8.7876 5.84814Z" fill="#82817D"></path>
                                                            <path d="M6.63135 0.973145C3.73213 0.973145 1.38135 3.32393 1.38135 6.22314C1.38135 9.12236 3.73213 11.4731 6.63135 11.4731C9.53057 11.4731 11.8813 9.12236 11.8813 6.22314C11.8813 3.32393 9.53057 0.973145 6.63135 0.973145ZM6.63135 10.5825C4.22432 10.5825 2.27197 8.63018 2.27197 6.22314C2.27197 3.81611 4.22432 1.86377 6.63135 1.86377C9.03838 1.86377 10.9907 3.81611 10.9907 6.22314C10.9907 8.63018 9.03838 10.5825 6.63135 10.5825Z" fill="#82817D"></path>
                                                            </g>
                                                            <defs>
                                                            <clipPath id="clip0_62_95">
                                                            <rect width="12" height="12" fill="white" transform="translate(0.631348 0.223145)"></rect>
                                                            </clipPath>
                                                            </defs>
                                                        </svg>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="prod-price">
                                                <p><span>৳</span><span class="pp-800">800</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div class="single-product-select-wrap">
                            <label for="talbina-one-kg">
                                <div class="product-talbina-wrap">
                                    <div class="product-img-title">
                                        <input type="radio" id="talbina-one-kg" data-combo_product="true" name="selected_product" data-price="1500" value="1 Kg Talbina Combo Pack">
                                        <div class="product-img-con">
                                            <img src="http://uiexpertz.com/wp-content/uploads/2023/09/uiexpertz-product-img.jpg" alt="talbina" class="product-img">
                                        </div>
                                        <div class="">
                                            <h5>
                                                1 Kg Talbina Combo Pack
                                            </h5>
                                            <p>
                                                1 Kg Talbina Mix + 1 Kg Mustard Flower Honey
                                            </p>
                                        </div>
                                    </div>
                                    <div class="">
                                        <div class="num-of-product-price-wrap">
                                            <div class="quantity-form-wrap">
                                                <input class="product-quantity" id="number1" aria-invalid="false" value="1" type="number" name="number-of-product">
                                                <div class="inc-dic-btn-wrappper">
                                                    <div class="value-button" id="increase" onclick="increaseValue1()" value="Increase Value">
                                                        <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <g clip-path="url(#clip0_62_94)">
                                                            <path d="M8.7876 5.84814H7.00635V4.06689C7.00635 4.01533 6.96416 3.97314 6.9126 3.97314H6.3501C6.29854 3.97314 6.25635 4.01533 6.25635 4.06689V5.84814H4.4751C4.42354 5.84814 4.38135 5.89033 4.38135 5.94189V6.50439C4.38135 6.55596 4.42354 6.59814 4.4751 6.59814H6.25635V8.37939C6.25635 8.43096 6.29854 8.47314 6.3501 8.47314H6.9126C6.96416 8.47314 7.00635 8.43096 7.00635 8.37939V6.59814H8.7876C8.83916 6.59814 8.88135 6.55596 8.88135 6.50439V5.94189C8.88135 5.89033 8.83916 5.84814 8.7876 5.84814Z" fill="#82817D"></path>
                                                            <path d="M6.63135 0.973145C3.73213 0.973145 1.38135 3.32393 1.38135 6.22314C1.38135 9.12236 3.73213 11.4731 6.63135 11.4731C9.53057 11.4731 11.8813 9.12236 11.8813 6.22314C11.8813 3.32393 9.53057 0.973145 6.63135 0.973145ZM6.63135 10.5825C4.22432 10.5825 2.27197 8.63018 2.27197 6.22314C2.27197 3.81611 4.22432 1.86377 6.63135 1.86377C9.03838 1.86377 10.9907 3.81611 10.9907 6.22314C10.9907 8.63018 9.03838 10.5825 6.63135 10.5825Z" fill="#82817D"></path>
                                                            </g>
                                                            <defs>
                                                            <clipPath id="clip0_62_94">
                                                            <rect width="12" height="12" fill="white" transform="translate(0.631348 0.223145)"></rect>
                                                            </clipPath>
                                                            </defs>
                                                        </svg>
                                                    </div>
                                                    <div class="value-button" id="decrease" onclick="decreaseValue1()" value="Decrease Value">
                                                        <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <g clip-path="url(#clip0_62_95)">
                                                            <path d="M8.7876 5.84814H4.4751C4.42354 5.84814 4.38135 5.89033 4.38135 5.94189V6.50439C4.38135 6.55596 4.42354 6.59814 4.4751 6.59814H8.7876C8.83916 6.59814 8.88135 6.55596 8.88135 6.50439V5.94189C8.88135 5.89033 8.83916 5.84814 8.7876 5.84814Z" fill="#82817D"></path>
                                                            <path d="M6.63135 0.973145C3.73213 0.973145 1.38135 3.32393 1.38135 6.22314C1.38135 9.12236 3.73213 11.4731 6.63135 11.4731C9.53057 11.4731 11.8813 9.12236 11.8813 6.22314C11.8813 3.32393 9.53057 0.973145 6.63135 0.973145ZM6.63135 10.5825C4.22432 10.5825 2.27197 8.63018 2.27197 6.22314C2.27197 3.81611 4.22432 1.86377 6.63135 1.86377C9.03838 1.86377 10.9907 3.81611 10.9907 6.22314C10.9907 8.63018 9.03838 10.5825 6.63135 10.5825Z" fill="#82817D"></path>
                                                            </g>
                                                            <defs>
                                                            <clipPath id="clip0_62_95">
                                                            <rect width="12" height="12" fill="white" transform="translate(0.631348 0.223145)"></rect>
                                                            </clipPath>
                                                            </defs>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="prod-price">
                                                <p><span>৳</span><span class="pp-1500">1500</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="subtotal-price-title">
                        <p>Subtotal</p>
                        <p class="subtotal-price"><span>৳</span><span class="subtotal-price">800</span></p>
                    </div>

                    <div class="shipping-section-wrapper">
                        <div class="radio-check-wrap">
                            
                            <div class="">
                                <label for="check_in_dhaka">
                                    <input type="radio" id="check_in_dhaka" checked name="select_delevery_location" value="80">
                                    <span class="text-in-dhaka-city">ঢাকা সিটির মধ্যে:</s>
                                    <span class="subtotal-price"><span>৳</span><span class="delivery-in-dhaka-price">80</span></span>
                                </label>
                            </div>
                            <div class="">
                                <label for="check_out_dhaka">
                                    <input type="radio" id="check_out_dhaka" name="select_delevery_location" value="120">
                                    <span class="text-out-dhaka-city">ঢাকা সিটির বাহিরে:</s>
                                    <span class="subtotal-price"><span>৳</span><span class="delivery-out-of-dhaka-price">120</span></span>
                                </label>
                            </div>
                            
                        </div>
                        <div class="shipping-price-title">
                            <p>Shipping</p>
                            <p class="subtotal-price"><span>৳</span><span class="shipping-price">80</span></p>
                        </div>
                        <div class="total-price-title">
                            <p>Total</p>
                            <p class="subtotal-price"><span>৳</span><span class="total-price">880</span></p>
                        </div>

                        <div class="cash-on-delivery">
                            ক্যাশ অন ডেলিভারী
                        </div>
                        <div class="text-like-btn">
                            পণ্য হাতে পাওয়ার পর টাকা পরিশোধ করুন
                        </div>

                        <div class="privacy-policy-text">
                            Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our <a href="#">privacy policy</a>.
                        </div>

                        <button type="button" class="form-submit-btn">অর্ডার প্লেস করুন </button>
                    </div>
                </div>
            </div>
        </section>';
}
add_shortcode('product_purcase_table_sortcode', 'product_purchase_table_form');



/**
 * Add essential assets
 * */

function enqueue_custom_styles_and_scripts() {
        // Enqueue your custom CSS file
        wp_enqueue_style('custom-styles', plugins_url('style.css', __FILE__));
        // Enqueue your custom JavaScript file with jQuery as a dependency
        wp_enqueue_script('custom-script', plugins_url('script.js', __FILE__), array('jquery'), '1.0', true);
        // pass ajax variable
        wp_localize_script('custom-script', 'ajaxurl', array(
            'ajaxurl' => admin_url('admin-ajax.php')
        ));
}

add_action('wp_enqueue_scripts', 'enqueue_custom_styles_and_scripts');



