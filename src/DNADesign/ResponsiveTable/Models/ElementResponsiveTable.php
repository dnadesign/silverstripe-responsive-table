<?php

namespace DNADesign\Elemental\Models;

use DNADesign\Elemental\Models\BaseElement;
use DNADesign\Elemental\Controllers\ResponsiveTableController;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use SilverStripe\View\ArrayData;
use SilverStripe\ORM\ArrayList;
use SilverStripe\Forms\GridField\GridField;

/**
 * @package elemental
 */
class ElementResponsiveTable extends BaseElement
{
  private static $title = "Responsive Table";

  private static $description = "A custom responsive table";

  private static $table_name = 'ElementResponsiveTable';

  private static $singular_name = 'Responsive Table';

  private static $plural_name = 'Responsive Tables';

  private static $controller_class = ResponsiveTableController::class;

  private static $icon = 'font-icon-checklist';

  private static $db = [
    'HideAllRowNames' => 'Boolean',
    'HideAllColumnHeading' => 'Boolean',
    'DisableMobileAccordion' => 'Boolean',
    'ExtraInfo' => 'Varchar(255)',
    // 'TableTheme' => 'Enum(array("Interislander", "Northern Explorer", "Coastal Pacific", "TranzAlpine"), "Interislander")'
  ];

  private static $has_many = [
    'TableRows' => TableRow::class,
    'TableColumns' => TableColumn::class
  ];

  public function getCMSFields()
  {
    $fields = parent::getCMSFields();

    $tableColumnsGrid = $fields->dataFieldByName('TableColumns');
    $tableRowsGrid = $fields->dataFieldByName('TableRows');

    $fields->removeByName('TableColumns');
    $fields->removeByName('TableRows');
    $fields->removeByName('ClassNameTranslated');

    $fields->dataFieldByName('Title')->setDescription('Table title in template');

    if ($this->isInDB()) {
      $tableColumnsGrid->getConfig()->addComponent(GridFieldOrderableRows::create('Sort'));
      $tableColumnsGrid->getConfig()->removeComponentsByType(GridFieldAddExistingAutocompleter::class);

      $fields->addFieldsToTab('Root.Main', [
        CheckboxField::create('HideAllRowNames', 'Hide all row names'),
        CheckboxField::create('HideAllColumnHeading', 'Hide all column headings'),
        CheckboxField::create('DisableMobileAccordion', 'Disable mobile accordion'),
        // DropdownField::create('TableTheme', 'Theme', $this->dbObject('TableTheme')->enumValues())
        //   ->setRightTitle('Theme colour for column headings'),
        GridField::create('TableRows', 'Rows', $this->TableRows(), $tableRowsGrid->getConfig()),
        GridField::create('TableColumns', 'Columns', $this->TableColumns(), $tableColumnsGrid->getConfig()),
        TextField::create('ExtraInfo')->setRightTitle('Content displayed below table')
      ]);
    } else {
      $fields->removeByName('HideAllRowNames');
      $fields->removeByName('HideAllColumnHeading');
      $fields->removeByName('DisableMobileAccordion');
      // $fields->removeByName('TableTheme');
      $fields->removeByName('ExtraInfo');
      $warning = LiteralField::create('warning', '<span class="message warning">Please save your table before adding content.</span>');
      $fields->addFieldToTab('Root.Main', $warning);
    }

    return $fields;
  }

  public function getStandardTable()
  {
    $rows = $this->TableRows();
    $table = $this->columnHeadingsRow();
    $columnCount = $this->TableColumns()->count();

    #loop through row names
    foreach ($rows as $rowKey => $rowName) {
      #create new row
      $row = new ArrayList();
      #push row name into new row
      $row->push(new ArrayData(['Value' => $rowName->Name]));

      #loop through Columns
      foreach ($this->TableColumns()->sort('Sort') as $columnKey => $column) {
        #loop through Column Cells
        foreach ($column->TableCells()->sort('Sort') as $cellKey => $cellValue) {
          # only include cells that are with same index of row
          if ($cellKey === $rowKey) {
            $row->push(new ArrayData(['Value' => $cellValue->Content, 'RowName' => $rowName->Name]));
          }
        }
      }

      $cellDiff = $columnCount - ($row->count() - 1);

      if($columnCount > 0 && $cellDiff <= $columnCount) {
        if ($cellDiff > 0 && $row->count() > 1) {
          for ($i = 0; $i < $cellDiff; $i++) {
            $row->push(new ArrayData(['Value' => '', 'RowName' => $rowName->Name]));
          }
        }
      }
      if ($row->count() > 1) {
        $table->push(new ArrayData(['Row' => $row]));
      }
    }

    return $table;
  }

  public function getAccordionTable()
  {
    $rowNames = $this->TableRows();
    $table = new ArrayList();

    #loop through Columns
    foreach ($this->TableColumns()->sort('Sort') as $columnKey => $column) {
      #create new row
      $row = new ArrayList();
      # including row with column headings...
      $row->push(new ArrayData(['Heading' => $column->Heading]));
      #loop through Column Cells
      foreach ($column->TableCells()->sort('Sort') as $cellKey => $cellValue) {
        # only include cells that are with same index of row
        $row->push(new ArrayData(['Value' => $cellValue->Content, 'RowName' => $rowNames[$cellKey]->Name]));
      }
      $table->push(new ArrayData(['Row' => $row]));
    }
    return $table;
  }

  public function columnHeadingsRow()
  {
    $table = new ArrayList();
    $row = new ArrayList();

    #pushing first value as empty to account for row name column
    $row->push(new ArrayData(['Value' => '']));
    foreach ($this->TableColumns() as $columnKey => $column) {
      # including row with column headings...
      $row->push(new ArrayData(['Value' => $column->Heading]));
    }
    $table->push(new ArrayData(['Row' => $row]));

    return $table;
  }

  public function getNoCells()
  {
    $cellCount = 0;
    foreach ($this->TableColumns() as $columnKey => $column) {
      # including row with column headings...
      $cellCount = $cellCount + $column->TableCells()->Count();
    }

    if ($cellCount === 0) {
      return true;
    }

    return false;
  }

  public function getType()
  {
    return _t(__class__ . '.BlockType', 'Responsive Table');
  }

  public function inlineEditable()
  {
    return false;
  }
}
