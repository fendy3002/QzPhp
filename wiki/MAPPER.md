# QzPhp Mapper

Generate php object to object mapper using JSON specification

## Contents
1. [Schema](#schema)

    a. [fields](#schema_fields)

2. [Converter](#converter)
  
    a. [fields](#converter_fields)
    b. [data](#converter_data)
    c. [type: array / object](#converter_array_object)
    d. [object field](#converter_field_fields)
    e. [schema](#converter_schema)
    f. [key](#converter_key)
    g. [ref link](#converter_ref_link)

<a name="schema"></a>
## Schema

    {
        "Version": 1.0,
        "Generated\\Models\\Person": {
            "folder": "Models",
            "fields":{
                "name": "string",
                "notAssigned": "string",
                "birth": "datetime",

                "phones": "string[]",
                "educations": "object[]",
                "defaultEducation" : "object",

                "mother": "Models\\Person",
                "addresses": "Models\\Address[]"
            }
        }
    }

<a name="schema_fields"></a>
## fields

Schema fields currently used mainly as documentation.

<a name="converter"></a>
## Converter

    {
        "Version": 1.0,
        "Generated\\Converter\\Person1": {
            
            "className": "Models\\Person",
            "folder": "Converter",
            "fields":{
                "name": "",
                "notAssigned": "::null"
            }
        }
    }

<a name="converter_fields"></a>
### fields

Converter fields are key-value JSON, with key being the target name, and value being the source name.

If the value is string, the value will refer as source object's field name. Example:

    "name" : "someField"
    // equals $target->name = $source->someField

If the value is empty string, the source field will use key / target field.

    "someUniqueField" : ""
    // equals $target->someUniqueField = $source->someUniqueField

If the value is ::null, the field won't be assigned. Example:

    "someUniqueField" : "::null"
    // will be skipped

If the value is object, it will use other rule and based on data provided, which will be explained in next section. Example:

    "someUniqueField: {
        "type": "object",
        //...
    }

<a name="converter_array_data"></a>
### data

Many times we want to map objects with array based on relationship, such as one to many or one to one. The supporting data can be passed to converter object's second parameter, with format as key value array. For example: 

    $converter = new \Generated\Converter\Person1();
    $converted = $converter->convert($persons, [
        "educations" => $educations,
        "phones" => [$homePhone, $workPhone]
    ]);

The key in array equals to converter's field. For example, the above code can be used by the converter spec below:

    "fields": {
        "educations" : {
            "type": "array"
        },
        "phones": {
            "type": "array"
        }
    }

<a name="converter_array_object"></a>
### type: array / object

If the value has type with value `object`, it will be mapped as one object (will get the first index). If the type value is `array`, it will be mapped as array. Example:

    "someUniqueField: {
        "type": "object",
        //...
    }
    // equals: $target->someUniqueField = $data['someUniqueField'][0]

    "someUniqueField: {
        "type": "array",
        //...
    }
    // equals: $target->someUniqueField = $data['someUniqueField']

Data passed to field need to be in array format, for example:

    $converter = new \Generated\Converter\Person1();
    $converted = $converter->convert($persons, [
        "someUniqueField" => [$valueA, $valueB]
    ]);

<a name="converter_value"></a>
### value

Source data:

    [
        (object)["name" => "a", "address" => "b"],
        (object)["name" => "a1", "address" => "b1"]
    ]

Schema:

    "someUniqueField": {
        "type": "array",
        "value": "name"
    }

Result:

    $target->someUniqueField = [
        "a",
        "a1"
    ];

<a name="converter_field_fields"></a>
### object field

Source data:

    [
        (object)["name" => "a", "address" => "b", "phone" => "c"],
        (object)["name" => "a1", "address" => "b1", "phone" => "c1"]
    ]

Schema:

    "someUniqueField": {
        "type": "array",
        "field": {
            "name": "",
            "street_address": "address"
        }
    }

Result:

    $target->someUniqueField = [
        (object)["name" => "a", "address" => "b"],
        (object)["name" => "a1", "address" => "b1"]
    ];

<a name="converter_schema"></a>
### schema

You can use another converter in converter. This way you can easily define the converter modular-ly. For example:

    "someUniqueField": {
        "type": "array",
        "schema": "Generated\\Converter\\Vehicle1"
    }

More or less will be translated as:

    $target->someUniqueField = $_someUniqueFieldConverter->convert($data['someUniqueField'], $data['someUniqueField_additional']);

When using schema field, you can support additional data for the child converter by suffix it with `_additional`. Example:

    $converted = $converter->convert(
        $person,
        [
            "vehicle" => $vehicles,
            "vehicle_additional" => [
                "parts" => $vehicleParts,
                "parts_additional" => [
                    "vendor" => $partVendors
                ]
            ]
        ]
    );


<a name="converter_key"></a>
### key

If somehow you need to filter the mapping object by target field, you can use `key` attribute. This works for one to many relationship. Example:

    "subordinates": {
        "type" : "array",
        "schema" : "Generated\\Converter\\Person1",
        "key" : {
            "person_id": "supervisor_id",
            "division": "division"
        }
    }

More or less will perform like following:

    //data
    $source = (object)["person_id" => 1, "division" => "finance"]

    $data['subordinates'] = [
        (object)["supervisor_id" => 1, "division" => "finance", "name" => "a"],
        (object)["supervisor_id" => 1, "division" => "accounting", "name" => "b"],
        (object)["supervisor_id" => 2, "division" => "finance", "name" => "c"]
    ]

    // in-converter
    $mapped = [];
    foreach($data['subordinates'] as $obj){
        if($obj->supervisor_id == $source->person_id &&
        $obj->division == $source->division){
            $mapped[] = $obj
        }
    }
    $target->subordinates = $_subordinates_converter->convert($mapped, $data['subordinates_additional']);

<a name="converter_ref_link"></a>
### ref link

Still in beta. Used when the relations is handled in third value, e.g: third / detail table. For example:

    $persons = [/* person data */];
    $orgHierarchy = [
        (object)[
            "employee_id" => 2,
            "supervisor_id" => 1
        ],
        (object)[
            "employee_id" => 3,
            "supervisor_id" => 1
        ],
        (object)[
            "employee_id" => 4,
            "supervisor_id" => 2
        ],
    ];

The converter schema:

    "subordinates": {
        "ref": true,
        "link": {
            "from": {
                "person_id": "supervisor_id"
            },
            "with": {
                "employee_id": "person_id"
            }
        }
    }

It can be used by passing the `link` in data with suffix `_link`, as follow:

    $converted = $converter->convert(
        $person,
        [
            "subordinates" => $person,
            "subordinates_link" => $orgHierarchy
        ]
    );

