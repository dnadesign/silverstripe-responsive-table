<?php

namespace DNADesign\Elemental\Models;

use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\GridField\GridField;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;

class TableColumn extends DataObject
{

  private static $db = [
    'Heading' => 'Varchar(100)',
    'Sort' => 'Int'
  ];

  private static $has_one = [
    'ElementResponsiveTable' => ElementResponsiveTable::class
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

    $tableCells = $fields->dataFieldByName("TableCells");

    $fields->removeByName("ElementResponsiveTableID");
    $fields->removeByName("TableCells");
    $fields->removeByName("Sort");

    if ($this->isInDB()) {
      $tableCellsGridConfig = $tableCells->getConfig();
      $tableCellsGridConfig->removeComponentsByType(GridFieldAddExistingAutocompleter::class);

      if ($this->TableCells()->count() === $this->ElementResponsiveTable()->TableRows()->count()) {
        $tableCellsGridConfig->removeComponentsByType(GridFieldAddNewButton::class);
      }

      $tableCellsGridConfig->addComponent(GridFieldOrderableRows::create('Sort'));

      $tableCells->setDescription('This is limited to the number of row headings');
      $fields->addFieldToTab('Root.Main', $tableCells);
    } else {
      $warning = LiteralField::create('warning', '<span class="message warning">Please save your column before adding cells.</span>');
      $fields->addFieldToTab('Root.Main', $warning);
    }

    return $fields;
  }

  protected function onBeforeWrite()
  {
    if (!$this->Sort) {
      $this->Sort = TableColumn::get()->filter(['ElementResponsiveTableID' => $this->ElementResponsiveTable()->ID])->max('Sort') + 1;
    }

    parent::onBeforeWrite();
  }

  public function getCellsCount()
  {
    return $this->TableCells()->Count();
  }
}
