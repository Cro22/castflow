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

/*
Plugin Name: iTestor
Plugin URI: https://github.com/Cro22/castflow
Description: Provides a connection between Castflow and iTestor for data queries.
Version: 1.0
Author: Jesus Nuñez
Author URI: https://github.com/Cro22
License: MIT
*/

include_once(plugin_dir_path(__FILE__) . '/updater/iTestor_Updater.php');
include_once(plugin_dir_path(__FILE__) . '/include/admin/admin_charge.php');


//Updater
$updater = new iTestor_Updater(__FILE__);
$updater->set_username('cro22');
$updater->set_repository('castflow');

$updater->initialize();

//Charge Admin
add_action('admin_menu', 'admin_charge');
