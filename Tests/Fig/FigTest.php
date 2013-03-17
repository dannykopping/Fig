<?php
namespace Fig\Test;

use Fig\Fig;
use PHPUnit_Framework_TestCase;

class FigTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        Fig::setUp(
            array(
                "name" => "Danny",
                "location" => "Johannesburg",
                "height(m)" => 1.7,
                "languages" => array("English, Afrikaans, Deutsch"),
                "family" => array(
                    "parents" => array("Rodney", "Margi"),
                    "pets" => array(
                        "type" => "dog",
                        "name" => "Lisa",
                        "breed" => "ridgeback"
                    )
                )
            )
        );
    }

    /**
     * Test that the Fig class exists before attempting to try anything else
     */
    public function testClassExists()
    {
        $this->assertTrue(class_exists("Fig\\Fig", true), "Class does not exist");
    }

    /**
     * Test getting a configuration option with no dot-notation
     */
    public function testGetSimple()
    {
        $this->assertEquals("Danny", Fig::get("name"));
    }

    /**
     * Test getting a configuration option with no dot-notation, but with a weird key name
     */
    public function testGetOddKeyName()
    {
        $this->assertEquals(1.7, Fig::get("height(m)"));
    }

    /**
     * Test getting a configuration option with dot-notation
     */
    public function testGetComplex()
    {
        $this->assertEquals(array("Rodney", "Margi"), Fig::get("family.parents"));
    }

    /**
     * Test setting a configuration option without dot-notation
     */
    public function testSetSimple()
    {
        Fig::set("library-name", "Fig");
        $this->assertEquals("Fig", Fig::get("library-name"));
    }

    /**
     * Test setting a configuration option with dot-notation
     */
    public function testSetComplex()
    {
        Fig::set("projects.open source", array("Fig"));
        $this->assertEquals(array("Fig"), Fig::get("projects.open source"));
    }

    /**
     * Test deletion of configuration option without dot-notation
     */
    public function testDeleteSimple()
    {
        Fig::set("greeting", "hello!");
        Fig::delete("greeting");
        $this->assertNull(Fig::get("greeting"));
        $this->assertArrayNotHasKey("greeting", Fig::getAll());
    }

    /**
     * Test deletion of configuration option with dot-notation
     */
    public function testDeleteComplex()
    {
        Fig::set(
            "greetings.common",
            array(
                "English" => "Hello"
            )
        );

        Fig::set(
            "greetings.uncommon",
            array(
                "English" => "Good day ol' Chap!"
            )
        );

        Fig::delete("greetings.uncommon");

        // Trying to retrieve a value for "greetings.uncommon" should return nothing
        $this->assertNull(Fig::get("greetings.uncommon"));

        // Trying to retrieve a value for a child value of "greetings.uncommon" should return nothing
        $this->assertNull(Fig::get("greetings.uncommon.English"));

        // Fig should not have a single reference to the deleted node anymore
        $all = Fig::getAll();
        $this->assertArrayNotHasKey("uncommon", $all["greetings"]);

        // Deleting a node should not affect its siblings
        $this->assertArrayHasKey("common", $all["greetings"]);
    }
}
