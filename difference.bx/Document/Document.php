<?php

interface Document {
    public function getSize() : int;
    public function getLine(int $index) : ?string;
    public function getName() : string;
    public function setName(string $name) : void;
}

