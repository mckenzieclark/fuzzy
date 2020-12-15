<?php
/**
 * Fuzzy plugin for Craft CMS 3.x
 *
 * Fuzzy dates and events
 *
 * @link      https://github.com/mckenzieclark
 * @copyright Copyright (c) 2020 John Clark
 */

namespace mckenzieclark\fuzzytests\unit;

use Codeception\Test\Unit;
use UnitTester;
use Craft;
use mckenzieclark\fuzzy\Fuzzy;

/**
 * ExampleUnitTest
 *
 *
 * @author    John Clark
 * @package   Fuzzy
 * @since     1.0.0
 */
class ExampleUnitTest extends Unit
{
    // Properties
    // =========================================================================

    /**
     * @var UnitTester
     */
    protected $tester;

    // Public methods
    // =========================================================================

    // Tests
    // =========================================================================

    /**
     *
     */
    public function testPluginInstance()
    {
        $this->assertInstanceOf(
            Fuzzy::class,
            Fuzzy::$plugin
        );
    }

    /**
     *
     */
    public function testCraftEdition()
    {
        Craft::$app->setEdition(Craft::Pro);

        $this->assertSame(
            Craft::Pro,
            Craft::$app->getEdition()
        );
    }
}
