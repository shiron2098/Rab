<?php
/*$a = __DIR__ . '/VendmaxAndNayaxAndConnect/File/vmsasha3/exec t2s_exportPos No/data2019-08-12 10:07:05';
$ser = __DIR__ . '/sere';
$response = file_get_contents($ser);*/
/*$xml = simplexml_load_string($response);
$json_string = json_encode($xml);
$object = json_decode($json_string);
$ver = $object;
foreach ($object as $photo ){
     $re = $photo;
     foreach($re as $string){
         $aaaa = $string;
         print_R($aaaa);
         foreach ( $aaaa as $reps){
             $rtr = $reps;
         }
     }
}*/

class Simple_Parser
{
    var $parser;
    var $error_code;
    var $error_string;
    var $current_line;
    var $current_column;
    var $data = array();
    var $datas = array();

    function parse($data)
    {
        $this->parser = xml_parser_create('UTF-8');
        xml_set_object($this->parser, $this);
        xml_parser_set_option($this->parser, XML_OPTION_SKIP_WHITE, 1);
        xml_set_element_handler($this->parser, 'tag_open', 'tag_close');
        xml_set_character_data_handler($this->parser, 'cdata');
        if (!xml_parse($this->parser, $data))
        {
            $this->data = array();
            $this->error_code = xml_get_error_code($this->parser);
            $this->error_string = xml_error_string($this->error_code);
            $this->current_line = xml_get_current_line_number($this->parser);
            $this->current_column = xml_get_current_column_number($this->parser);
        }
        else
        {
            $this->data = $this->data['child'];
        }
        xml_parser_free($this->parser);

    }

    function tag_open($parser, $tag, $attribs)
    {
        $this->data['child'][$tag][] = array('data' => '', 'attribs' => $attribs, 'child' => array());
        $this->datas[] =& $this->data;
        $this->data =& $this->data['child'][$tag][count($this->data['child'][$tag])-1];
    }

    function cdata($parser, $cdata)
    {
        $this->data['data'] .= $cdata;
    }

    function tag_close($parser, $tag)
    {
        $this->data =& $this->datas[count($this->datas)-1];
        array_pop($this->datas);
    }
}
/*$a = 0;
$xml_parser = new Simple_Parser;
$xml_parser->parse($response);
$xmlstring = $xml_parser->data['NEWDATASET']['0']['child']['TABLE'];
foreach ($xmlstring as $atribut){
    foreach($atribut['attribs'] as $fullstring){
        $parser=xml_parser_create();
        $file[$a] = $fullstring;
        $a++;
        print_r($a);
        file_put_contents(__DIR__ . '/sere',$file);
    }
}*/