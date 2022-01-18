while(<>) {
	chop;
	chomp( $line = $_ );

	($b, $p, $gorb, $type, $idx) = split /\t/, $_;

	print "INSERT INTO progress (birth, prog, gorb, type, idx) VALUES ('$b', '$p', '$gorb', '$type', '$idx');\n";
}

