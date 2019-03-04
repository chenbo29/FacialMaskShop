## 环境配置，clone代码

参考：https://blog.csdn.net/qq_36602939/article/details/88149742

## 数据库配置

*把

`/application/database.php_read`

*复制一份

`/application/database.php`

*把群里的数据库账号密码输入`/application/database.php`中

访问本地站点网址


## 代码提交命令

`git add mmm.sss`          //mmm为文件名称，sss为文件拓展名（   常用git add .   ）


`git commit -m "hhh"`      //hhh为git commit 提交信息，是对这个提交的概述


`git pull`                 //拉下GitHub上的仓库的代码


`git push`                 //把本地代码更新到GitHub上的仓库


## 项目介绍

本项目初期为公众号项目，

后端PHP在 /application/mobile/controller 下开发业务代码

在 /application/admin/controller 下开发后台管理代码

前端在 /template/mobile/rainbow 下开发前端代码