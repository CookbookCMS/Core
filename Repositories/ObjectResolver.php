<?php 
/*
 * This file is part of the Congraph package.
 *
 * (c) Nikola Plavšić <nikolaplavsic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Congraph\Core\Repositories;

use Congraph\Contracts\Core\ObjectResolverContract;
use Congraph\Core\Traits\MapperTrait;
use Illuminate\Contracts\Container\Container;

/**
 * Object resolver
 * 
 * Uses repository mapping to resolve objects by their type
 * 
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	Congraph/Core
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class ObjectResolver implements ObjectResolverContract
{

	use MapperTrait;

	/**
	 * Application container
	 * 
	 * @var Illuminate\Contracts\Container\Container
	 */
	protected $container;

	/**
	 * ObjectResolver constructor
	 * 
	 * @param Illuminate\ $db
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;
	}


	public function resolve($type, $ids, $include = [], $locale = null, $status = null)
	{
		$multiple = false;
		$method = 'fetch';
		$params = [$ids, $include, $locale, $status];

		if(is_array($ids))
		{
			$multiple = true;
			$method = 'get';
			$params = [
				[
					'id' => [
						'in' => $ids
					]
				], 0, 0, [], $include, $locale, $status
			];
		}

		return $this->resolveMapping($type, $params, 'default', $method);
	}

	public function resolveWithParams($type, $filter = [], $offset = 0, $limit = 0, $sort = [], $include = [], $locale = null, $status = null)
	{
		return $this->resolveMapping($type, [$filter, $offset, $limit, $sort, $include, $locale, $status], 'default', 'get');
	}


}