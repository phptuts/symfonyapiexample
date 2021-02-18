<?php


namespace App\Model;

/**
 * Interface ViewInterface
 * @package App\Model
 */
interface ViewInterface
{
    /**
     * This will return all the viewable fields for an entity in an array
     * @return array
     */
    public function view(): array;
}