<?php

namespace DNADesign\Elemental\Models;

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
    if (!empty($context)) {
      if ($context['Parent']->TableCells()->count() === $context['Parent']->ElementResponsiveTable()->TableRows()->count()) {
        return false;
      }
    } else {
      if ($this->TableColumn()->TableCells()->count() === $this->TableColumn()->ElementResponsiveTable()->TableRows()->count()) {
        return false;
      }
    }

    return true;
  }

  protected function onBeforeWrite()
  {
    if (!$this->Sort) {
      $this->Sort = TableCell::get()->filter(['TableColumnID' => $this->TableColumn()->ID])->max('Sort') + 1;
    }

    parent::onBeforeWrite();
  }

  public function getCMSFields()
  {
    $fields = parent::getCMSFields();

    $fields->removeByName("TableColumnID");
    $fields->removeByName("Sort");
    $fields->removeByName("Content");

    $description = 'Column reference - <strong>' . $this->TableColumn()->Heading . '</strong> -- Row reference - <strong>' . $this->getRelatedRowName() . '</strong>';
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

    $rows = $this->TableColumn()->ElementResponsiveTable()->TableRows();

    return $rows[$index] ? $rows[$index]->Name : null;
  }

  public function getRowName()
  {
    $rows = $this->TableColumn()->ElementResponsiveTable()->TableRows();
    if ($rows) {
      foreach ($this->TableColumn()->TableCells() as $key => $value) {
        # code...
        if ($value->ID === $this->ID) {
          if ($rows[$value->Sort - 1]) {
            return $rows[$value->Sort - 1]->Name;
          }
        }
      }
    }
  }
}
