<?php
ini_set('memory_limit', '4095M'); 
set_time_limit(0);
/**
 * vineyards.php is a PHP script which is coded in PHP5 and runs on PHP CLI.
 * 
 * Class Vineyards contains  functions which iterate the file "person_wine_3.txt" and generate the final list of all wines allotement and  generate the  file with desired result. In 
 *  this class i generate three function thats works on three stages of the puzzle.
 *
 * @package    Vineyards Wine Allotment Puzzle
 * @author     Sandeep Kumar <er.sandeepauluck@gmail.com>
 * @version    1
  */
class Vineyards{
	/**
     * Class Vineyards
     */
	
	public	$allWineList;
	public  $personWineWishlist;
	public	$wineFinalAllotmentList;
    public	$totalWineSold;
	
	/**
     * Constructor function 
     *
     * This Function declare the data types of the variables.
     */
	 
	function __construct(){
	    
		$this->allWineList 		       = array();
		$this->personWineWishlist	   = array();
		$this->wineFinalAllotmentList  = array();
		$this->totalWineSold 		   = 0;
	}
    
	/**
     * function generateWineList
     *
     * This Function create the list of all the wines availables in Vineyards Shop and also create list of wishlist of wines.   .
     *
     */
	
	public function generateWineList($wineWishlistFileName){
	
	     $fp = fopen($wineWishlistFileName,"r");
		 while ( !feof($fp) ){
				$line = fgets($fp, 2048);
			    $data = str_getcsv($line, "\t"); 
				if($data[0]!=''){
					$personName = trim($data[0]);
					$wineCode = trim($data[1]);
					if(!array_key_exists($wineCode, $this->personWineWishlist)){
						$this->personWineWishlist[$wineCode] = [];
					}
					$this->personWineWishlist[$wineCode][] = $personName;
					$this->allWineList[]=$wineCode;
				}
		 } 
		 fclose($fp); 
		 $this->allWineList = array_unique($this->allWineList);
		 
	}
	
	
	/**
     * function generateWineAllotmentList
     *
     * this function generate the final allotment list of wines to persons .
     *
     */
	public function generateWineAllotmentList(){
	    
		foreach ($this->allWineList as $key => $wineCode){
		     foreach ($this->personWineWishlist[$wineCode] as $keys => $personCode){
			      if(!array_key_exists($personCode, $this->wineFinalAllotmentList)){
						$this->wineFinalAllotmentList[$personCode][] = $wineCode;
						$this->totalWineSold++;
						break;
				   }else{
						if(count($this->wineFinalAllotmentList[$personCode])<3){
							  $this->wineFinalAllotmentList[$personCode][] = $wineCode;
							  $this->totalWineSold++;
							  break;
						}
				   }
			 }
		}
    }
	/**
     * function exportWineAllotmentList
     *
     * this function is used to generate the required tsv file with desired result .
     *
     */
	public function exportWineAllotmentList($exportFilename){
	
	   $fh = fopen($exportFilename, "w");
	   $heading="Total number of wines to be sold in aggregate is ".$this->totalWineSold." by vineyards";
		fwrite($fh, $heading );
		foreach ($this->wineFinalAllotmentList as $personCode=>$winelist){
			foreach ($this->wineFinalAllotmentList[$personCode] as $key => $wineCode){
				fwrite($fh, "\n".$personCode." \t ".$wineCode);
			}
		}
		fclose($fh);
		echo $heading.'</br>';
		echo "Click Here To <a href='$exportFilename'>Download Result File</a>";
	
	}
}

$wine = new Vineyards();
$wine->generateWineList("person_wine_3.txt");
$wine->generateWineAllotmentList();
$wine->exportWineAllotmentList('final_list.txt');
?>