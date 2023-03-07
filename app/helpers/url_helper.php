<?php
//  Page redirect function
function redirect($page)
{
    header('location: ' . URLROOT . '/' . $page);
}