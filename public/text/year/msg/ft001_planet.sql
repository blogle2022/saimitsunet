while(<>) {
	chomp( $datestr = $_ );

	
print <<EOM;
select Sun,Mercury,Venus,Mars,Jupiter,Saturn,Uranus,Neptune,Pluto WHERE datestr='$datestr';
EOM
}
