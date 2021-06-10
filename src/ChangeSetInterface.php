<?php


namespace Homeapp\AuditBundle;


interface ChangeSetInterface
{
    public function forCreate(object $entity):array;
}