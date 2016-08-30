<?php

function generar_tablero($size){
  $matriz = [];
  foreach(range(0,$size-1) as $x ){
    foreach(range(0,$size-1) as $y ){
      $matriz[$x][$y] = rand(0,1);
    }
  }
  return $matriz;
}
function imprime_tablero($grid){
  $max_count = 0;
  foreach($grid as $fila){
    $max_count = max($max_count, count($fila));
  }
  $size = count($grid);
  foreach(range(0,$size-1) as $x ){
    foreach(range(0,$max_count-1) as $y ){
      if(array_key_exists($x, $grid) && array_key_exists($y, $grid[$x])){
        echo $grid[$x][$y];
      }
    }
    echo "\n";
  }
  echo "\n";
}

function contador_bits ($array_bits){
  $contadores = [];
  $contador = 0;
  foreach($array_bits as $k => $bit){
    if($bit==1){
      $contador++;
    } elseif ($k>0) { //solo a partir del 2do bit
        if($contador != 0){
          $contadores[] = $contador;
          $contador = 0;
        }
    }
  }
  if(count($array_bits)-1==$k){
    if($contador!=0){
      $contadores[] = $contador;
    }
  }

  //return implode(' ', $array_bits) . '  =>  ' . implode(' ', $contadores)."\n";
  return $contadores;
}

function sep(){str_repeat('=',20)."\n";}

function mover_matriz($matriz_origen, $matriz_destino, $desp_x, $desp_y){
  $max_count = 0;
  foreach($matriz_origen as $fila){
    $max_count = max($max_count, count($fila));
  }
  foreach(range(0,count($matriz_origen)-1) as $x ){
    foreach(range(0,$max_count-1) as $y ){
      if(
            array_key_exists($desp_x+$x, $matriz_destino) &&
            array_key_exists($desp_y+$y, $matriz_destino[$desp_x+$x]) &&
            array_key_exists($x, $matriz_origen) &&
            array_key_exists($y, $matriz_origen[$x])
       ){
         $matriz_destino[$desp_x+$x][$desp_y+$y] = $matriz_origen[$x][$y];
      }
    }
  }
  return $matriz_destino;
}


$size = 5;
$matriz = generar_tablero($size);
//imprime_tablero($matriz);
//sep();


// imprime contadores filas
$matriz_cfilas = [];
foreach ($matriz as $key => $fila) {
  $cf = contador_bits($fila);
  $matriz_cfilas[] = $cf;
  //echo implode(' ', $cf)."\n";
}
//imprime_tablero($matriz_cfilas);
//sep();


// imprime contadores columnas
$matriz_ccolumnas = [];
foreach (range(0,$size-1) as $x) {
  $bits_columna =[];
  foreach (range(0,$size-1) as $y) {
    $bits_columna[] = $matriz[$y][$x];
  }
  $cc = contador_bits($bits_columna);
  $matriz_ccolumnas[] = $cc;
  //echo implode(' ', $cc)."\n";
}
//imprime_tablero($matriz_ccolumnas);
//sep();


$gridview = [];
foreach(range(0,7) as $x ){
  foreach(range(0,7) as $y ){
    $gridview[$x][$y] = ' ';
  }
}


//foreach(){
//}

function transpose($array) {
    return array_map(null, ...$array);
}


// generando canvas cuadrado para transponer matriz
$gridview1 = [];
foreach(range(0,$size-1) as $x ){
  foreach(range(0,$size-1) as $y ){
    $gridview1[$x][$y] = ' ';
  }
}
// situando matriz a transponer en su canvas
$gridview1 = mover_matriz($matriz_ccolumnas, $gridview1, 0, 0);

// transponiendo
$tGridview1 = transpose($gridview1);

// invirtiendo matriz verticalmente (mirror effect)
$gridview2 = [];
foreach(range(0,$size-1) as $x ){
  foreach(range(0,$size-1) as $y ){
    $gridview2[$size-$x-1][$y] = $tGridview1[$x][$y];
  }
}
$gridview3 = [];
$maxCounters = ceil($size/2);
foreach(range(0, $maxCounters-1) as $x ){
  foreach(range(0,$size-1) as $y ){
    $gridview3[$x][$y] = $gridview2[$x+$maxCounters-1][$y];
  }
}
//imprime_tablero($gridview3);

// moviendo una matriz dentro de otra:
$gridview = mover_matriz($matriz, $gridview, 3, 0);
$gridview = mover_matriz($matriz_cfilas, $gridview, 3, 5);
$gridview = mover_matriz($gridview3, $gridview, 0, 0);
//imprime_tablero($gridview);

function imprime_nonograma($gridview, $size){
  $maxCounters = ceil($size/2);
  $totalSize = $size + $maxCounters;

  $outer_edge =
    '+' . str_repeat('=', $size*2 - 1) .
    '+' . str_repeat('=', $maxCounters*2 - 1) .
    '+' . PHP_EOL;

  echo $outer_edge;
  foreach(range(0,$maxCounters-1) as $x){
    $row = [];
    foreach(range(0,$size-1) as $y){
      $row[] = $gridview[$x][$y];
    }
    echo "‖" . implode('|',$row) . "‖".str_repeat(' ',$maxCounters*2-1)."‖" . PHP_EOL;
  }
  echo $outer_edge;

  foreach(range(0,$size-1) as $x){
    $row1 = $row2 = [];
    foreach(range(0,$totalSize-1) as $y){
      if($y<$size){
        $row1[] = $gridview[$x+$maxCounters][$y];
      }else{
        $row2[] = $gridview[$x+$maxCounters][$y];
      }

    }
    echo "‖" . implode('|',$row1) . "‖" . implode('|',$row2) . "‖" . PHP_EOL;
  }


  echo $outer_edge;

}

imprime_nonograma($gridview, $size);
