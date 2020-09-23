<?php
declare(strict_types=1);

namespace DataCenter\Test\TestCase\Command;

use Cake\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * DataCenter\Command\AppCommand Test Case
 *
 * @uses \DataCenter\Command\AppCommand
 */
class AppCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->useCommandRunner();
    }

    /**
     * Test parseMultipleIdString method
     *
     * @return void
     */
    public function testParseMultipleIdString(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test removeLeadingZeros method
     *
     * @return void
     */
    public function testRemoveLeadingZeros(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test getConfirmation method
     *
     * @return void
     */
    public function testGetConfirmation(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test getDuration method
     *
     * @return void
     */
    public function testGetDuration(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
