<?
//读取数据
function GetDataArr($objPHPExcel,$sheet) {
	
  $sheet = $objPHPExcel->getSheet($sheet);// 获取Sheet 
  $highestRow = $sheet->getHighestRow(); // 取得总行数 
  $highestColumn = $sheet->getHighestColumn(); // 取得总列数
  
  $LetArr = array("A" => 1,"B" => 2,"C" => 3,"D" => 4,"E" => 5,"F" => 6,"G" => 7,"H" => 8,"I" => 9,"J" => 10,"K" => 11,"L" => 12,"M" => 13,"N" => 14,"O" => 15,"P" => 16,"Q" => 17,"R" => 18,"S" => 19,"T" => 20,"U" => 21,"V" => 22,"W" => 23,"X" => 24,"Y" => 25,"Z" => 26);
  $LetKey = array_flip($LetArr);// 键值交换
  $len = strlen($highestColumn);// 列宽
  $highest = 0;// 列数
  $leave = 0;// 
  $DataArr=NULL;
  if($len == 2){
	 $A = substr($highestColumn,0,1);
	 $B = substr($highestColumn,1,2);
	 $highest = $LetArr[$A]*26+$LetArr[$B];
	 $leave = $LetArr[$A];
  }else{
	 $B = $highestColumn;
	 $highest = $LetArr[$B];
  }
  // 取值,装入数组
  for($i = 1;$i <= $highestRow;$i++) {
      	for($j = 0;$j <= $highest-1;$j++) {
	    	if($j <= 25){
				$k = $LetKey[$j+1];
			}else{
				$k = $LetKey[intval($j/26)].$LetKey[($j%26)+1];
			}
			$DataArr[$i-1][$j]=trim(M3_UTF8($objPHPExcel->getActiveSheet()->getCell("$k$i")->getValue()));
			//-----------李志林增加 去除富文本和公式----------------
			if($DataArr[$i-1][$j] instanceof PHPExcel_RichText)     //富文本转换字符串  
            {
				$DataArr[$i-1][$j] = trim($DataArr[$i-1][$j]->__toString());  
			}
            if(substr($DataArr[$i-1][$j],0,1)=='=') //公式  
            {	
				$DataArr[$i-1][$j]=trim(M3_UTF8($objPHPExcel->getActiveSheet()->getCell("$k$i")->getCalculatedValue()));   
			} 
		}
  }
  return $DataArr;
}
?>