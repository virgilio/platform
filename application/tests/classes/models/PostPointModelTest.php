<?php defined('SYSPATH') or die('No direct script access allowed.');

/**
 * Unit tests for the post model
 *
 * @author     Ushahidi Team <team@ushahidi.com>
 * @package    Ushahidi\Application\Tests
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License Version 3 (GPLv3)
 */

class PostPointModelTest extends Unittest_Database_TestCase {

	public function setUp()
	{
		parent::setUp();

		// Hack to insert post_point value with POINT() data
		$pdo_connection = $this->getConnection()->getConnection();
		$pdo_connection->query("INSERT INTO `post_point` (`id`, `post_id`, `form_attribute_id`, `value`)
			VALUES (1, 1, 1, POINT(12.123, 21.213));");
	}

	/**
	 * Get data set PostPointModel
	 *
	 * @return PHPUnit_Extensions_Database_DataSet_IDataSet
	 */
	public function getDataSet()
	{
		return new PHPUnit_Extensions_Database_DataSet_YamlDataSet(
			Kohana::find_file('tests/datasets', 'ushahidi/PostPointModel', 'yml')
		);
	}

	/**
	 * Provider for test_save_point
	 *
	 * @access public
	 * @return array
	 */
	public function provider_save_point()
	{
		return array(
			array(
				// Valid point data
				array(
					'post_id' => 1,
					'form_attribute_id' => 1,
					'value' => array('lat' => 0, 'lon' => 1),
				)
			),
			array(
				// Valid point data
				array(
					'post_id' => 1,
					'form_attribute_id' => 1,
					'value' => array('lat' => 90, 'lon' => 180),
				)
			)
		);
	}

	/**
	 * Test Saving MySQL Point Data
	 *
	 * @dataProvider provider_save_point
	 * @param array $set
	 * @return void
	 */
	public function test_save_point($set)
	{
		$point = ORM::factory('Post_Point');
		$point->values($set);

		$point->check();
		$point->save();

		// ID should be an int greater than 0
		$this->assertTrue($point->saved());
		$this->assertInternalType('int', $point->id);
		$this->assertGreaterThan(0, $point->id);
		$this->assertEquals($set['value']['lon'], $point->value['lon']);
		$this->assertEquals($set['value']['lat'], $point->value['lat']);
	}

	/**
	 * Test Saving MySQL Point Data
	 *
	 * @param array $set
	 * @return void
	 */
	public function test_load_point()
	{
		$point = ORM::factory('Post_Point', 1);

		// ID should be an int greater than 0
		$this->assertInternalType('array', $point->value);
		$this->assertEquals(12.123, $point->value['lon']);
		$this->assertEquals(21.213, $point->value['lat']);
	}

	/**
	 * Test Saving MySQL Point Data
	 *
	 * @dataProvider provider_save_point
	 * @param array $set
	 * @return void
	 */
	public function test_update_point($set)
	{
		$point = ORM::factory('Post_Point', 1);
		$point->values($set);

		$point->check();
		$point->save();

		// ID should be an int greater than 0
		$this->assertTrue($point->saved());
		$this->assertEquals($set['value']['lon'], $point->value['lon']);
		$this->assertEquals($set['value']['lat'], $point->value['lat']);
	}
}