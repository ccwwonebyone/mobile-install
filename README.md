#### 代码风格
* Preferences > Editor > Code Style > PHP > Scheme > 点齿轮 > import scheme > Intellij IDEA code style XML > 选择项目根目录下code_style.xml
* 提交代码前必须格式化代码

#### 代码编码风格自动化处理

> 下面方式兼容mac和window

首先，下载 `php-cs-fixer` 文件：

```
wget https://cs.symfony.com/download/php-cs-fixer-v2.phar -O php-cs-fixer.phar
```

然后就可以运行了：

```
composer cs-fixer
```

#### 项目规范
[项目规范](./PROJECT_PECIFICATION.md)

#### 数据库规范
[Mysql数据库规范](./MYSQL_PECIFICATION.md)


#### 全局辅助函数

* 文件位置：``bootstrap/functions.php``

#### Route

* 长路由命名统一用底杠 _ 拼接，如：contract_equipments

#### Model

* 模型层职能：主要负责数据库交互，模型定义，模型字段验证定义等
* 默认提供两种基类
    * ``\App\Models\BaseModel => 支持mysql``
    * ``\App\Models\BaseMoloquet => 支持mongoDB``
* 非特殊情况，Model均需加上软删除
    * ``use SoftDeletes;``
* 所有Model都必须定义fillable的字段列表
#### Service

* 服务层职能：主要负责业务逻辑的封装
* 统一继承``\App\Services\BaseService``
* 服务调用统一使用单例调用，比如：
    * ``$address = AddressService::instance()->getAddressByUserAndId($this->user(), $addressId);``

#### Controller

* 控制层职能：参数验证、简单验证逻辑、数据拼装、结果返回
* 保持控制层尽可能简洁，判断标准：40行代码以内
* 每个接口都必须加上api注释


#### 分支使用规范
**master 分支**


1. master 为主分支，也是用于部署生产环境的分支，确保master分支稳定性
2. master 分支只能由release分支以及hotfix分支合并，任何时间都不能直接修改代码


**dev 分支**

1. dev 为开发分支，始终保持最新完成以及bug修复后的代码
2. 一般开发的新功能时，feature分支都是基于develop分支下创建的

**feature 分支**

1. 开发新功能时，以dev为基础创建feature分支
2. 分支命名: feature/ 开头的为特性分支【日期】， 命名规则:
```
feature/user_module
feature/cart_module
```
**test 分支**

1. test 为测试人员测试所使用分支，开发新功能在dev分支联调通过后，由个人feature分支合并到test分支进入测试阶段

**release 分支**

1. release 为预上线分支，发布提测阶段，会release分支代码为基准提测
2. 当有一组feature开发完成，首先会合并到dev分支，进入联调。进入提测时，合并到test分支。
3. 如果测试过程中若存在bug需要修复，则在个人feature分支修复并往dev、test合并修复。
4. 当测试完成之后，合并release分支，预发布分支通过，合并release到master分支，此时master为最新代码，用作上线。

**hotfix 分支**

1. 分支命名: hotfix/ 开头的为修复分支，它的命名规则与 feature 分支类似
2. 线上出现紧急问题时，需要及时修复，以master分支为基线，创建hotfix分支，修复完成后，需要合并到master分支和dev分支

#### 常见任务

**增加新功能**

```
(develop)$: git checkout -b feature/xxx            # 从develop建立特性分支
(feature/xxx)$: blabla                         # 开发
(feature/xxx)$: git add xxx
(feature/xxx)$: git commit -m 'commit comment'
(dev)$: git merge feature/xxx --no-ff          # 把特性分支合并到dev联调自测。
```

**修复紧急bug**

```
(master)$: git checkout -b hotfix/xxx         # 从master建立hotfix分支
(hotfix/xxx)$: blabla                         # 开发
(hotfix/xxx)$: git add xxx
(hotfix/xxx)$: git commit -m 'commit comment'
(master)$: git merge hotfix/xxx --no-ff       # 把hotfix分支合并到master，并上线到生产环境
(develop)$: git merge hotfix/xxx --no-ff          # 把hotfix分支合并到develop，同步代码
```

**测试环境代码**

```
(test)$ git merge feature/xxx --no-ff          # 提交测试
(release)$  git merge feature/xxx --no-ff      # 测试通过之后将要上线且已经通过测试的功能分支，提交至预发布release环境集成测试
(develop)$: git merge release --no-ff          # 将测试好的release把dev分支合并到develop
```

**生产环境上线**

```
(master)$: git merge release --no-ff          # 把release合并到master，运维人员操作
```



#### 代码提交规范
**参数说明**

1. type: 代表某次提交的类型，比如是修复一个bug还是增加一个新的feature。  目前支持以下8种类型:

```
  feat：新功能（feature）
  fix：  修补bug
  docs： 文档（documentation）
  style： 格式（不影响代码运行的变动）
  refactor：重构（即不是新增功能，也不是修改bug的代码变动）
  test：增加测试
  chore：构建过程或辅助工具的变动
  perf：优化相关，比如提升性能、体验
```

2. subject: 是 commit 目的的简短描述，4到50个字符,  与冒号之间带空格。配置hook强制检查message


```
 1、commit-msg文件放到: 工作目录/.git/hooks/下     
 2、设置commit-msg权限为可执行  执行命令:chmod u+x commit-msg  
 3、ok了,测试以不规范的格式提交,会有提示,并提交失败：     配置message模板    如果有需要可以为commit message指定一个模板文件,如果git commit时没有带信息，将会出现默认模板内容的提示 
```
