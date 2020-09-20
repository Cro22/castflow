<?php
/*
 * Copyright (c)  2020-2020, Jesus Nuñez
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files
 * (Excluding the person who has requested the development of this software), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

require_once plugin_dir_path(__FILE__) . 'menu/configuration.php';
require_once plugin_dir_path(__FILE__) . 'menu/shortcodes.php';
add_action('admin_menu', 'incluyeme_login_menus');
add_action('admin_enqueue_scripts', 'incluyeme_login_styles');
function admin_charge()
{
	add_menu_page(
		'iTestor Connect',
		'iTestor Connect',
		'manage_options',
		'itestorconnect',
		'itestor_configuration'
	);
	
	add_submenu_page('itestorconnect',
		'Short Codes',
		'Short Codes',
		'manage_options',
		'itestorshort',
		'itestor_short_codes'
	);
}
