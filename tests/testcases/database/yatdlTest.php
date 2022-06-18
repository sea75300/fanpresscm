<?php

require_once dirname(dirname(__DIR__)) . '/testBase.php';
require_once dirname(dirname(dirname(__DIR__))) . '/lib/nkorg/yatdl/parser.php';

class yatdlTest extends testBase {

    protected function setUp() : void
    {
        $this->object = new fpcm\model\system\yatdl(__DIR__ . '/test.yml');

        $GLOBALS['check_cols'] = [
            'id',
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

    public function testGetArray()
    {
        $data = $this->object->getArray();
        $this->assertIsArray($data);

        $this->assertEquals('sample', $data['name']);
        $this->assertEquals('id', $data['primarykey']);
        $this->assertEquals('utf8', $data['charset']);
        $this->assertEquals('InnoDB', $data['engine']);
        $this->assertEquals('id', $data['autoincrement']['colname']);
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

    public function testGetTable()
    {
        $data = $this->object->getTable();
        $this->assertInstanceOf('\\nkorg\\yatdl\\tableItem', $data);

        $this->assertEquals('sample', $data->name);
        $this->assertEquals('id', $data->primarykey);
        $this->assertEquals('utf8', $data->charset);
        $this->assertEquals('InnoDB', $data->engine);
        $this->assertEquals('id', $data->autoincrement['colname']);
        $this->assertEquals(1, $data->autoincrement['start']);
        $this->assertTrue(is_array($data->indices));
        $this->assertTrue(isset($data->indices['int_colidx']));
        $this->assertEquals('int_col', $data->indices['int_colidx']['col']);
        $this->assertTrue($data->indices['int_colidx']['isUnqiue']);

        $cols = array_keys($data->cols);
        foreach ($GLOBALS['check_cols'] as $col) {
            $this->assertTrue(in_array($col, $cols));
        }
    }

    public function testParse()
    {
        try {
            $result = (int) $this->object->parse();
        } catch (\Error $exc) {
            echo $exc->getTraceAsString();
        }

        $this->assertFalse(in_array($result, [
            \nkorg\yatdl\parser::ERROR_YAMLCHECK_FAILED,
            \nkorg\yatdl\parser::ERROR_YAMLPARSER_COLS,
            \nkorg\yatdl\parser::ERROR_YAMLPARSER_AUTOINCREMENT,
            \nkorg\yatdl\parser::ERROR_YAMLPARSER_INDICES,
            0
        ]));
    }

    public function testGetSqlString()
    {
        try {
            $this->assertTrue($this->object->parse());
        } catch (\Error $exc) {
            echo $exc->getTraceAsString();
        }        

        $data = $this->object->getSqlString();

        if (fpcm\classes\loader::getObject('\fpcm\classes\database')->getDbtype() === 'pgsql') {
            $this->assertStringContainsString('CREATE TABLE {{dbpref}}_sample', $data, 'Invalid create table data', true);
            $this->assertStringContainsString('id bigint ', $data, 'Invalid "id" col', true);
            $this->assertStringContainsString('int_col int ', $data, 'Invalid "int_col" col', true);
            $this->assertStringContainsString('char_col char(255) ', $data, 'Invalid "char_col" col', true);
            $this->assertStringContainsString('varchar_col character varying(255) ', $data, 'Invalid "varchar_col" col', true);
            $this->assertStringContainsString('text_col text ', $data, 'Invalid "text_col" col', true);
            $this->assertStringContainsString('mtext_col text ', $data, 'Invalid "mtext_col" col', true);
            $this->assertStringContainsString('ltext_col text ', $data, 'Invalid "ltext_col" col', true);
            $this->assertStringContainsString('bool_col smallint ', $data, 'Invalid "bool_col" col', true);
            $this->assertStringContainsString('bin_col bytea ', $data, 'Invalid "bin_col" col', true);
            $this->assertStringContainsString('lbin_col bytea ', $data, 'Invalid "lbin_col" col', true);
            $this->assertStringContainsString('float_col real ', $data, 'Invalid "float_col" col', true);
            $this->assertStringContainsString('double_col decimal ', $data, 'Invalid "double_col" col', true);
            $this->assertStringContainsString('DEFAULT \'YaTDL Parser Char\'', $data, 'Invalid "DEFAULT" data', true);
        }

        if (fpcm\classes\loader::getObject('\fpcm\classes\database')->getDbtype() === 'mysql') {
            $this->assertStringContainsString('CREATE TABLE IF NOT EXISTS `{{dbpref}}_sample`', $data, 'Invalid create table data', true);
            $this->assertStringContainsString('`id` bigint(20) ', $data, 'Invalid "id" col', true);
            $this->assertStringContainsString('`int_col` int(11) ', $data, 'Invalid "int_col" col', true);
            $this->assertStringContainsString('`char_col` char(255) ', $data, 'Invalid "char_col" col', true);
            $this->assertStringContainsString('`varchar_col` varchar(255) ', $data, 'Invalid "varchar_col" col', true);
            $this->assertStringContainsString('`text_col` text ', $data, 'Invalid "text_col" col', true);
            $this->assertStringContainsString('`mtext_col` mediumtext ', $data, 'Invalid "mtext_col" col', true);
            $this->assertStringContainsString('`ltext_col` longtext ', $data, 'Invalid "ltext_col" col', true);
            $this->assertStringContainsString('`bool_col` tinyint(4) ', $data, 'Invalid "bool_col" col', true);
            $this->assertStringContainsString('`bin_col` blob ', $data, 'Invalid "bin_col" col', true);
            $this->assertStringContainsString('`lbin_col` longblob ', $data, 'Invalid "lbin_col" col', true);
            $this->assertStringContainsString('`float_col` float(11) ', $data, 'Invalid "float_col" col', true);
            $this->assertStringContainsString('`double_col` decimal(11) ', $data, 'Invalid "double_col" col', true);
            $this->assertStringContainsString('DEFAULT \'YaTDL Parser Char\'', $data, 'Invalid "DEFAULT" data', true);
        }
    }
    
    public function testExec()
    {
        try {
            $db = new fpcm\classes\database();
            $this->assertTrue($db->execYaTdl(__DIR__ . '/test.yml'));
            $this->assertTrue($db->drop('sample'));
            
        } catch (\Error $exc) {
            echo $exc->getTraceAsString();
        }         
        
    }

}
