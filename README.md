#Simple parser for search string

[![StyleCI](https://styleci.io/repos/68639834/shield?branch=master)](https://styleci.io/repos/68639834)

## Examples

**Search string:**
(key:value or key:value) and key:value

**Parser result:**
```
Array
(
    [0] => WrongWare\SearchParser\Group Object
        (
            [prev:protected] => 
            [type:protected] => and
            [terms:protected] => Array
                (
                    [0] => WrongWare\SearchParser\Group Object
                        (
                            [prev:protected] => 
                            [type:protected] => or
                            [terms:protected] => Array
                                (
                                    [0] => WrongWare\SearchParser\Term Object
                                        (
                                            [key:protected] => key
                                            [value:protected] => value
                                        )

                                    [1] => WrongWare\SearchParser\Term Object
                                        (
                                            [key:protected] => key
                                            [value:protected] => value
                                        )

                                )

                        )

                    [1] => WrongWare\SearchParser\Term Object
                        (
                            [key:protected] => key
                            [value:protected] => value
                        )

                )

        )

)
```
