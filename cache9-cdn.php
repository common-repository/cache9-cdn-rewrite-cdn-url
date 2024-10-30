<?php
/*
Plugin Name: Cache9 CDN
Plugin URI: http://www.cache9.com/
Description: Rewrite the root url to CDN URL and cache them to CDN server. Cache9 코리아의 대한민국 전용 CDN 서비스용 도메인 변경 플러그인.
Author: Chris216(Cache9.com)
Author URI: http://www.cache9.com/
Version: 0.1.0
License: GPL2

Copyright 2014 Cache9.com (email : cs.cache9@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

	include('function.php');
	include('setting.php');

	if( !defined( 'WPCONT' ) ) define( 'WPCONT', 'wp-content' );
	if( !defined( 'WPINC' ) ) define( 'WPINC', 'wp-includes' );
?>