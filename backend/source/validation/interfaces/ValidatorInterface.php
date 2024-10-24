<?php
namespace Palmo\source\validation\interfaces;

interface ValidatorInterface
{
    public function validate($value): bool;

    public function getError(): string;
}