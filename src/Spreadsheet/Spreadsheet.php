<?php
declare(strict_types=1);

namespace DataCenter\Spreadsheet;

use Cake\Http\Exception\InternalErrorException;
use PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet as PhpOfficeSpreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

/**
 * Class Spreadsheet
 *
 * @package App\Spreadsheet
 * @property \PhpOffice\PhpSpreadsheet\Spreadsheet $objPHPExcel
 * @property array $columnTitles
 * @property int $currentRow
 * @property string $title
 */
class Spreadsheet
{
    protected array $columnTitles;
    protected int $currentRow;
    protected PhpOfficeSpreadsheet $objPHPExcel;
    protected string $title;

    /**
     * Spreadsheet constructor
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function __construct()
    {
        Cell::setValueBinder(new AdvancedValueBinder());
        $this->objPHPExcel = new PhpOfficeSpreadsheet();
        $this->objPHPExcel->setActiveSheetIndex(0);
        $this->setDefaultStyles();
        $this->currentRow = 1;
    }

    /**
     * Sets the width of spreadsheet cells
     *
     * @param array $widthsByColTitle array of column title => width, e.g. ['vs Local Area' => 9, ...]
     * @return $this
     */
    public function setCellWidth(array $widthsByColTitle = [])
    {
        foreach ($widthsByColTitle as $title => $width) {
            $colNum = array_search($title, $this->columnTitles);
            if ($colNum) {
                $widthsByColNum[$colNum] = $width;
            }
        }

        for ($n = 1; true; $n++) {
            $colLetter = $this->getColumnKey($n);
            if (isset($widthsByColNum[$n])) {
                $this->objPHPExcel
                    ->getActiveSheet()
                    ->getColumnDimension($colLetter)
                    ->setWidth($widthsByColNum[$n]);
            } else {
                $this->objPHPExcel
                    ->getActiveSheet()
                    ->getColumnDimension($colLetter)
                    ->setAutoSize(true);
            }
            if ($colLetter == $this->getLastColumnLetter()) {
                break;
            }
        }

        return $this;
    }

    /**
     * Returns the nth Excel-style column key (A, B, C, ... AA, AB, etc.)
     *
     * @param int $num Column number
     * @return string
     */
    public function getColumnKey(int $num): string
    {
        // Switch from one-indexed to zero-indexed
        $num--;

        $numeric = $num % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval($num / 26);
        if ($num2 > 0) {
            return $this->getColumnKey($num2) . $letter;
        } else {
            return $letter;
        }
    }

    /**
     * Returns the letter corresponding to the rightmost populated column
     *
     * @return string
     */
    protected function getLastColumnLetter(): string
    {
        $columnCount = count($this->columnTitles);

        return $this->getColumnKey($columnCount);
    }

    /**
     * Sets the default text styling for the spreadsheet
     *
     * @return void
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    protected function setDefaultStyles()
    {
        $this->objPHPExcel->getDefaultStyle()->getFont()
            ->setName('Arial')
            ->setSize(11);
    }

    /**
     * Sets the author metadata for this spreadsheet
     *
     * @param string $author Author of spreadsheet
     * @return $this
     */
    public function setAuthor(string $author)
    {
        $this->objPHPExcel->getProperties()
            ->setCreator($author)
            ->setLastModifiedBy($author);

        return $this;
    }

    /**
     * Sets the title metadata for this spreadsheet
     *
     * @param string|null $title Title of spreadsheet, defaults to $this->title
     * @return $this
     */
    public function setMetadataTitle(?string $title = null)
    {
        if (!$title) {
            $title = $this->title;
        }

        $this->objPHPExcel->getProperties()
            ->setTitle($title)
            ->setSubject($title);

        return $this;
    }

    /**
     * Sets the title property
     *
     * @param string $title Title
     * @return $this
     */
    public function setTitle(string $title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Gets the title property
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title that's displayed on the current sheet's tab
     *
     * @param string $title Sheet title
     * @param bool $useEllipsis Set to FALSE to cut off long titles at the limit instead of appending an ellipsis
     * @return $this
     */
    public function setActiveSheetTitle(string $title, bool $useEllipsis = true)
    {
        // Keep title within 31-character limit
        if (strlen($title) > 31) {
            $title = $useEllipsis ? substr($title, 0, 28) . '...' : substr($title, 0, 31);
        }

        $this->objPHPExcel->getActiveSheet()->setTitle($title);

        return $this;
    }

    /**
     * Sets the columnTitles property for this spreadsheet
     *
     * @param array $columnTitles Array of column titles
     * @return $this
     */
    public function setColumnTitles(array $columnTitles)
    {
        $this->columnTitles = $columnTitles;

        return $this;
    }

    /**
     * Returns this spreadsheet's PHPExcel object
     *
     * @return \PhpOffice\PhpSpreadsheet\Spreadsheet
     */
    public function get(): PhpOfficeSpreadsheet
    {
        return $this->objPHPExcel;
    }

    /**
     * Writes and styles a title at the top of the current sheet
     *
     * @param string $title Sheet title
     * @return $this
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function writeSheetTitle(string $title)
    {
        $this->write(1, $this->currentRow, $title);

        // Style title
        $this->objPHPExcel->getActiveSheet()->getStyle('A1:A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 24,
            ],
        ]);
        $lastCol = $this->getLastColumnLetter();
        $span = "A1:{$lastCol}1";
        $this->objPHPExcel->getActiveSheet()->mergeCells($span);

        return $this;
    }

    /**
     * Writes and styles a subtitle under the main title at the top of the current sheet
     *
     * @param string $subtitle Sheet subtitle
     * @return $this
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function writeSheetSubtitle(string $subtitle)
    {
        // Write
        $this->write(1, $this->currentRow, $subtitle);

        // Style
        $firstCell = 'A' . $this->currentRow;
        $lastCell = $this->getLastColumnLetter() . $this->currentRow;
        $this->objPHPExcel->getActiveSheet()->getStyle("$firstCell:$firstCell")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 18,
            ],
        ]);
        $this->objPHPExcel->getActiveSheet()->mergeCells("$firstCell:$lastCell");

        return $this;
    }

    /**
     * Writes a value to the spreadsheet
     *
     * @param int $colNum Column number
     * @param int $rowNum Row number
     * @param string $value Value
     * @return $this
     */
    protected function write(int $colNum, int $rowNum, string $value)
    {
        $this->objPHPExcel
            ->getActiveSheet()
            ->setCellValueByColumnAndRow($colNum, $rowNum, $value);

        return $this;
    }

    /**
     * Writes a series of values to the fields in the current row
     *
     * @param array $row Array of values to write
     * @return $this
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function writeRow(array $row)
    {
        foreach ($row as $columnNumber => $value) {
            // Switch from zero-indexed to one-indexed
            $columnNumber = isset($row[0]) ? $columnNumber + 1 : $columnNumber;

            if ($value === null) {
                continue;
            }

            // Percentage values
            if (strpos($value, '%') !== false) {
                $cell = $this->getColumnKey($columnNumber) . $this->currentRow;
                $this->objPHPExcel->getActiveSheet()->getCell($cell)->setValueExplicit(
                    $value,
                    DataType::TYPE_STRING
                );
                continue;
            }

            $this->write($columnNumber, $this->currentRow, $value);
        }

        return $this;
    }

    /**
     * Increments the currentRow property
     *
     * @return $this
     */
    public function nextRow()
    {
        $this->currentRow++;

        return $this;
    }

    /**
     * Applies styles to a specified span of cells in the current row,
     * or the entire row if $fromCol and $toCol are omitted
     *
     * @param array $styles Array of PHPExcel compatible style data
     * @param int $fromCol First column number
     * @param int|null $toCol Last column number
     * @return $this
     */
    public function styleRow(array $styles, int $fromCol = 1, ?int $toCol = null)
    {
        $fromCell = $this->getColumnKey($fromCol) . $this->currentRow;
        $toColLetter = $toCol === null ? $this->getLastColumnLetter() : $this->getColumnKey($toCol);
        $toCell = $toColLetter . $this->currentRow;

        $this->objPHPExcel->getActiveSheet()
            ->getStyle("$fromCell:$toCell")
            ->applyFromArray($styles);

        return $this;
    }

    /**
     * Styles column group headers
     *
     * @param array $boundarySets An array of arrays containing [first col number, last col number] column spans
     * @return $this
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function styleColGroupHeaders(array $boundarySets)
    {
        foreach ($boundarySets as $boundarySet) {
            $firstCol = $this->getColumnKey($boundarySet[1]);
            $lastCol = $this->getColumnKey($boundarySet[1]);
            $firstCell = $firstCol . $this->getCurrentRow();
            $lastCell = $lastCol . $this->getCurrentRow();
            $span = "$firstCell:$lastCell";

            $this->objPHPExcel->getActiveSheet()
                ->mergeCells($span)
                ->getStyle($span)
                ->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'top' => $this->getBorder(),
                        'left' => $this->getBorder(),
                        'right' => $this->getBorder(),
                        'bottom' => $this->getBorder('none'),
                    ],
                    'font' => ['bold' => true],
                ]);
        }

        return $this;
    }

    /**
     * Returns the PHPExcel definition for this spreadsheet's border
     *
     * @param string $style 'thin' or 'none'
     * @return array
     * @throws \Cake\Http\Exception\InternalErrorException
     */
    protected function getBorder(string $style = 'thin')
    {
        switch ($style) {
            case 'thin':
                return ['style' => Border::BORDER_THIN];
            case 'none':
                return ['style' => Border::BORDER_NONE];
        }

        throw new InternalErrorException(sprintf('Unrecognized border style "%s"', $style));
    }

    /**
     * Returns the value of the currentRow property
     *
     * @return int
     */
    public function getCurrentRow(): int
    {
        return $this->currentRow;
    }

    /**
     * Adds a default border to each specified side (left, right, outline, etc.)
     *
     * @param string|string[] $sides One or more sides
     * @param int $fromCol First column number
     * @param int|null $toCol Last column number
     * @return $this
     */
    public function applyBorders($sides, int $fromCol = 1, ?int $toCol = null)
    {
        foreach ((array)$sides as $side) {
            $styles = ['borders' => [$side => $this->getBorder()]];
            $this->styleRow($styles, $fromCol, $toCol);
        }

        return $this;
    }

    /**
     * Aligns the row (or specified span in this row) horizontally
     *
     * @param string $direction left, right, or center
     * @param int $fromCol First column number
     * @param int|null $toCol Last column number
     * @return $this
     */
    public function alignHorizontal(string $direction, int $fromCol = 1, ?int $toCol = null)
    {
        switch ($direction) {
            case 'left':
                $direction = Alignment::HORIZONTAL_LEFT;
                break;
            case 'right':
                $direction = Alignment::HORIZONTAL_RIGHT;
                break;
            case 'center':
                $direction = Alignment::HORIZONTAL_CENTER;
                break;
            default:
                throw new InternalErrorException('Unsupported alignment direction: ' . $direction);
        }

        $styles = ['alignment' => ['horizontal' => $direction]];
        $this->styleRow($styles, $fromCol, $toCol);

        return $this;
    }

    /**
     * Aligns the row (or specified span in this row) vertically
     *
     * @param string $direction top, bottom, or center
     * @param int $fromCol First column number
     * @param int|null $toCol Last column number
     * @return $this
     */
    public function alignVertical(string $direction, int $fromCol = 1, ?int $toCol = null)
    {
        switch ($direction) {
            case 'top':
                $direction = Alignment::VERTICAL_TOP;
                break;
            case 'bottom':
                $direction = Alignment::VERTICAL_BOTTOM;
                break;
            case 'center':
                $direction = Alignment::VERTICAL_CENTER;
                break;
            default:
                throw new InternalErrorException('Unsupported alignment direction: ' . $direction);
        }

        $styles = ['alignment' => ['vertical' => $direction]];
        $this->styleRow($styles, $fromCol, $toCol);

        return $this;
    }

    /**
     * Creates a new sheet, sets it as the active sheet, and resets the currentRow property
     *
     * @param string $title Title of new sheet
     * @return $this
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function newSheet(string $title)
    {
        $this->currentRow = 1;
        $this->objPHPExcel->createSheet(null)->setTitle($title);
        $this->objPHPExcel->setActiveSheetIndexByName($title);

        return $this;
    }

    /**
     * Removes the specified worksheet (defaults to first sheet if unspecified)
     *
     * @param int $index Index of sheet to be removed
     * @return $this
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function removeSheet(int $index = 0)
    {
        $this->objPHPExcel->removeSheetByIndex($index);

        return $this;
    }

    /**
     * Selects the first sheet in this workbook
     *
     * @return $this
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function selectFirstSheet()
    {
        $this->objPHPExcel->setActiveSheetIndex(0);

        return $this;
    }

    /**
     * Turns text wrapping on for the specified cell range, or whole row if unspecified
     *
     * @param int $fromCol Starting column index
     * @param int|null $toCol Ending column index
     * @return $this
     */
    public function setWrapText(int $fromCol = 1, ?int $toCol = null)
    {
        $fromCell = $this->getColumnKey($fromCol) . $this->currentRow;
        $toColLetter = $toCol === null ? $this->getLastColumnLetter() : $this->getColumnKey($toCol);
        $toCell = $toColLetter . $this->currentRow;

        $this->objPHPExcel
            ->getActiveSheet()
            ->getStyle("$fromCell:$toCell")
            ->getAlignment()
            ->setWrapText(true);

        return $this;
    }
}
