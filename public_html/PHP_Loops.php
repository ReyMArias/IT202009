<?php
//PHP Loops Homework

echo "1.) Create array of numbers: <br\n>";

$numbers = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 0);
echo var_dump($numbers);
echo "<br>\n <br>\n";

##################################

echo "2.) Loop the array: <br>\n";
foreach($numbers as $num){
	echo "$num <br>\n";
}
echo "<br>\n";

###################################

echo "3.) Loop only evens: <br>\n";
# works as I am testing to see if each element produces a remainder when divided by 2. If they do not, it prints that number
$count = count($numbers);
for($i = 0; $i < $count; $i++){
	if($numbers[$i] % 2 ==0) {
		echo "$numbers[$i] <br>\n";
	}
}
echo "<br>\n";

#######################

echo "4.) explain your code: <br>\n";

echo "Question 3 works as I am testing to see if each element produces a remainder when divided by 2. If they do not, it prints that number";
?>
