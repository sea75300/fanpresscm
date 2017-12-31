<?php

require_once dirname(dirname(__DIR__)).'/testBase.php';
require_once dirname(dirname(dirname(__DIR__))).'/inc/lib/nkorg/yatdl/parser.php';

class yatdlTest extends testBase {

    public function setUp() {

        $this->object = new fpcm\model\system\yatdl(__DIR__.'/test.yml');
        
        $GLOBALS['check_cols'] = [
            'id_bigint',
            'int_col',
            'char_col',
            'varchar_col',
            'text_col',
            'mtext_col',
            'ltext_col',
            'bool_col',
            'bin_col',
            'lbin_col',
            'float_col',
            'double_col'
        ];
        
    }
    
    public function testGetArray() {

        $data = $this->object->getArray();
        $this->assertTrue(is_array($data));

        $this->assertEquals('sample', $data['name']);
        $this->assertEquals('id_bigint', $data['primarykey']);
        $this->assertEquals('utf8', $data['charset']);
        $this->assertEquals('InnoDB', $data['engine']);
        $this->assertEquals('id_bigint', $data['autoincrement']['colname']);
        $this->assertEquals(1, $data['autoincrement']['start']);
        $this->assertTrue(is_array($data['indices']));
        $this->assertTrue(isset($data['indices']['int_colidx']));
        $this->assertEquals('int_col', $data['indices']['int_colidx']['col']);
        $this->assertTrue($data['indices']['int_colidx']['isUnqiue']);

        $cols = array_keys($data['cols']);
        foreach ($GLOBALS['check_cols'] as $col) {
            $this->assertTrue(in_array($col, $cols));            
        }

    }
    
    public function testParse() {

        $haystack = [
            \nkorg\yatdl\parser::ERROR_YAMLCHECK_FAILED,
            \nkorg\yatdl\parser::ERROR_YAMLPARSER_COLS,
            \nkorg\yatdl\parser::ERROR_YAMLPARSER_AUTOINCREMENT,
            \nkorg\yatdl\parser::ERROR_YAMLPARSER_INDICES,
            0
        ];
        
        $result = (int) $this->object->parse();
        $this->assertFalse(in_array($result, $haystack));

    }
    
    public function testGetSqlString() {

        $this->assertTrue($this->object->parse());
        
        $data = $this->object->getSqlString();

        if (fpcm\classes\baseconfig::$fpcmDatabase->getDbtype() === 'pgsql') {
            
            $this->assertContains('CREATE TABLE {{dbpref}}_sample', $data, 'Invalid create table data', true);
            $this->assertContains('id_bigint bigint ', $data, 'Invalid "id_bigint" col', true);
            $this->assertContains('int_col int ', $data, 'Invalid "int_col" col', true);
            $this->assertContains('char_col char(255) ', $data, 'Invalid "char_col" col', true);
            $this->assertContains('varchar_col character varying(255) ', $data, 'Invalid "varchar_col" col', true);
            $this->assertContains('text_col text ', $data, 'Invalid "text_col" col', true);
            $this->assertContains('mtext_col text ', $data, 'Invalid "mtext_col" col', true);
            $this->assertContains('ltext_col text ', $data, 'Invalid "ltext_col" col', true);
            $this->assertContains('bool_col smallint ', $data, 'Invalid "bool_col" col', true);
            $this->assertContains('bin_col bytea ', $data, 'Invalid "bin_col" col', true);
            $this->assertContains('lbin_col bytea ', $data, 'Invalid "lbin_col" col', true);
            $this->assertContains('float_col real ', $data, 'Invalid "float_col" col', true);
            $this->assertContains('double_col decimal ', $data, 'Invalid "double_col" col', true);

        }
        if (fpcm\classes\baseconfig::$fpcmDatabase->getDbtype() === 'mysql') {

            $this->assertContains('CREATE TABLE IF NOT EXISTS `{{dbpref}}_sample`', $data, 'Invalid create table data', true);
            $this->assertContains('`id_bigint` bigint(20) ', $data, 'Invalid "id_bigint" col', true);
            $this->assertContains('`int_col` int(11) ', $data, 'Invalid "int_col" col', true);
            $this->assertContains('`char_col` char(255) ', $data, 'Invalid "char_col" col', true);
            $this->assertContains('`varchar_col` varchar(255) ', $data, 'Invalid "varchar_col" col', true);
            $this->assertContains('`text_col` text ', $data, 'Invalid "text_col" col', true);
            $this->assertContains('`mtext_col` mediumtext ', $data, 'Invalid "mtext_col" col', true);
            $this->assertContains('`ltext_col` longtext ', $data, 'Invalid "ltext_col" col', true);
            $this->assertContains('`bool_col` tinyint(4) ', $data, 'Invalid "bool_col" col', true);
            $this->assertContains('`bin_col` blob ', $data, 'Invalid "bin_col" col', true);
            $this->assertContains('`lbin_col` longblob ', $data, 'Invalid "lbin_col" col', true);
            $this->assertContains('`float_col` float(11) ', $data, 'Invalid "float_col" col', true);
            $this->assertContains('`double_col` decimal(11) ', $data, 'Invalid "double_col" col', true);

        }
        

    }

}