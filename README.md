## Composer

```.yml
  "require": {
    "dnadesign/silverstripe-elemental-responsivetable": "1.0"
  },
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/dnadesign/silverstripe-elemental-responsivetable.git"
    }
  ]
```

## Requirements

Silverstripe 4.0
Silverstripe Elemental 4.3

## Extending/Theming

You can extend the element to include themes.

```.yml
DNADesign\Elemental\Models\ElementResponsiveTable:
  extensions:
    - 'CustomResponsiveTableExtension'
```

```.php
<?php

use SilverStripe\Forms\DropdownField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\FieldList;

class CustomResponsiveTableExtension extends DataExtension
{
    private static $db = [
        'TableTheme' => 'Enum(array("Blue", "Green", "Light Blue", "Gray"), "Blue")'
    ];

    public function updateCMSFields(FieldList $fields)
    {
        if ($this->owner->isInDB()) {
            $fields->addFieldToTab(
                'Root.Main',
                DropdownField::create('TableTheme', 'Theme', $this->owner->dbObject('TableTheme')->enumValues())
                    ->setRightTitle('Theme colour for column headings')
            );
        } else {
            $fields->removeByName('TableTheme');
        }

        return $fields;
    }

    public function getTheme()
    {
        switch ($this->owner->TableTheme) {
            case 'Blue':
                return 'theme1';
                break;
            case 'Green':
                return 'theme2';
                break;
            case 'Light Blue':
                return 'theme3';
                break;
            default:
                return 'theme4';
                break;
        }
    }
}
```

```.scss
@mixin ColumnNameTheme($theme) {
    .responsive__table__column--name {
        background-color: $theme;

        &:nth-of-type(2n) {
            background-color: $theme;
        }

        &:nth-of-type(3n) {
            background-color: lighten($theme, 3%);
        }

        &:nth-of-type(4n) {
            background-color: lighten($theme, 6%);
        }
    }
}

.responsive__table__theme-- {
    &theme1 {
        @include ColumnNameTheme($brand-example-1);
    }

    &theme2 {
        @include ColumnNameTheme($brand-example-2);
    }
}
```

```.ss
<% if $TableRows && not NoCells %>
<div class="responsive__table__theme--$Theme()">
    <div class="responsive__table__responsive">
        <% if not $HideTitle %>
        <h3 class="responsive__table__title">$Title</h3>
        <% end_if %>
        <% include Includes\ResponsiveTableStandard %>
        <% include Includes\ResponsiveTableAccordion %>
        <div class="responsive__table__disclaimer">
            <span>$ExtraInfo</span>
        </div>
    </div>
</div>
<% end_if %>
```
