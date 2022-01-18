
$cnt = 0;
while(<>) {
	chomp;
	$line = $_;
	$line =~ s/\r//;

	print $line;
	if($cnt%2==0) {
		print " ##### ";
	} else {
		print "\n";
	}
	$cnt++;
}
