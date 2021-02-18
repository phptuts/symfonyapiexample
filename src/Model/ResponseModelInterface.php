<?php


namespace App\Model;

/**
 * Interface ResponseModelInterface
 * @package App\Model
 */
interface ResponseModelInterface
{
    /**
     * This will return an associative array that will be serialized by the frontend.
     *
     * @return array
     */
    public function toArray(): array;
}