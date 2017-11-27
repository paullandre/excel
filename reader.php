<?php
require_once 'excel_reader2.php';

define("_MAIN", 0);
define("_NUM_ROWS", "numRows");
define("_NUM_COLS", "numCols");
define("_CELLS"   , "cells");

$from = "";
$to = "";
$rows = "";
$rsItems = array();

$header = array();
$arrs   = array();
$rows2 = 0;
$fields = 0;
$records;
$heads = "";
$datas = "";
    
function main()
{    
    if(null == $_GET) die("Null");
    if(null == $_GET['file'] && null != $_GET['json_file']) die("No excel file");
    if(null != $_GET['file'] && null == $_GET['json_file']) die("Please specify json filename");
        
    $file = $_GET['file'];
    $json_file = $_GET['json_file'];
    
    $data = new Spreadsheet_Excel_Reader($file);

    $rows   = $data->sheets[_MAIN][_NUM_ROWS];
    $fields = $data->sheets[_MAIN][_NUM_COLS];

    $records = $data->sheets[_MAIN][_CELLS];
    /* Reel Type */
    $to_clean = explode("x", $records[7][4]);
    $row = (int)$to_clean[0];
    $col = (int)$to_clean[1];
    /*************/
    
    $indent = "   ";    
    $json = $indent;
    $header = json_encode(array("the_game" => 
                array("version" => 1, 
                    "genesis_id" => "DESIRED_ID",
                    "description" => null, 
                    "configured_rtp" => (float)$records[6][4],
                    "game_state_collect_key" => null,
                    "depth" => 0,
                    "requires_signing" => false,
                    "keeps_state" => false,
                    "config" => array("win_line" => 1,
                        "all_pays_bet_base" => 1,
                        "wild_multiplier" => 1,
                        "bet_multipliers" => array("1" => 1),
                        "wilds" => ["WILD"],
                        "scattters" => ["SCATTER"],
                        "default_bet" => 0,
                        "min_lines_per_bet" => 1,
                        "coin_sizes" => [1],
                        "default_coin_size" => 1,
                        "allowed_multipliers" => [],
                        "allowed_coin_sizes" => []),
                    "points_config" => null,
                    "game_type" => 4,
                    "reels" => array("rows" => $row,
                        "columns" => $col,
                        "has_composite_symbols" => false,
                        "irregular_reels" => false,
                        "reels" => [array("is_independant" => false,
                            "number" => 1,
                            "irregular_rows" => 0,
                            "elements" => call_user_func(function() use ($records) 
                                {         
                                    $fields = array(); 
                                    for($i = 156; $i <= 1000; $i++)
                                    {
                                        if($records[$i][3] == "")
                                        {
                                            return $fields;
                                        }
                                        
                                        $fields[] = array("position" => $records[$i][2], "symbol" => $records[$i][3]);
                                    }
                                    
                                    return $fields;
                                })             
                            ),
                            array("is_independant" => false,
                            "number" => 2,
                            "irregular_rows" => 0,
                            "elements" => call_user_func(function() use ($records) 
                                {         
                                    $fields = array(); 
                                    for($i = 156; $i <= 1000; $i++)
                                    {
                                        if($records[$i][4] == "")
                                        {
                                            return $fields;
                                        }
                                        
                                        $fields[] = array("position" => $records[$i][2], "symbol" => $records[$i][4]);
                                    }
                                    
                                    return $fields;
                                })             
                            ),
                            array("is_independant" => false,
                            "number" => 3,
                            "irregular_rows" => 0,
                            "elements" => call_user_func(function() use ($records) 
                                {         
                                    $fields = array(); 
                                    for($i = 156; $i <= 1000; $i++)
                                    {
                                        if($records[$i][5] == "")
                                        {
                                            return $fields;
                                        }
                                        
                                        $fields[] = array("position" => $records[$i][2], "symbol" => $records[$i][5]);
                                    }
                                    
                                    return $fields;
                                })             
                            ),
                            array("is_independant" => false,
                            "number" => 4,
                            "irregular_rows" => 0,
                            "elements" => call_user_func(function() use ($records) 
                                {         
                                    $fields = array(); 
                                    for($i = 156; $i <= 1000; $i++)
                                    {
                                        if($records[$i][6] == "")
                                        {
                                            return $fields;
                                        }
                                        
                                        $fields[] = array("position" => $records[$i][2], "symbol" => $records[$i][6]);
                                    }
                                    
                                    return $fields;
                                })             
                            ),
                            array("is_independant" => false,
                            "number" => 5,
                            "irregular_rows" => 0,
                            "elements" => call_user_func(function() use ($records) 
                                {         
                                    $fields = array(); 
                                    for($i = 156; $i <= 1000; $i++)
                                    {
                                        if($records[$i][7] == "")
                                        {
                                            return $fields;
                                        }
                                        
                                        $fields[] = array("position" => $records[$i][2], "symbol" => $records[$i][7]);
                                    }
                                    
                                    return $fields;
                                })             
                            )
                            ]
                        ),
                        "lines" => null,
                        "pay_table" => array("entries" => call_user_func(function() use ($records) 
                            {  
                                $fields = array();
                                for($i = 93; $i <= 103; $i++)
                                {
                                    $fields[] = array("symbol" => $records[$i][2],
                                        "pay_table" => [array(call_user_func(function()use($records)
                                        {
                                            $e = array();
                                            for($x = 7; $x <= 3; $x--)
                                            {
                                                $e[] = array("of_akind" => $records[91][$x],
                                                    "prize" => $records[$i][$x]);
                                            }
                                            
                                            return $e;
                                        })
                                        )
                                    ]);
                                }
                                                                
                                return $fields;
                            })                        
                        )
                    )
                )
            );
    
    $json .= $header;

    $write_file = fopen($json_file, "w");
    fwrite($write_file, $json);
    fclose($write_file);
    
    die(print($json));
}

main();