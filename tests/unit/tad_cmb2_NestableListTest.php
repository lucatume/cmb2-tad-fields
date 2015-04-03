<?php


	class tad_cmb2_NestableListTest extends \PHPUnit_Framework_TestCase {

		protected function setUp() {
			$this->sut = new tad_cmb2_NestableList();
		}

		protected function tearDown() {
		}

		/**
		 * @test
		 * it should be instantiatable
		 */
		public function it_should_be_instantiatable() {
			$this->assertInstanceOf( 'tad_cmb2_NestableList', $this->sut );
		}

	}