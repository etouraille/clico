<?php


namespace App\Model;


class Variants
{
    public $variants = [];

    /**
     * @return mixed
     */
    public function getVariants()
    {
        return $this->variants;
    }

    /**
     * @param mixed $variants
     */
    public function setVariants($variants): void
    {
        $this->variants = $variants;
    }

    public function addVariant($variant) {
        $this->variants[] = $variant;
    }
}
