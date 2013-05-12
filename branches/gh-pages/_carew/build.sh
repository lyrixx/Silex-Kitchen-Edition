#!/bin/bash

BASE=`dirname $0`

$BASE/vendor/bin/carew carew:build --base-dir=$BASE --web-dir=$BASE/.. -v
