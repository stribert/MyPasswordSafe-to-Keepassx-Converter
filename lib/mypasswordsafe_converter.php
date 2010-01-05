<?php

/**
 * MypasswordsafeConverter 
 * 
 * @package Converter
 * @version $id$
 * @copyright Copyright (C) 2010 Robert Strind. All rights reserved.
 * @author Robert strind<robertstrind@gmail.com> 
 * @license LGPL {@link http://www.gnu.org/copyleft/lesser.html}
 */
class MypasswordsafeConverter {

	/**
	 * __construct
	 * 
	 * @param string $mypasswordsafe_path 
	 * @param string $keepassx_path 
	 * @access public
	 * @return void
	 */
	public function __construct($mypasswordsafe_path, $keepassx_path) {
		$this->reader = new XMLReader();
		$this->reader->open($mypasswordsafe_path, $encoding);
		$this->writer = new XMLWriter();
		$this->writer->openURI($keepassx_path);
		$this->writer->startDocument('1.0');
		$this->writer->setIndent(4);
	}

	/**
	 * convert
	 * 
	 * @access public
	 * @return void
	 */
	public function convert() {
		$lines = 0;
		$this->writer->startElement('database');
		$this->writer->startElement('group');
		$this->writer->writeElement('title', 'MyPasswordSafe top level');
		$this->writer->writeElement('icon', 0);
		while($this->reader->read()) {
			switch($this->reader->name) {
				case 'item':
					if($this->reader->nodeType == XMLReader::ELEMENT) {
						$this->writer->startElement('entry');
					}
					if($this->reader->nodeType == XMLReader::END_ELEMENT) {
						$this->writer->writeElement('expire', 'Aldri');
						$this->writer->endElement();
					}
					break;
				case 'group':
					$group = true;
					if($this->reader->nodeType == XMLReader::ELEMENT) {
						$this->writer->startElement('group');
						if($this->reader->hasAttributes) {
							$this->writer->writeElement('title', $this->reader->getAttribute('name'));
						}
						$this->writer->writeElement('icon', 0);
					}
					if($this->reader->nodeType == XMLReader::END_ELEMENT) {
						$this->writer->endElement();
					}
					break;
				case 'notes':
					if($this->reader->nodeType == XMLReader::ELEMENT) {
						if($this->reader->isEmptyElement) {
							$this->writer->writeElement('comment');
						} else {
							$this->writer->startElement('comment');
							$lines = 0;
						}
					}
					if($this->reader->nodeType == XMLReader::END_ELEMENT) {
						$this->writer->endElement();
					}
					break;
				case 'line':
					if($this->reader->nodeType == XMLReader::ELEMENT) {
						if(!$this->reader->isEmptyElement) {
							if($lines > 0) {
								$this->writer->writeElement('br');
							}
							$this->reader->read();
							$this->writer->text($this->reader->value);
							$lines++;
						}
					}
					break;
				case 'name':
					if($this->reader->nodeType == XMLReader::ELEMENT) {
						if($this->reader->isEmptyElement) {
							$this->writer->writeElement('title', '');
						} else {
							$this->reader->read();
							$this->writer->writeElement('title', $this->reader->value);
						}
					}
					break;
				case 'created':
					if($this->reader->nodeType == XMLReader::ELEMENT) {
						if($this->reader->isEmptyElement) {
							$this->writer->writeElement('creation', '');
						} else {
							$this->reader->read();
							$this->writer->writeElement('creation', $this->reader->value);
						}
					}
					break;
				case 'modified':
					if($this->reader->nodeType == XMLReader::ELEMENT) {
						if($this->reader->isEmptyElement) {
							$this->writer->writeElement('lastmod', '');
						} else {
							$this->reader->read();
							$this->writer->writeElement('lastmod', $this->reader->value);
						}
					}
					break;
				case 'accessed':
					if($this->reader->nodeType == XMLReader::ELEMENT) {
						if($this->reader->isEmptyElement) {
							$this->writer->writeElement('lastaccess', '');
						} else {
							$this->reader->read();
							$this->writer->writeElement('lastaccess', $this->reader->value);
						}
					}
					break;
				case 'user':
					if($this->reader->nodeType == XMLReader::ELEMENT) {
						if($this->reader->isEmptyElement) {
							$this->writer->writeElement('username', '');
						} else {
							$this->reader->read();
							$this->writer->writeElement('username', $this->reader->value);
						}
					}
					break;
				case 'password':
					if($this->reader->nodeType == XMLReader::ELEMENT) {
						if($this->reader->isEmptyElement) {
							$this->writer->writeElement('password', '');
						} else {
							$this->reader->read();
							$this->writer->startElement('password');
							$this->writer->writeCData($this->reader->value);
							$this->writer->endElement();
						}
					}
					break;
				default:
			}
		}
		$this->writer->endElement(); // Close top level group
		$this->writer->endElement(); // Close database tag
	}
}

