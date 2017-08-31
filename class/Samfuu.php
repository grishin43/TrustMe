<?php

function logging($login, $report, $foo_name)
{
    $name = "../log/" . date("Y-m-d").".log";

    $test = "Parameters: " . $login .
        "\nFunction name: " .$foo_name.
        "\nResponse text: " . $report.
        "\nDate: " . date(DATE_RSS) .
        "\n----------------------------------------------------------\n";

    file_put_contents($name, $test, FILE_APPEND);
}
