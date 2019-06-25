<?php

use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\TextareaField;

class TableCell extends DataObject
{

    private static $db = [
        'Content' => 'Varchar(100)',
        'Sort' => 'Int'
    ];

    private static $summary_fields = [
        'Content' => 'Content',
        'RowName' => 'Row Name'
    ];

    private static $has_one = [
        'TableColumn' => TableColumn::class
    ];

    private static $singular_name = "Cell";
    private static $plural_name = "Cells";

    public function canCreate($member = null, $context = array())
    {
        if (TableCell::get()->count() === TableRow::get()->count()) {
            return false;
        }

        return true;
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName("TableColumnID");
        $fields->removeByName("Sort");
        $fields->removeByName("Content");
        $fields->dataFieldByName('new-record');

        $description = 'Column reference - <strong>'. $this->TableColumn()->Heading . '</strong> -- Row reference - <strong>' . $this->getRelatedRowName() . '</strong>';
        $fields->addFieldToTab('Root.Main', TextareaField::create('Content', 'Content')->setDescription($description));

        return $fields;
    }

    public function getRelatedRowName()
    {
        if ($this->isInDB()) {
            if ($this->Sort == 0) {
                return null;
            }
            $index = $this->Sort - 1;
        } else {
            $index = $this->TableColumn()->TableCells()->count();
        }

        if ($index === null) {
            return null;
        }

        $rows = $this->TableColumn()->ElContentTable()->TableRows();

        return $rows[$index]->Name;
    }

    public function getRowName()
    {
        $rows = $this->TableColumn()->ElContentTable()->TableRows();

        foreach ($this->TableColumn()->TableCells() as $key => $value) {
            # code...
            if ($value->ID === $this->ID) {
                return $rows[$value->Sort - 1]->Name;
            }
        }
    }
}
