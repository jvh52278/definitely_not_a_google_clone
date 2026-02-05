#!/bin/bash

info_line=$(mpstat | grep "all")
#echo "$info_line"

line_length=length=${#info_line} #debug

last_space=0
# loop through text
for ((x=0; x<line_length; x=x+1))
do
        if [ "${info_line:x:1}" == " " ]
        then
                last_space=$x
        fi
done

#echo "$last_space" #debug

value_string=""

for ((x=$last_space+1; x<line_length; x=x+1))
do
        char="${info_line:x:1}"
        #append selected char to the value string
        value_string=${value_string}${info_line:x:1}
done

echo "scale=2; 100 - $value_string" | bc