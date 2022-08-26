<?php

function mail_path(string $path = '') {
    $path = $path[0] == '/' ? substr($path, 1) : $path;
    return resource_path("views\\mail\\$path");
}