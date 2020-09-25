#!/bin/bash

function prepare_manifests() {
    REPLACEMENTS=$2
    DIR=$1
    OUTPUT_BASE=$3

    mkdir $OUTPUT_BASE

    cp $DIR/*.yaml $OUTPUT_BASE

    FILES=$DIR/templated/*
    for f in $FILES
    do
      OUTPUT_FILE=$OUTPUT_BASE/$(basename $f)
      sed $REPLACEMENTS $f > $OUTPUT_FILE
    done
}
