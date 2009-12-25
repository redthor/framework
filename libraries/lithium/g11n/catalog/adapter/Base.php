<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace lithium\g11n\catalog\adapter;

use \lithium\util\Set;

/**
 * The `Base` class is the foundation for all g11n catalog adapters.
 */
abstract class Base extends \lithium\core\Object {

	/**
	 * A cascade of categories supported. If re-defined in sub-classes
	 * contents are being merged.
	 *
	 * @var array
	 */
	protected $_categories = array(
		'inflection' => array(
			'plural'            => array('read' => false, 'write' => false),
			'singular'          => array('read' => false, 'write' => false),
			'uninflectedPlural' => array('read' => false, 'write' => false),
			'irregularPluar'    => array('read' => false, 'write' => false),
			'transliteration'   => array('read' => false, 'write' => false),
			'template'          => array('read' => false, 'write' => false)
		),
		'list'       => array(
			'language'          => array('read' => false, 'write' => false),
			'script'            => array('read' => false, 'write' => false),
			'territory'         => array('read' => false, 'write' => false),
			'timezone'          => array('read' => false, 'write' => false),
			'currency'          => array('read' => false, 'write' => false),
			'template'          => array('read' => false, 'write' => false)
		),
		'message'    => array(
			'page'              => array('read' => false, 'write' => false),
			'plural'            => array('read' => false, 'write' => false),
			'direction'         => array('read' => false, 'write' => false),
			'template'          => array('read' => false, 'write' => false)
		),
		'validation' => array(
			'phone'             => array('read' => false, 'write' => false),
			'postalCode'        => array('read' => false, 'write' => false),
			'ssn'               => array('read' => false, 'write' => false),
			'template'          => array('read' => false, 'write' => false)
	));

	/**
	 * Initializer.  Merges redefined categories.
	 *
	 * @return void
	 */
	protected function _init() {
		parent::_init();
		$properties = get_class_vars(__CLASS__);
		$this->_categories = Set::merge($properties['_categories'], $this->_categories);
	}

	/**
	 * Checks if an operation for a category is supported.
	 *
	 * @param string $category Dot-delimited category.
	 * @param string $operation Operation to check for. Either `'read'` or `'write'`.
	 * @return boolean `true` if operation is supported, otherwise `false`.
	 * @see lithium\g11n\catalog\adapter\Base::$_categories.
	 */
	public function isSupported($category, $operation) {
		$category = explode('.', $category, 2);
		return $this->_categories[$category[0]][$category[1]][$operation];
	}

	/**
	 * Reads data.
	 *
	 * @param string $category Dot-delimited category.
	 * @param string $locale A locale identifier.
	 * @param string $scope The scope for the current operation.
	 * @return mixed
	 * @see lithium\g11n\catalog\adapter\Base::$_categories.
	 */
	abstract public function read($category, $locale, $scope);

	/**
	 * Writes data.  Existing data is silently overwritten.
	 *
	 * @param string $category Dot-delimited category.
	 * @param string $locale A locale identifier.
	 * @param string $scope The scope for the current operation.
	 * @param mixed $data The data to write.
	 * @return boolean
	 * @see lithium\g11n\catalog\adapter\Base::$_categories.
	 */
	abstract public function write($category, $locale, $scope, $data);

	/**
	 * Formats a message item if neccessary.
	 *
	 * @param string $key The potential message ID.
	 * @param string|array $value The message value.
	 * @return array Message item formatted into internal/verbose format.
	 */
	protected function _formatMessageItem($key, $value) {
		if (!is_array($value) || !isset($value['translated'])) {
			return array('singularId' => $key, 'translated' => (array) $value);
		}
		return $value;
	}

	/**
	 * Merges a message item into given data.
	 *
	 * @param array $data Data to merge item into.
	 * @param array $item Item to merge into $data.
	 * @return void
	 */
	protected function _mergeMessageItem(&$data, $item) {
		$id = $item['singularId'];

		$defaults = array(
			'singularId' => null,
			'pluralId' => null,
			'translated' => array(),
			'fuzzy' => false,
			'comments' => array(),
			'occurrences' => array()
		);
		$item += $defaults;

		if (!isset($data[$id])) {
			$data[$id] = $item;
			return;
		}

		if ($data[$id]['pluralId'] === null) {
			$data[$id]['singularId'] = $item['singularId'];
			$data[$id]['pluralId'] = $item['pluralId'];
			$data[$id]['translated'] += $item['translated'];
		}
		if ($data[$id]['fuzzy'] === false) {
			$data[$id]['fuzzy'] = $item['fuzzy'];
		}
		$data[$id]['comments'] = array_merge($data[$id]['comments'], $item['comments']);
		$data[$id]['occurrences'] = array_merge($data[$id]['occurrences'], $item['occurrences']);
	}
}

?>