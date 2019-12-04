#!/usr/bin/env bash
export CMD_PATH=$(cd `dirname $0`; pwd)
cd $CMD_PATH
p "$1"

ls -al
git svn fetch
git svn rebase
git svn dcommit


