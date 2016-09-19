#Simple parser for search string

[![StyleCI](https://styleci.io/repos/68639834/shield?branch=master)](https://styleci.io/repos/68639834)

## Examples

**Search string:**
(key:value or key:value) and key:value

**Parser result:**
```
Group Object
(
    [prev:protected] => 
    [type:protected] => 
    [terms:protected] => Array
        (
            [0] => Group Object
                (
                    [prev:protected] => 
                    [type:protected] => parenthesis
                    [terms:protected] => Array
                        (
                            [0] => Term Object
                                (
                                    [key:protected] => key
                                    [value:protected] => value
                                )

                            [1] => Operator Object
                                (
                                    [type:protected] => or
                                )

                            [2] =>Term Object
                                (
                                    [key:protected] => key
                                    [value:protected] => value
                                )

                        )

                )

            [1] => Operator Object
                (
                    [type:protected] => and
                )

            [2] => Term Object
                (
                    [key:protected] => key
                    [value:protected] => value
                )

        )

)
```
