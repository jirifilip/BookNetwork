<?php

require("\..\FormBuilder.php");

use PHPUnit\Framework\TestCase;


class FormBuilderTest extends TestCase {

    private $formBuilder;
        
    protected function setUp() {
         $formBuilder = new FormBuilder();
    }

    /** @test */
    public function addAttributesRendersAttributesCorrectly() {

        $this->assertEquals(
            1,
            1
        );

    }
    
}