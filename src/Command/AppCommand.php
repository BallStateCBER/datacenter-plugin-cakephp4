<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\I18n\Time;
use Exception;

/**
 * AppCommand class
 *
 * This is a base class for other Data Center commands to extend from. It defines some common methods that may be needed
 * by multiple projects.
 */
class AppCommand extends Command
{
    protected $progress;
    protected $io;

    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/4/en/console-commands/commands.html#defining-arguments-and-options
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);

        return $parser;
    }

    /**
     * Sets class properties
     *
     * Be sure to call parent::execute() in any class that extends AppCommand to take advantage of this
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return void
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $this->io = $io;
        $this->progress = $this->io->helper('Progress');
    }

    /**
     * Creates a progress bar, draws it, and returns it
     *
     * @param int $total Total number of items to be processed
     * @return void
     */
    protected function makeProgressBar(int $total)
    {
        $this->progress = $this->io->helper('Progress');
        $this->progress->init([
            'total' => $total,
            'width' => 60,
        ]);
        $this->progress->draw();
    }

    /**
     * Takes a string of IDs and returns an array
     *
     * @param string $string String of IDs and ID ranges (e.g. 1,2,3,5-7)
     * @return int[]
     * @throws \Exception
     */
    protected function parseMultipleIdString(string $string)
    {
        $ids = [];

        foreach (explode(',', $string) as $range) {
            $dashCount = substr_count($range, '-');

            // Single ID
            if (!$dashCount) {
                if (!is_numeric($range)) {
                    throw new Exception('Invalid ID: ' . $range);
                }
                $ids[] = (int)$range;
                continue;
            }

            // Range of IDs
            if ($dashCount == 1) {
                [$rangeStart, $rangeEnd] = explode('-', $range);
                foreach ([$rangeStart, $rangeEnd] as $id) {
                    if (!is_numeric($id)) {
                        throw new Exception('Invalid ID: ' . $id);
                    }
                }
                $ids = array_merge($ids, range((int)$rangeStart, (int)$rangeEnd));
                continue;
            }

            throw new Exception('Invalid range: ' . $range);
        }

        return $ids;
    }

    /**
     * Strips out leading zeros from a string
     *
     * @param string $string String to remove leading zeros from
     * @return string
     */
    protected function removeLeadingZeros($string)
    {
        return ltrim($string, '0');
    }

    /**
     * Displays a message and a prompt for a 'y' or 'n' response and returns TRUE if response is 'y'
     *
     * @param string $msg Message to display
     * @param string $default Default selection (leave blank for 'y')
     * @return bool
     */
    protected function getConfirmation($msg, $default = 'y')
    {
        return $this->io->askChoice(
                $msg,
                ['y', 'n'],
                $default
            ) == 'y';
    }

    /**
     * Returns a string describing the duration of time elapsed since $start
     *
     * @param int $start Timestamp
     * @return string
     */
    protected function getDuration(int $start)
    {
        $end = time();

        if ($start == $end) {
            return '(took < 1 second)';
        }

        $duration = Time::createFromTimestamp($start)->timeAgoInWords();

        return sprintf(
            '(took %s)',
            str_replace(' ago', '', $duration)
        );
    }
}
