<?php

namespace Epesi\Core\System\Integration\Modules\Concerns;

use Illuminate\Support\Str;

trait HasModule
{
	/**
	 * Default module view class
	 * 
	 * @param string $alias
	 * @return string
	 */
	final public static function view($alias = null)
	{
		$class = self::namespace() . '\\' . ($alias? Str::studly($alias): (static::name() . 'View'));
		
		if ($alias) return $class;
		
		return static::$view?: $class;
	}
	
	/**
	 * Module alias
	 * 
	 * @return string
	 */
	final public static function alias()
	{
		if (is_a(static::class, \Epesi\Core\System\Integration\Modules\ModuleCore::class, true) && empty(static::$alias)) {
			throw new \Exception('Undefined alias in module core class ' . static::class);
		}
		
		return static::$alias?? static::module()::alias();
	}
	
	/**
	 * Module core class name
	 * 
	 * @return string
	 */
	public static function module()
	{
		return static::namespace() . '\\' . static::name() . 'Core';
	}
	
	/**
	 * Namespace of the module
	 *
	 * @return string
	 */
	final public static function namespace()
	{
		return join('\\', array_slice(explode('\\', static::class), 0, -1));
	}
	
	
	/**
	 * Base name of the module
	 *
	 * @return string
	 */
	final public static function name()
	{
		$names = array_slice(explode('\\', static::class), -2, -1);
		
		return $names? reset($names): '';
	}
	
	/**
	 * Path to the module directory.
	 *
	 * @return string path to the module directory
	 */
	final public static function path() {
		$reflection = new \ReflectionClass(static::class);
		
		return pathinfo($reflection->getFileName(), PATHINFO_DIRNAME);
	}
	
	/**
	 * Path to the module directory from installation base directory.
	 *
	 * @return string path to the module directory
	 */
	final public static function relativePath() {
		return str_ireplace(base_path() . DIRECTORY_SEPARATOR, '', static::path());
	}
	
	final public static function isSubModuleOf($moduleClass) {
		return static::parentNamespace() == $moduleClass::namespace();
	}
	
	final public static function parentNamespace() {
		return implode('\\', array_slice(explode('\\', static::namespace()), 0, -1));
	}
}
