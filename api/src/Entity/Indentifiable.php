<?php


namespace App\Entity;


use Ramsey\Uuid\UuidInterface;

interface Indentifiable
{
   public function getUuid() : string;
   public function getId(): ?int;
   public function setUuid(string $uid);
}
