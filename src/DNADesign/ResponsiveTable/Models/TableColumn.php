<?php

use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use UndefinedOffset\SortableGridField\Forms\GridFieldSortableRows;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;

class TableColumn extends DataObject
{

    private static $db = [
        'Heading' => 'Varchar(100)',
        'Sort' => 'Int'
    ];

    private static $has_one = [
        'ElContentTable' => ElContentTable::class
    ];

    private static $has_many = [
        'TableCells' => TableCell::class
    ];

    private static $summary_fields = [
        'Heading' => 'Heading',
        'CellsCount' => 'Cells'
    ];

    private static $singular_name = "Column";
    private static $plural_name = "Columns";

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName("ElContentTableID");
        $fields->removeByName("TableCells");
        $fields->removeByName("Sort");

        if ($this->isInDB()) {
            $tableCellsGrid = GridFieldConfig_RecordEditor::create();

            if (TableCell::get()->count() === TableRow::get()->count()) {
                $tableCellsGrid->removeComponentsByType(GridFieldAddNewButton::class);
                $tableCellsGrid->removeComponentsByType(GridFieldAddExistingAutocompleter::class);
            }

            $tableCellsGrid->addComponent(new GridFieldSortableRows('Sort'));

            $grid = GridField::create('TableCells', 'Cells', $this->TableCells(), $tableCellsGrid)->setRightTitle('This is limited to the number of row headings');
            $fields->addFieldToTab('Root.Main', $grid);
        } else {
            $warning = LiteralField::create('warning', '<span class="message warning">Please save your column before adding cells.</span>');
            $fields->addFieldToTab('Root.Main', $warning);
        }

        return $fields;
    }

    public function getCellsCount()
    {
        return $this->TableCells()->Count();
    }
}
