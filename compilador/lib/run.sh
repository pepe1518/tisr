#!/bin/sh

#ulimit -t 3

# timeout evaluation
echo '' > $6
echo '' > $7
echo '' > $8
echo '' > $9

perl $1 -t $2 -o $7 $4 < $5 > /dev/null 2> $8
perl $1 -t $2 -m $3 -o $7 $4 < $5 > $6 2> $9
