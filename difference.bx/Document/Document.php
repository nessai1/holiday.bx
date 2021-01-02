<?php

interface Document {
    public function getSize() : int;
    public function getLine(int $index) : ?string;
}

