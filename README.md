# git svn

## svn忽略文件
```
git svn propset svn:ignore core/config/config.php
git svn propset svn:ignore html/temp/
git rm -rf log/ --cached
git svn propset svn:ignore log/
git svn show-ignore
git svn propset svn:ignore tmsapi.log
git svn propset svn:ignore upload/
```

## 1

p "message"

# 2 

```
./2.rebase.sh

```

```
[svn-remote "svn"]
	url = https://192.168.1.23/svn/yuncang
	fetch = :refs/remotes/git-svn
```