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

class iTestor_Updater
{
	protected $file;
	protected $plugin;
	protected $basename;
	protected $active;
	private $username;
	private $repository;
	private $authorize_token;
	private $github_response;
	
	/**
	 * iTestor_Updater constructor.
	 * @param $file
	 */
	public function __construct($file)
	{
		$this->file = $file;
		add_action('admin_init', [$this, 'set_plugin_properties']);
		return $this;
	}
	
	public function set_plugin_properties()
	{
		$this->plugin = get_plugin_data($this->file);
		$this->basename = plugin_basename($this->file);
		$this->active = is_plugin_active($this->basename);
	}
	
	/**
	 * @param $username
	 */
	public function set_username($username)
	{
		$this->username = $username;
	}
	
	/**
	 * @param $repository
	 */
	public function set_repository($repository)
	{
		$this->repository = $repository;
	}
	
	/**
	 * @param $token
	 */
	public function authorize($token)
	{
		$this->authorize_token = $token;
	}
	
	public function initialize()
	{
		add_filter('pre_set_site_transient_update_plugins', [$this, 'modify_transient'], 10, 1);
		add_filter('plugins_api', [$this, 'plugin_popup'], 10, 3);
		add_filter('upgrader_post_install', [$this, 'after_install'], 10, 3);
	}
	
	private function get_repository_info()
	{
		if (is_null($this->github_response)) {
			$request_uri = sprintf('https://api.github.com/repos/%s/%s/releases', $this->username, $this->repository);
			if ($this->authorize_token) {
				$request_uri = add_query_arg('access_token', $this->authorize_token, $request_uri);
			}
			$response = json_decode(wp_remote_retrieve_body(wp_remote_get($request_uri)), true);
			if (is_array($response)) {
				$response = current($response);
			}
			if ($this->authorize_token) {
				$response['zipball_url'] = add_query_arg('access_token', $this->authorize_token, $response['zipball_url']);
			}
			error_log(print_r((object)$response, true));
			$this->github_response = $response;
		}
	}
	
	/**
	 * @param $transient
	 * @return mixed
	 */
	public function modify_transient($transient)
	{
		
		if (property_exists($transient, 'checked')) {
			if ($checked = $transient->checked) {
				$this->get_repository_info();
				$out_of_date = version_compare($this->github_response['tag_name'], $checked[$this->basename], 'gt');
				if ($out_of_date) {
					$new_files = $this->github_response['zipball_url'];
					$slug = current(explode('/', $this->basename));
					$plugin = [
						'url' => $this->plugin["PluginURI"],
						'slug' => $slug,
						'package' => $new_files,
						'new_version' => $this->github_response['tag_name']
					];
					$transient->response[$this->basename] = (object)$plugin;
				}
			}
		}
		return $transient;
	}
	
	/**
	 * @param $result
	 * @param $action
	 * @param $args
	 * @return object
	 */
	public function plugin_popup($result, $action, $args)
	{
		if (!empty($args->slug)) {
			if ($args->slug == current(explode('/', $this->basename))) {
				$this->get_repository_info();
				
				$plugin = [
					'name' => $this->plugin["Name"],
					'slug' => $this->basename,
					'version' => $this->github_response['tag_name'],
					'author' => $this->plugin["AuthorName"],
					'author_profile' => $this->plugin["AuthorURI"],
					'last_updated' => $this->github_response['published_at'],
					'homepage' => $this->plugin["PluginURI"],
					'short_description' => $this->plugin["Description"],
					'sections' => [
						'Description' => $this->plugin["Description"],
						'Updates' => $this->github_response['body'],
					],
					'download_link' => $this->github_response['zipball_url']
				];
				error_log(print_r((object)$plugin, true));
				return (object)$plugin;
			}
		}
		return $result;
	}
	
	/**
	 * @param $response
	 * @param $hook_extra
	 * @param $result
	 * @return mixed
	 */
	public function after_install($response, $hook_extra, $result)
	{
		global $wp_filesystem;
		
		$install_directory = plugin_dir_path($this->file);
		$wp_filesystem->move($result['destination'], $install_directory);
		$result['destination'] = $install_directory;
		
		if ($this->active) {
			activate_plugin($this->basename);
		}
		return $result;
	}
	
}
