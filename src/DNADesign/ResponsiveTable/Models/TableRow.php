<?php

use SilverStripe\ORM\DataObject;

class TableRow extends DataObject
{

    private static $db = [
        'Name' => 'Varchar(100)'
    ];

    private static $has_one = [
        'ElContentTable' => ElContentTable::class
    ];

    private static $summary_fields = [
        'Name' => 'Name'
    ];

    private static $singular_name = "Row";
    private static $plural_name = "Rows";

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName('ElContentTableID');

        return $fields;
    }
}
