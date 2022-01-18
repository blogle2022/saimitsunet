
                $baseDeg{"ar"} = 0;
                $baseDeg{"ta"} = 30;
                $baseDeg{"ge"} = 60;
                $baseDeg{"cn"} = 90;
                $baseDeg{"le"} = 120;
                $baseDeg{"vi"} = 150;
                $baseDeg{"li"} = 180;
                $baseDeg{"sc"} = 210;
                $baseDeg{"sa"} = 240;
                $baseDeg{"cp"} = 270;
                $baseDeg{"aq"} = 300;
                $baseDeg{"pi"} = 330;


while(<>) {
	
	chomp;
	$line = $_;

	$key = substr($line,0,16);
	$key =~ s/ //g;

	$dd = substr($line,16,2);
	$dd =~ s/ //g;

	$sign = substr($line,19,2);
	$sign =~ s/ //g;

	$mmss = substr($line,22,10);
	$mmss =~ s/ //g;

	($min, $sec) = split /'/, $mmss;
	$sec_total = $min * 60 + $sec;
	$right = $sec_total / 3600;
	$pos = $dd + $right + $baseDeg{"$sign"};
	print $pos . ",";
	#print '$trans["' . $key . '"][] = '."$pos;\n";




}


