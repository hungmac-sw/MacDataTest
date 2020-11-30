# Create demo data for Shopware 6

This plugin is supporting to add more data test to your shop with the number of items you want, It helpful for you test performance or something like that

## Installation

* Download the latest release
* Extract the zip file in `shopware_folder/custom/plugins/`

## Command

```
bin/console mac:datatest [ENTITY_NAME] [NUMBER_OF_ITEMS]
```
ENTITY_NAME: the name of the entity

NUMBER_OF_ITEMS: the number of items you want to add

For example: 
```
bin/console mac:datatest product 500
```

we're supporting for the entities
- media
- customer
- property_group
- category
- product_manufacturer
- product
- product_stream
- order
- product_review
- mail_template
- mail_header_footer
